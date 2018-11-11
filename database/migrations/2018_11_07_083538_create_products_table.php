<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //название, артикул, единица измерения, цена, на складе
        Schema::create('products', function(Blueprint $table){
            $table->increments('id');
            $table->string('title')->unabled();
            $table->text('desc')->nullable();
            $table->text('images')->nullable();
            $table->string('sku')->unabled()->comment('Артикул');
            $table->string('unit')->nullable()->comment('Ед.Изм.');
            $table->integer('price')->nullable();
            $table->integer('instock')->default(0)->comment('На складе');
            $table->string('hash', 32)->unique();
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
        Schema::drop('products');
    }
}
