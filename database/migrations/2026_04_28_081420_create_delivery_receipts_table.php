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
        Schema::create('delivery_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('dr_number', 20)->unique();
            $table->json('it_leasing_ids');        // store the selected IDs
            $table->string('assigned_company')->nullable();
            $table->string('assigned_employee')->nullable();
            $table->string('generated_by')->nullable(); // auth user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_receipts');
    }
};
