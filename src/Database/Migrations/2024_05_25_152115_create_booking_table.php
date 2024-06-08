<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('qty')->default(0)->nullable();
            $table->date('day');
            $table->time('from', $precision = 0);
            $table->time('to', $precision = 0);
            $table->Integer('city_id')->unsigned()->index();

            $table->integer('order_item_id')->unsigned()->nullable();

            $table->integer('booking_product_event_ticket_id')->unsigned()->nullable();

            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
