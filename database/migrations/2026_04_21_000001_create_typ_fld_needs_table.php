<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typ_fld_needs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('family_id');
            $table->unsignedBigInteger('family_member_id')->nullable();
            $table->string('need_name');
            $table->unsignedInteger('quantity')->default(1);
            $table->boolean('is_custom')->default(false);
            $table->timestamps();

            $table->foreign('family_id')
                ->references('id')
                ->on('typ_fld_families')
                ->onDelete('cascade');

            $table->foreign('family_member_id')
                ->references('id')
                ->on('typ_fld_family_members')
                ->nullOnDelete();
        });

        if (Schema::hasTable('typ_fld_families') && Schema::hasColumn('typ_fld_families', 'collective_needs')) {
            $families = DB::table('typ_fld_families')->select('id', 'collective_needs')->get();

            foreach ($families as $family) {
                $rawNeeds = trim((string) ($family->collective_needs ?? ''));
                if ($rawNeeds === '') {
                    continue;
                }

                $items = preg_split('/[\r\n,;]+/', $rawNeeds) ?: [];
                foreach ($items as $item) {
                    $needName = trim($item);
                    if ($needName === '') {
                        continue;
                    }

                    DB::table('typ_fld_needs')->insert([
                        'family_id' => $family->id,
                        'family_member_id' => null,
                        'need_name' => $needName,
                        'quantity' => 1,
                        'is_custom' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        if (Schema::hasTable('typ_fld_family_members') && Schema::hasColumn('typ_fld_family_members', 'needs')) {
            $members = DB::table('typ_fld_family_members')->select('id', 'family_id', 'needs')->get();

            foreach ($members as $member) {
                $rawNeeds = trim((string) ($member->needs ?? ''));
                if ($rawNeeds === '') {
                    continue;
                }

                $items = preg_split('/[\r\n,;]+/', $rawNeeds) ?: [];
                foreach ($items as $item) {
                    $needName = trim($item);
                    if ($needName === '') {
                        continue;
                    }

                    DB::table('typ_fld_needs')->insert([
                        'family_id' => $member->family_id,
                        'family_member_id' => $member->id,
                        'need_name' => $needName,
                        'quantity' => 1,
                        'is_custom' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        Schema::table('typ_fld_families', function (Blueprint $table) {
            if (Schema::hasColumn('typ_fld_families', 'collective_needs')) {
                $table->dropColumn('collective_needs');
            }
        });

        Schema::table('typ_fld_family_members', function (Blueprint $table) {
            if (Schema::hasColumn('typ_fld_family_members', 'needs')) {
                $table->dropColumn('needs');
            }
        });
    }

    public function down(): void
    {
        Schema::table('typ_fld_families', function (Blueprint $table) {
            if (!Schema::hasColumn('typ_fld_families', 'collective_needs')) {
                $table->text('collective_needs')->nullable()->after('head_family_name');
            }
        });

        Schema::table('typ_fld_family_members', function (Blueprint $table) {
            if (!Schema::hasColumn('typ_fld_family_members', 'needs')) {
                $table->string('needs')->nullable()->after('gender');
            }
        });

        if (Schema::hasTable('typ_fld_needs')) {
            $familyNeeds = DB::table('typ_fld_needs')
                ->whereNull('family_member_id')
                ->select('family_id', 'need_name', 'quantity')
                ->get()
                ->groupBy('family_id');

            foreach ($familyNeeds as $familyId => $rows) {
                $summary = $rows->map(function ($row) {
                    return $row->quantity > 1 ? $row->need_name . ' x' . $row->quantity : $row->need_name;
                })->implode(', ');

                DB::table('typ_fld_families')
                    ->where('id', $familyId)
                    ->update(['collective_needs' => $summary]);
            }

            $memberNeeds = DB::table('typ_fld_needs')
                ->whereNotNull('family_member_id')
                ->select('family_member_id', 'need_name', 'quantity')
                ->get()
                ->groupBy('family_member_id');

            foreach ($memberNeeds as $memberId => $rows) {
                $summary = $rows->map(function ($row) {
                    return $row->quantity > 1 ? $row->need_name . ' x' . $row->quantity : $row->need_name;
                })->implode(', ');

                DB::table('typ_fld_family_members')
                    ->where('id', $memberId)
                    ->update(['needs' => $summary]);
            }
        }

        Schema::dropIfExists('typ_fld_needs');
    }
};
