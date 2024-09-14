<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberToPatients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         // Modify the first_name column to be nullable
         Schema::table('patients', function (Blueprint $table) {
            $table->string('number')->nullable();
            $table->string('package_end_date')->nullable();
            $table->string('abha_number')->nullable();
        });
    }


     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('package_end_date');
            $table->dropColumn('abha_number');
        });
    }


}
