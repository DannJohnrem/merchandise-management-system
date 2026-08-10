<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('it_leasing_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('it_leasing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pull_out_form_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('remarks')->nullable();
            $table->timestamp('changed_at');

            $table->timestamps();

            $table->index(['it_leasing_id', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_leasing_status_histories');
    }
};
