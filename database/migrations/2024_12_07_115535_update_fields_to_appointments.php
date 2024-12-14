<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsToAppointments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('bp_detail')->nullable();
            $table->string('medical_history')->nullable();
            $table->string('family_medical_history')->nullable();
            $table->string('current_condition')->nullable();
            $table->string('observation_details')->nullable();
            $table->string('investigation_details')->nullable();
            $table->string('treatment_plan')->nullable();
            $table->string('procedures')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
}
