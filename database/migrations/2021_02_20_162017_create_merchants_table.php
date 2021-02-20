<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('merchants', function (Blueprint $table) {
      $table->id();
      $table->string('dni_type', 2);
      $table->string('dni', 15);
      $table->string('name')->index();
      $table->string('alias', 100)->nullable();
      $table->string('phone1')->nullable();
      $table->string('phone2')->nullable();
      $table->string('image')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('merchants');
  }
}
