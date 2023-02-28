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
    Schema::create('employees_positions', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('employee_id')->nullable();
      $table->unsignedBigInteger('position_id')->nullable();
      $table->timestamps();

      // Foreign Keys
      $table->foreign('employee_id')
        ->on('employees')
        ->references('id')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      $table->foreign('position_id')
        ->on('positions')
        ->references('id')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('employee_position');
  }
};
