<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 10);
            $table->string('name');
            $table->string('nit', 20);
            $table->decimal('daily_price', 10, 2);
            $table->string('alias', 20)->nullable();
            $table->string('contract', 20)->nullable();
            $table->string('policy', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('code', 'idx_code');
            $table->index('nit', 'idx_nit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eps');
    }
}
