<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEgressPucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('egress_pucs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('egress_id');
            $table->text('description')->nullable();
            $table->string('code', 15);
            $table->boolean('type')->default(0); // 1: Credit, 0: Debit
            $table->unsignedDecimal('amount', 15,2);
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
        Schema::dropIfExists('egress_pucs');
    }
}
