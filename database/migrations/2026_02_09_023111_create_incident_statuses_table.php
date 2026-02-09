<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color_class');
            $table->string('short_code');
            $table->boolean('is_compliance')->default(true);
            $table->timestamps();
        });

        // Seed default statuses
        DB::table('incident_statuses')->insert([
            ['name' => 'Holiday', 'color_class' => 'status-holiday', 'short_code' => 'H', 'is_compliance' => true],
            ['name' => 'Incident In School', 'color_class' => 'status-incident', 'short_code' => 'I', 'is_compliance' => false],
            ['name' => 'Classes/Work Suspended', 'color_class' => 'status-suspended', 'short_code' => 'S', 'is_compliance' => true],
            ['name' => 'No Class Suspension', 'color_class' => 'status-no-suspension', 'short_code' => 'N', 'is_compliance' => true],
            ['name' => 'Suspended F2F Classes', 'color_class' => 'status-f2f-suspended', 'short_code' => 'F', 'is_compliance' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_statuses');
    }
};