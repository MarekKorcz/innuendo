<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModelMaking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->text('description');
            $table->string('email');
            $table->string('phone_number');
            $table->string('street');
            $table->string('street_number')->nullable();
            $table->string('house_number')->nullable();
            $table->string('city');
            $table->string('postcode');
            $table->string('country');
            $table->integer('user_id')->unsigned()->index()->foreign()->references("id")->on("users");
        });
        
        Schema::create('auctions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('status');
            $table->decimal('price');
            $table->integer('order_id')->unsigned()->index()->foreign()->references("id")->on("orders");
            $table->integer('vendor_id')->unsigned()->index()->foreign()->references("id")->on("vendors");
        });
        
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('status');
            $table->time('execution_time');
            $table->integer('order');
            $table->integer('user_id')->unsigned()->index()->foreign()->references("id")->on("users");
            $table->integer('vendor_id')->unsigned()->index()->foreign()->references("id")->on("vendors");
        });
        
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('quantity');
            $table->integer('order_id')->unsigned()->index()->foreign()->references("id")->on("orders");
            $table->integer('item_id')->unsigned()->index()->foreign()->references("id")->on("items");
        });
        
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('vendor_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("vendors");
        });
        
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->text('description');
            $table->decimal('price');
            $table->time('manufacture_time');
            $table->string('image')->nullable();
            $table->integer('category_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("categories");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('auctions');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('items');
    }
}
