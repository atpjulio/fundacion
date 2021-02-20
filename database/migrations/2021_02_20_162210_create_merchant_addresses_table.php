<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantAddressesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('merchant_addresses', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('parent_id');
      $table->unsignedBigInteger('city_id');
      $table->unsignedBigInteger('state_id');
      $table->string('line1');
      $table->string('line2')->nullable();
      $table->string('country', 2)->nullable();
      $table->timestamps();

      $table->foreign('parent_id')->references('id')->on('merchants')
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
    Schema::dropIfExists('merchant_addresses');
  }
}
