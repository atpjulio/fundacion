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
            $table->text('authorization_code');
            $table->unsignedInteger('eps_id');
            $table->unsignedDecimal('total', 15, 2);
            $table->unsignedDecimal('payment', 15, 2)->default('0.00');
            $table->boolean('status')->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('number', 'idx_number');
            $table->index('company_id', 'idx_company_id');
            $table->index('eps_id', 'idx_eps_id');
            $table->index('total', 'idx_total');
            $table->index('status', 'idx_status');
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
