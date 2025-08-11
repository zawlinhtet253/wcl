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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->string('employee_code')->unique(); // ဥပမာ WCL-001
            $table->string('position')->nullable(); // ရာထူး (ဥပမာ Manager, Developer)
            $table->text('address')->nullable(); // လိပ်စာ
            $table->string('nrc')->unique()->nullable(); // NRC နံပါတ်
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
