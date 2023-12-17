<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  protected $primaryKey = "id";
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('submission', function (Blueprint $table) {
      $table->id('id');
      $table->longText('description');
      $table->tinyInteger('score')->nullable();
      $table->string('submission');
      $table->tinyInteger('is_grade')->nullable();
      $table->unsignedBigInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users');
      $table->unsignedBigInteger('assignment_id');
      $table->foreign('assignment_id')->references('id')->on('assignment');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('submission');
  }
};
