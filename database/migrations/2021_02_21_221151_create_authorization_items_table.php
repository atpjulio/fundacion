<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizationItemsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('authorization_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('authorization_id');
      $table->unsignedTinyInteger('quantity');
      $table->morphs('authorizable');

      $table->timestamps();

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
    Schema::dropIfExists('authorization_items');
  }
}
