<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpecificationToEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('specification')->nullable();
            $table->string('code')->nullable()->change();
            $table->date('date_of_birth')->nullable()->change();
            $table->date('date_of_join')->nullable()->change();
            $table->string('qualification')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('specification');
            $table->string('code')->nullable(false)->change();
            $table->date('date_of_birth')->nullable(false)->change();
            $table->date('date_of_join')->nullable(false)->change();
            $table->string('qualification')->nullable(false)->change();
        });
    }
}
