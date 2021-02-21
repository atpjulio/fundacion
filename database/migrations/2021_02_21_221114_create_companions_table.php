<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('companions', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('merchant_id');
      $table->unsignedBigInteger('eps_id');
      $table->string('dni_type', 2);
      $table->string('dni', 15)->index();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('phone', 20)->nullable();
      $table->timestamps();

      $table->foreign('merchant_id')->references('id')->on('merchants')
        ->onDelete('cascade');

      $table->foreign('eps_id')->references('id')->on('new_eps')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('companions');
  }
}
