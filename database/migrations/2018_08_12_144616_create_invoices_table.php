<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('number');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('eps_id');
            $table->unsignedInteger('patient_id');
            $table->unsignedDecimal('total', 15,2);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('number', 'idx_number');
            $table->index('company_id', 'idx_company_id');
            $table->index('eps_id', 'idx_eps_id');
            $table->index('patient_id', 'idx_patient_id');
            $table->index('total', 'idx_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
