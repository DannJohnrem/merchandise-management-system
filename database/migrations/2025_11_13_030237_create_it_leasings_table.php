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
            $table->string('serial_number')->unique();
            $table->string('brand');
            $table->string('model');
            $table->decimal('cost', 12, 2);
            $table->string('assigned_to');
            $table->string('class');
            $table->enum('status', ['available', 'in_use', 'repair', 'returned', 'lost'])
                ->nullable(false);
            $table->text('remarks');
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
