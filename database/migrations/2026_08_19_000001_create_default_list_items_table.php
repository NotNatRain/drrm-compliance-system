<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('default_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('section', 1);          // 'A' or 'B'
            $table->string('item_key');             // unique slug e.g. 'aluminum_stretcher'
            $table->string('item_name');            // display name (stored for reporting)
            $table->boolean('has_item')->default(false);
            $table->integer('quantity_owned')->default(0);
            $table->string('source')->nullable();            // 'deped' | 'partner' | null
            $table->string('source_detail')->nullable();     // GAA, NGO, LGU, etc.
            $table->date('date_checked')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

            // One record per item per school
            $table->unique(['school_id', 'section', 'item_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_list_items');
    }
};
