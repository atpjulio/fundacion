<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorizations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('eps_id');
            $table->unsignedInteger('eps_service_id');
            $table->unsignedInteger('patient_id');
            $table->string('code');
            $table->date('date_from');
            $table->date('date_to');
            $table->decimal('total', 10, 2)->nullable();
            $table->boolean('companion')->default(0);
            $table->string('companion_dni', 100)->nullable();
            $table->text('guardianship')->nullable();
            $table->text('guardianship_file')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('eps_id', 'idx_eps_id');
            $table->index('eps_service_id', 'idx_eps_service_id');
            $table->index('patient_id', 'idx_patient_id');
            $table->index('date_from', 'idx_date_from');
            $table->index('date_to', 'idx_date_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authorizations');
    }
}
