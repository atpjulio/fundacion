<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewCitiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('new_cities', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('state_id');
      $table->string('code', 3);
      $table->string('name', 100);
      $table->timestamps();

      $table->foreign('state_id')->references('id')->on('states')
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
    Schema::dropIfExists('new_cities');
  }
}
