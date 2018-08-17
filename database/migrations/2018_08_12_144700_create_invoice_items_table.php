<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('eps_service_id');
            $table->unsignedInteger('authorization_id');
            $table->unsignedTinyInteger('quantity');
            $table->decimal('daily_price', 10, 2);
            $table->decimal('total_item', 10, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->index('invoice_id', 'idx_invoice_id');
            $table->index('eps_service_id', 'idx_eps_service_id');
            $table->index('authorization_id', 'idx_authorization_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
