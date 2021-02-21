<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('new_patients', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('merchant_id');
      $table->unsignedBigInteger('eps_id');
      $table->string('dni_type', 2);
      $table->string('dni', 15)->index();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('phone', 20)->nullable();
      $table->timestamps();

      $table->index(['eps_id', 'dni_type', 'dni']);

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
    Schema::dropIfExists('new_patients');
  }
}
