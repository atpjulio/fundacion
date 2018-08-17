<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eps_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('eps_id');
            $table->string('code', 20);
            $table->string('name', 50);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('eps_id', 'idx_eps_id');
            $table->index('code', 'idx_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eps_services');
    }
}
