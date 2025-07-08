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
            $table->integer('client_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->timestamps();

            $table->index(['client_id', 'from', 'to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};