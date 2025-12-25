<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('flash_sales', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('banner_image')->nullable();
        $table->dateTime('start_time');
        $table->dateTime('end_time');
        $table->boolean('status')->default(true);
        $table->timestamps();
    });

    Schema::create('flash_sale_product', function (Blueprint $table) {
        $table->id();
        $table->foreignId('flash_sale_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->decimal('discount_price', 10, 2);
        $table->integer('stock_limit')->nullable();
        $table->timestamps();
    });
}
};
