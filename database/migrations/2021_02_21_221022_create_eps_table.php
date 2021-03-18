<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('new_eps', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('merchant_id');
      $table->string('code', 10)->nullable();
      $table->string('color', 7);
      $table->string('name')->index();
      $table->string('nit', 15);
      $table->string('alias')->nullable();
      $table->string('contract_number')->nullable();
      $table->string('policy')->nullable();
      $table->string('phone1', 20)->nullable();
      $table->string('phone2', 20)->nullable();
      $table->timestamps();

      $table->foreign('merchant_id')->references('id')->on('merchants')
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
    Schema::dropIfExists('new_eps');
  }
}
