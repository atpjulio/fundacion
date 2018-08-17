<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dni_type', 20)->nullable();
            $table->string('dni', 20)->nullable();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email')->nullable();
            $table->string('birth_date', 10);
            $table->boolean('sisben_level')->default(1);
            $table->string('sisben_number', 50)->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('dni_type', 'idx_dni_type');
            $table->index('dni', 'idx_dni');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
