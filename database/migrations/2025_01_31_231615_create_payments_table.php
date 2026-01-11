<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->string('status')->default('PENDING'); // PENDING, COMPLETED, FAILED
            $table->string('transaction_id')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('description')->nullable(); 
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade')->nullable();;
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->json('response')->nullable(); // Add JSON column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
