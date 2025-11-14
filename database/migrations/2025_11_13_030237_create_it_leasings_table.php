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
            $table->string('category'); // Laptop, Printer...
            $table->string('serial_number')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('assigned_to')->nullable(); // e.g., BTSMC
            $table->string('class')->nullable(); // e.g., employee name
            $table->string('status')->default('in_use'); // in_use, returned, repair
            $table->string('qr_code_path')->nullable();
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
