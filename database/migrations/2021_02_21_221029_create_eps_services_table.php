<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpsServicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('new_eps_services', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('eps_id');
      $table->string('code', 10)->nullable();
      $table->string('name')->index();
      $table->unsignedDecimal('amount', 15, 2);
      $table->enum('status', config('enum.status'))->index();
      $table->timestamps();

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
    Schema::dropIfExists('new_eps_services');
  }
}
