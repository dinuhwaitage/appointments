<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDoctorIdForeignKeyInAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['doctor_id']);

            // Recreate without cascade
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('employees');
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
            $table->dropForeign(['doctor_id']);

            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
        
        });
    }
}
