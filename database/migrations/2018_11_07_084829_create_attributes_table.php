<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function(Blueprint $table){
            $table->increments('id');
            $table->integer('product_id')->unabled();
            $table->string('title')->unabled();
            $table->integer('price')->nullable();
            $table->string('instock')->default(0);
            $table->timestamps();
            $table->unique(['product_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('attributes');
    }
}
