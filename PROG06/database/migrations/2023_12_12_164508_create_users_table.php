<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('username');
      $table->string('password')->default(bcrypt('123456a@A'));
      $table->string('fullname');
      $table->string('role')->default('student');
      $table->string('phone')->nullable();
      $table->string('email')->nullable();
      $table->string('website')->nullable();
      $table->longText('description')->nullable();
      $table->string('avatar')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
