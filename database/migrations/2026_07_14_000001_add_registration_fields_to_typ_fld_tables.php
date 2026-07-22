<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new fields to typ_fld_families
        Schema::table('typ_fld_families', function (Blueprint $table) {
            // Address fields
            $table->string('street')->nullable()->after('head_family_name');
            $table->string('barangay')->nullable()->after('street');
            $table->string('city')->nullable()->after('barangay');
            // Contact
            $table->string('contact_number', 20)->nullable()->after('city');
            // Other/special needs vulnerability
            $table->boolean('has_other_needs')->default(false)->after('has_child_under5');
            $table->string('other_needs_details')->nullable()->after('has_other_needs');
            // Personal belongings & pets as JSON
            $table->json('personal_belongings')->nullable()->after('other_needs_details');
            $table->json('personal_pets')->nullable()->after('personal_belongings');
            // Registered by (user who registered this family)
            $table->unsignedBigInteger('registered_by')->nullable()->after('personal_pets');
            $table->foreign('registered_by')->references('id')->on('users')->nullOnDelete();
        });

        // Add family_role to family members
        Schema::table('typ_fld_family_members', function (Blueprint $table) {
            $table->string('family_role')->nullable()->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('typ_fld_families', function (Blueprint $table) {
            $table->dropForeign(['registered_by']);
            $table->dropColumn([
                'street', 'barangay', 'city', 'contact_number',
                'has_other_needs', 'other_needs_details',
                'personal_belongings', 'personal_pets', 'registered_by'
            ]);
        });

        Schema::table('typ_fld_family_members', function (Blueprint $table) {
            $table->dropColumn('family_role');
        });
    }
};
