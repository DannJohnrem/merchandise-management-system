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
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->unique()->nullable();
            $table->string('category')->nullable();
            $table->string('asset_name')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->string('assigned_employee')->nullable();
            $table->string('department')->nullable();
            $table->string('asset_class')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'issued', 'repair', 'disposed', 'lost'])->default('available');
            $table->enum('condition', ['new', 'good', 'fair', 'poor'])->nullable();
            $table->date('warranty_expiration')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('purchase_order_no')->nullable();
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
