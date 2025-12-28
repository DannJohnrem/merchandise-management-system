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
        Schema::create('it_leasings', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('item_name');
            $table->string('serial_number')->unique();
            $table->string('brand');
            $table->string('model');
            $table->decimal('purchase_cost', 12, 2);
            $table->string('supplier')->nullable();
            $table->string('purchase_order_no')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiration')->nullable();
            $table->string('assigned_company');              // Company (BTSMC, etc.)
            $table->string('assigned_employee')->nullable(); // Optional end-user
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'deployed', 'in_repair', 'returned', 'lost'])->default('available');
            $table->enum('condition', ['new', 'good', 'fair', 'poor'])->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it_leasings');
    }
};
