<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationCompanionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorization_companions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('authorization_id');
            $table->unsignedInteger('eps_service_id');
            $table->string('dni_type', 2)->nullable();
            $table->string('dni', 20)->nullable();
            $table->string('name', 50);
            $table->string('phone', 15)->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authorization_companions');
    }
}
