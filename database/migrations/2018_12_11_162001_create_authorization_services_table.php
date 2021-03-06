<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorization_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('authorization_id');
            $table->unsignedInteger('eps_service_id');
            $table->decimal('price', 10, 2)->default("0.00");
            $table->smallInteger('days')->default(0);            
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
        Schema::dropIfExists('authorization_services');
    }
}
