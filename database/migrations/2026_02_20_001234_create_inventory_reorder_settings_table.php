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
        Schema::create('inventory_reorder_settings', function (Blueprint $table) {
            $table->id();
            // source of the item
            $table->string('source'); // fixed_asset | it_leasing

            // grouping key
            $table->string('item_key');

            // display info
            $table->string('category')->nullable();
            $table->string('name')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();

            // reorder config
            $table->unsignedInteger('reorder_level')->default(0);
            $table->unsignedInteger('reorder_time_days')->nullable();
            $table->boolean('discontinued')->default(false);

            $table->timestamps();

            $table->unique(['source', 'item_key']);
            $table->index(['source', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_reorder_settings');
    }
};
