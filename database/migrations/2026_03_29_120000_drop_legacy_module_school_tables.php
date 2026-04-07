<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Final cutover: all foreign keys point to `schools.id`, legacy school registry tables removed.
     * Prior migrations should have merged rows into `schools` / `school_specifics_information`.
     */
    public function up(): void
    {
        // MySQL commits implicitly on DDL; do not wrap in DB::transaction().
        $this->migrateTypFldFamiliesAndSnapshots();
        $this->migrateCmprChildSchoolIds();
        $this->migrateUsersSchoolReferences();
        $this->normalizeNotificationsSchoolId();
        $this->dropLegacySchoolIdOnFireSafetyChildTables();
        $this->dropUnifiedSchoolIdOnLegacyRegistries();
        $this->dropLegacyRegistryTables();
        $this->addFinalForeignKeys();
    }

    private function migrateTypFldFamiliesAndSnapshots(): void
    {
        if (Schema::hasTable('typ_fld_families') && Schema::hasTable('typ_fld_evacuation_centers')) {
            if (!Schema::hasColumn('typ_fld_families', 'school_id')) {
                Schema::table('typ_fld_families', function (Blueprint $table) {
                    $table->unsignedBigInteger('school_id')->nullable()->after('id');
                });
            }

            if (Schema::hasColumn('typ_fld_families', 'evacuation_center_id')) {
                DB::statement('
                    UPDATE typ_fld_families f
                    INNER JOIN typ_fld_evacuation_centers ec ON ec.id = f.evacuation_center_id
                    SET f.school_id = ec.unified_school_id
                    WHERE ec.unified_school_id IS NOT NULL
                ');

                $this->tryDropForeign('typ_fld_families', 'evacuation_center_id');
                Schema::table('typ_fld_families', function (Blueprint $table) {
                    $table->dropColumn('evacuation_center_id');
                });
            }

            DB::table('typ_fld_families')->whereNotNull('school_id')
                ->whereNotIn('school_id', DB::table('schools')->pluck('id'))
                ->update(['school_id' => null]);

            $this->tryDropForeign('typ_fld_families', 'school_id');
            try {
                Schema::table('typ_fld_families', function (Blueprint $table) {
                    $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
                });
            } catch (\Throwable $e) {
            }
        }

        if (Schema::hasTable('typ_fld_monitoring_snapshots') && Schema::hasTable('typ_fld_evacuation_centers')) {
            if (!Schema::hasColumn('typ_fld_monitoring_snapshots', 'school_id')) {
                Schema::table('typ_fld_monitoring_snapshots', function (Blueprint $table) {
                    $table->unsignedBigInteger('school_id')->nullable()->after('id');
                });
            }

            if (Schema::hasColumn('typ_fld_monitoring_snapshots', 'evacuation_center_id')) {
                DB::statement('
                    UPDATE typ_fld_monitoring_snapshots s
                    INNER JOIN typ_fld_evacuation_centers ec ON ec.id = s.evacuation_center_id
                    SET s.school_id = ec.unified_school_id
                    WHERE ec.unified_school_id IS NOT NULL
                ');

                $this->tryDropForeign('typ_fld_monitoring_snapshots', 'evacuation_center_id');
                Schema::table('typ_fld_monitoring_snapshots', function (Blueprint $table) {
                    $table->dropColumn('evacuation_center_id');
                });
            }

            DB::table('typ_fld_monitoring_snapshots')->whereNotNull('school_id')
                ->whereNotIn('school_id', DB::table('schools')->pluck('id'))
                ->delete();

            $this->tryDropForeign('typ_fld_monitoring_snapshots', 'school_id');
            try {
                Schema::table('typ_fld_monitoring_snapshots', function (Blueprint $table) {
                    $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
                });
            } catch (\Throwable $e) {
            }
        }
    }

    private function migrateCmprChildSchoolIds(): void
    {
        if (!Schema::hasTable('cmpr_schl_sfty_schools')) {
            return;
        }

        foreach (['cmpr_schl_sfty_facilities', 'cmpr_schl_sfty_students', 'cmpr_schl_sfty_assessments'] as $tbl) {
            if (!Schema::hasTable($tbl)) {
                continue;
            }

            DB::statement("
                UPDATE {$tbl} t
                INNER JOIN cmpr_schl_sfty_schools s ON s.id = t.school_id
                SET t.school_id = s.unified_school_id
                WHERE s.unified_school_id IS NOT NULL
            ");

            DB::table($tbl)->whereNotNull('school_id')
                ->whereNotIn('school_id', DB::table('schools')->pluck('id'))
                ->delete();

            $this->tryDropForeign($tbl, 'school_id');

            Schema::table($tbl, function (Blueprint $table) {
                $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
            });
        }
    }

    private function migrateUsersSchoolReferences(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        foreach (['school_id', 'incident_school_id', 'typhoon_school_id', 'unified_school_id'] as $col) {
            $this->tryDropForeign('users', $col);
        }

        if (Schema::hasColumn('users', 'unified_school_id')) {
            DB::statement('UPDATE users SET school_id = unified_school_id WHERE unified_school_id IS NOT NULL');
        }

        if (Schema::hasTable('school_specifics_information')) {
            DB::statement("
                UPDATE users u
                INNER JOIN school_specifics_information ssi
                    ON ssi.module = 'fire_safety'
                    AND ssi.key = 'original_fire_safety_id'
                    AND ssi.value = CONVERT(u.school_id, CHAR) COLLATE utf8mb4_unicode_ci
                SET u.school_id = ssi.school_id
                WHERE u.school_id IS NOT NULL
            ");
        }

        if (Schema::hasTable('incident_schools')) {
            DB::statement('
                UPDATE users u
                INNER JOIN incident_schools inc ON inc.id = u.incident_school_id
                SET u.incident_school_id = inc.unified_school_id
                WHERE u.incident_school_id IS NOT NULL AND inc.unified_school_id IS NOT NULL
            ');
        }

        if (Schema::hasTable('typ_fld_evacuation_centers')) {
            DB::statement('
                UPDATE users u
                INNER JOIN typ_fld_evacuation_centers ec ON ec.id = u.typhoon_school_id
                SET u.typhoon_school_id = ec.unified_school_id
                WHERE u.typhoon_school_id IS NOT NULL AND ec.unified_school_id IS NOT NULL
            ');
        }

        if (Schema::hasColumn('users', 'unified_school_id')) {
            Schema::table('users', function (Blueprint $t) {
                $t->dropColumn('unified_school_id');
            });
        }

        foreach (['school_id', 'incident_school_id', 'typhoon_school_id'] as $col) {
            if (!Schema::hasColumn('users', $col)) {
                continue;
            }
            DB::table('users')->whereNotNull($col)
                ->whereNotIn($col, DB::table('schools')->pluck('id'))
                ->update([$col => null]);
        }
    }

    private function normalizeNotificationsSchoolId(): void
    {
        if (!Schema::hasTable('notifications')) {
            return;
        }

        if (Schema::hasColumn('notifications', 'unified_school_id')) {
            DB::statement('UPDATE notifications SET school_id = unified_school_id WHERE unified_school_id IS NOT NULL');
            $this->tryDropForeign('notifications', 'unified_school_id');
            Schema::table('notifications', function (Blueprint $t) {
                $t->dropColumn('unified_school_id');
            });
        }

        if (Schema::hasTable('school_specifics_information') && Schema::hasColumn('notifications', 'school_id')) {
            DB::statement("
                UPDATE notifications n
                INNER JOIN school_specifics_information ssi
                    ON ssi.module = 'fire_safety'
                    AND ssi.key = 'original_fire_safety_id'
                    AND ssi.value = CONVERT(n.school_id, CHAR) COLLATE utf8mb4_unicode_ci
                SET n.school_id = ssi.school_id
                WHERE n.school_id IS NOT NULL
            ");
        }
    }

    private function dropLegacySchoolIdOnFireSafetyChildTables(): void
    {
        $tables = [
            'firesafety_buildings',
            'firesafety_alarm_systems',
            'firesafety_evacuationplans',
            'fire_safety_evacuation_drills',
            'fire_safety_inspections',
            'fire_safety_archives',
            'firesafety_fire_extinguishers',
            'fire_safety_rooms',
            'fire_safety_extinguisher_inspections',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'unified_school_id')) {
                continue;
            }
            try {
                $this->tryDropForeign($table, 'school_id');
                if (Schema::hasColumn($table, 'school_id')) {
                    Schema::table($table, function (Blueprint $t) {
                        $t->dropColumn('school_id');
                    });
                }
            } catch (\Throwable $e) {
            }
        }

        foreach (['firesafety_school_snapshots', 'fire_safety_school_snapshots'] as $snapshotTable) {
            if (!Schema::hasTable($snapshotTable) || !Schema::hasColumn($snapshotTable, 'unified_school_id')) {
                continue;
            }
            try {
                $this->tryDropForeign($snapshotTable, 'school_id');
                if (Schema::hasColumn($snapshotTable, 'school_id')) {
                    Schema::table($snapshotTable, function (Blueprint $t) {
                        $t->dropColumn('school_id');
                    });
                }
            } catch (\Throwable $e) {
            }
        }
    }

    private function dropUnifiedSchoolIdOnLegacyRegistries(): void
    {
        foreach (['firesafety_school_information', 'incident_schools', 'typ_fld_evacuation_centers', 'cmpr_schl_sfty_schools'] as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'unified_school_id')) {
                continue;
            }
            $this->tryDropForeign($table, 'unified_school_id');
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('unified_school_id');
            });
        }
    }

    private function dropLegacyRegistryTables(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('typ_fld_evacuation_centers');
        Schema::dropIfExists('incident_schools');
        Schema::dropIfExists('cmpr_schl_sfty_schools');
        Schema::dropIfExists('firesafety_school_information');

        Schema::enableForeignKeyConstraints();
    }

    private function addFinalForeignKeys(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        if (Schema::hasColumn('users', 'school_id')) {
            try {
                Schema::table('users', function (Blueprint $t) {
                    $t->foreign('school_id')->references('id')->on('schools')->nullOnDelete();
                });
            } catch (\Throwable $e) {
            }
        }
        if (Schema::hasColumn('users', 'incident_school_id')) {
            try {
                Schema::table('users', function (Blueprint $t) {
                    $t->foreign('incident_school_id')->references('id')->on('schools')->nullOnDelete();
                });
            } catch (\Throwable $e) {
            }
        }
        if (Schema::hasColumn('users', 'typhoon_school_id')) {
            try {
                Schema::table('users', function (Blueprint $t) {
                    $t->foreign('typhoon_school_id')->references('id')->on('schools')->nullOnDelete();
                });
            } catch (\Throwable $e) {
            }
        }
    }

    private function tryDropForeign(string $table, string $column): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return;
        }
        try {
            Schema::table($table, function (Blueprint $blueprint) use ($column) {
                $blueprint->dropForeign([$column]);
            });
        } catch (\Throwable $e) {
            // FK name may differ or already removed
        }
    }

    public function down(): void
    {
        // Irreversible — restore from database backup if required.
    }
};
