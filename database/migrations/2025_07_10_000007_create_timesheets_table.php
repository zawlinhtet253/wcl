<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->text('description');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->boolean('status')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();

            $table->index(['client_id', 'from', 'to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};