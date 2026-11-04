<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('typ_fld_families', function (Blueprint $table) {
            if (Schema::hasColumn('typ_fld_families', 'baranggay') && !Schema::hasColumn('typ_fld_families', 'barangay')) {
                $table->renameColumn('baranggay', 'barangay');
            }
            if (Schema::hasColumn('typ_fld_families', 'street')) {
                $table->string('street')->nullable()->change();
            }
            if (Schema::hasColumn('typ_fld_families', 'barangay')) {
                $table->string('barangay')->nullable()->change();
            }
            if (Schema::hasColumn('typ_fld_families', 'city')) {
                $table->string('city')->nullable()->change();
            }
            if (Schema::hasColumn('typ_fld_families', 'contact_number')) {
                $table->string('contact_number', 20)->nullable()->change();
            }
            if (Schema::hasColumn('typ_fld_families', 'contactnum')) {
                $table->integer('contactnum')->nullable()->change();
            }
            if (Schema::hasColumn('typ_fld_families', 'city_municipality')) {
                $table->string('city_municipality')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typ_fld_families', function (Blueprint $table) {
            //
        });
    }
};
