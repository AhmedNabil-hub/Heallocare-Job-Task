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
    Schema::create('positions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->unsignedBigInteger('department_id')->nullable();
      $table->timestamps();
      $table->unique(['name', 'department_id']);

      // Foreign Keys
      $table->foreign('department_id')
        ->on('departments')
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
    Schema::dropIfExists('positions');
  }
};
