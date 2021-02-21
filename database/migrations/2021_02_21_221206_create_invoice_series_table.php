<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSeriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('invoice_series', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('merchant_id');
      $table->string('resolution', 50)->nullable();
      $table->date('resolution_date')->nullable();
      $table->string('name')->nullable();
      $table->string('prefix', 10)->nullable();
      $table->unsignedBigInteger('number');
      $table->unsignedBigInteger('number_from')->nullable();
      $table->unsignedBigInteger('number_to')->nullable();
      $table->enum('status', config('enum.status'));
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
    Schema::dropIfExists('invoice_series');
  }
}
