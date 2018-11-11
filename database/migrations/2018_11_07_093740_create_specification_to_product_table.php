<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecificationToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specification_to_product', function(Blueprint $table){
            $table->increments('id');
            $table->integer('product_id')->unabled();
            $table->integer('specific_id')->unabled();
            $table->text('value')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'specific_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('specification_to_product');
    }
}
