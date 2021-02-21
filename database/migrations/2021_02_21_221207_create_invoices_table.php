<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('new_invoices', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('merchant_id');
      $table->unsignedBigInteger('eps_id');
      $table->unsignedBigInteger('serie_id');
      $table->unsignedBigInteger('patient_id')->nullable();
      $table->unsignedBigInteger('number');
      $table->string('code', 50);
      $table->date('date');
      $table->date('expiration_date')->nullable();
      $table->enum('status', config('enum.invoice.status'));
      $table->string('dni_type', 2);
      $table->string('dni', 15);
      $table->string('first_name');
      $table->string('last_name');
      $table->timestamps();

      $table->index(['code']);

      $table->foreign('merchant_id')->references('id')->on('merchants')
        ->onDelete('cascade');

      $table->foreign('eps_id')->references('id')->on('new_eps')
        ->onDelete('cascade');

      $table->foreign('serie_id')->references('id')->on('invoice_series')
        ->onDelete('cascade');

      $table->foreign('patient_id')->references('id')->on('new_patients')
        ->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('new_invoices');
  }
}
