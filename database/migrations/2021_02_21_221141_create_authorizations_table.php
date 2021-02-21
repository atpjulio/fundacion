<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('new_authorizations', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('merchant_id');
      $table->unsignedBigInteger('eps_id');
      $table->unsignedBigInteger('patient_id');
      $table->string('code', 50)->index();
      $table->date('date');
      $table->string('location')->nullable();
      $table->string('diagnosis')->nullable();
      $table->timestamps();

      $table->foreign('merchant_id')->references('id')->on('merchants')
        ->onDelete('cascade');

      $table->foreign('eps_id')->references('id')->on('new_eps')
        ->onDelete('cascade');

      $table->foreign('patient_id')->references('id')->on('new_patients')
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
    Schema::dropIfExists('new_authorizations');
  }
}
