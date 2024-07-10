<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('card_owner');
            $table->string('gsm');
            $table->decimal('amount', 8, 2);
            $table->string('order_id');
            $table->string('order_description');
            $table->integer('installment');
            $table->decimal('total_amount', 8, 2);
            $table->string('security_type');
            $table->string('transaction_id');
            $table->string('ip_address');
            $table->string('currency_code');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
