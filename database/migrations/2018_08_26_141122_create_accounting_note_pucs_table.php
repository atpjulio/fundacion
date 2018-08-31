<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountingNotePucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_note_pucs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('accounting_note_id');
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
        Schema::dropIfExists('accounting_note_pucs');
    }
}
