<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixEgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('egresses', function (Blueprint $table) {

            $table->dropColumn('bank_id');
            $table->dropColumn('payment_type');
            $table->dropColumn('notes');

            $table->unsignedInteger('entity_id');
            $table->text('concept');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('egresses', function (Blueprint $table) {
            //
        });
    }
}
