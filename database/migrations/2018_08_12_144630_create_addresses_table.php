<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('model_id');
            $table->boolean('model_type'); // 1 = Company, 2 = Eps, 3 = Patient
            $table->string('address', 50)->nullable();
            $table->string('address2', 50)->nullable();
            $table->string('zip', 5)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('country', 2)->default('CO');
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
        Schema::dropIfExists('addresses');
    }
}
