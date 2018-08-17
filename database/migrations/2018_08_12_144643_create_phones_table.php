<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('model_id');
            $table->boolean('model_type'); // 1 = Company, 2 = Eps, 3 = Patient
            $table->string('phone', 15)->nullable();
            $table->string('phone2', 15)->nullable();
            $table->timestamps();

            $table->index('model_id', 'idx_model_id');
            $table->index('model_type', 'idx_model_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phones');
    }
}
