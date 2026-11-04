<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add missing fields if not present
        Schema::table('typh_dswd_form', function (Blueprint $table) {
            if (!Schema::hasColumn('typh_dswd_form', 'addr_house')) {
                $table->string('addr_house', 255)->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'addr_street')) {
                $table->string('addr_street', 255)->nullable()->after('addr_house');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'addr_subd')) {
                $table->string('addr_subd', 255)->nullable()->after('addr_street');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'addr_barangay')) {
                $table->string('addr_barangay', 255)->nullable()->after('addr_subd');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'addr_city')) {
                $table->string('addr_city', 255)->nullable()->after('addr_barangay');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'addr_province')) {
                $table->string('addr_province', 255)->nullable()->after('addr_city');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'addr_zip')) {
                $table->string('addr_zip', 20)->nullable()->after('addr_province');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'is_4ps')) {
                $table->boolean('is_4ps')->default(false)->after('addr_zip');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'is_ip')) {
                $table->boolean('is_ip')->default(false)->after('is_4ps');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'ip_ethnicity')) {
                $table->string('ip_ethnicity', 150)->nullable()->after('is_ip');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'bank_ewallet')) {
                $table->string('bank_ewallet', 150)->nullable()->after('ip_ethnicity');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'account_name')) {
                $table->string('account_name', 150)->nullable()->after('bank_ewallet');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'account_type')) {
                $table->string('account_type', 100)->nullable()->after('account_name');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'account_number')) {
                $table->string('account_number', 100)->nullable()->after('account_type');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'house_ownership')) {
                $table->string('house_ownership', 50)->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'shelter_damage')) {
                $table->string('shelter_damage', 50)->nullable()->after('house_ownership');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'sig_barangay_captain')) {
                $table->string('sig_barangay_captain', 150)->nullable()->after('shelter_damage');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'sig_lswdo')) {
                $table->string('sig_lswdo', 150)->nullable()->after('sig_barangay_captain');
            }
            if (!Schema::hasColumn('typh_dswd_form', 'family_members')) {
                $table->longText('family_members')->nullable()->after('sig_lswdo');
            }
        });

        // 2. Make strict non-nullable columns nullable so partial inputs don't fail MySQL queries
        try {
            DB::statement("ALTER TABLE `typh_dswd_form` 
                MODIFY `region` varchar(100) NULL DEFAULT '',
                MODIFY `province` varchar(100) NULL DEFAULT '',
                MODIFY `city_municipality` varchar(100) NULL DEFAULT '',
                MODIFY `barangay` varchar(100) NULL DEFAULT '',
                MODIFY `last_name` varchar(100) NULL DEFAULT '',
                MODIFY `first_name` varchar(100) NULL DEFAULT '',
                MODIFY `birthdate` date NULL DEFAULT NULL,
                MODIFY `birthplace` varchar(150) NULL DEFAULT '',
                MODIFY `mothers_maiden_name` varchar(200) NULL DEFAULT '',
                MODIFY `religion` varchar(100) NULL DEFAULT '',
                MODIFY `contact_number` varchar(30) NULL DEFAULT '',
                MODIFY `occupation` varchar(100) NULL DEFAULT '',
                MODIFY `id_card_presented` varchar(100) NULL DEFAULT '',
                MODIFY `id_card_number` varchar(100) NULL DEFAULT '',
                MODIFY `phone_number` varchar(30) NULL DEFAULT ''
            ");
        } catch (\Throwable $e) {
            // Ignore if already modified or running in incompatible engine
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typh_dswd_form', function (Blueprint $table) {
            $cols = [
                'addr_house', 'addr_street', 'addr_subd', 'addr_barangay', 'addr_city',
                'addr_province', 'addr_zip', 'is_4ps', 'is_ip', 'ip_ethnicity',
                'bank_ewallet', 'account_name', 'account_type', 'account_number',
                'house_ownership', 'shelter_damage', 'sig_barangay_captain', 'sig_lswdo',
                'family_members'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('typh_dswd_form', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
