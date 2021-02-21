<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientDataTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('patient_data', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('patient_id');
      $table->unsignedBigInteger('city_id');
      $table->unsignedBigInteger('state_id');
      $table->enum('gender', config('enum.gender.enum'))->nullable();
      $table->enum('type', config('enum.patient.type.enum'))->nullable();
      $table->date('birth_date')->nullable();
      $table->enum('zone', config('enum.patient.zone.enum'))->nullable();
      $table->timestamps();

      $table->foreign('patient_id')->references('id')->on('new_patients')
        ->onDelete('cascade');

      $table->foreign('city_id')->references('id')->on('new_cities')
        ->onDelete('cascade');

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
    Schema::dropIfExists('patient_data');
  }
}
