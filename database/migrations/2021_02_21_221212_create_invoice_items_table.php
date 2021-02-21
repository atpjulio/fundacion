<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('invoice_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('invoice_id');
      $table->unsignedBigInteger('authorization_id');
      $table->string('authorization_code', 50);
      $table->string('service_code', 10);
      $table->string('service_name');
      $table->unsignedTinyInteger('quantity');
      $table->unsignedDecimal('amount', 15, 2);
      $table->timestamps();

      $table->foreign('invoice_id')->references('id')->on('new_invoices')
        ->onDelete('cascade');

      $table->foreign('authorization_id')->references('id')->on('new_authorizations')
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
    Schema::dropIfExists('invoice_items');
  }
}
