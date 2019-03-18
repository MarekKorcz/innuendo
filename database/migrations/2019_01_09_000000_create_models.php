<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('phone_number');
            $table->string('street');
            $table->string('street_number');
            $table->string('house_number')->nullable();
            $table->string('city');
            $table->boolean('isPublic')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('boss_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
        });
        
        Schema::create('user_property', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('property_id')->unsigned();
        });
        
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('minutes');
            $table->text('description');
            $table->decimal('price');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('category_id')->unsigned()->index()->foreign()->references("id")->on("categories");
        });
        
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('isActive')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('property_id')->unsigned()->index()->foreign()->references("id")->on("properties");
            $table->integer('employee_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
        });
        
        Schema::create('years', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('calendar_id')->unsigned()->index()->foreign()->references("id")->on("calendars");
        });
        
        Schema::create('months', function (Blueprint $table) {
            $table->increments('id');
            $table->string('month');
            $table->integer('month_number');
            $table->integer('days_in_month');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('year_id')->unsigned()->index()->foreign()->references("id")->on("years");
        });
        
        Schema::create('days', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('day_number');
            $table->integer('number_in_week');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('month_id')->unsigned()->index()->foreign()->references("id")->on("months");
        });
        
        Schema::create('graphics', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('total_time');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('day_id')->unsigned()->index()->foreign()->references("id")->on("days");
        });
        
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('minutes');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('graphic_id')->unsigned()->index()->foreign()->references("id")->on("graphics");
            $table->integer('day_id')->unsigned()->index()->foreign()->references("id")->on("days");
            $table->integer('item_id')->unsigned()->index()->foreign()->references("id")->on("items");
            $table->integer('user_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
            $table->integer('temp_user_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("temp_users");
            $table->integer('purchase_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("purchases");
        });
        
        Schema::create('temp_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->integer('phone_number');
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->decimal('old_price');
            $table->decimal('new_price');
            $table->integer('quantity');
            $table->integer('duration');
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('property_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id')->unsigned();
            $table->integer('subscription_id')->unsigned();
        });
        
        Schema::create('item_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('subscription_id')->unsigned();
        });
        
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('subscription_id')->unsigned()->index()->foreign()->references("id")->on("subscriptions");
            $table->integer('user_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
        });
        
        Schema::create('intervals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('available_units');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->integer('purchase_id')->unsigned()->index()->foreign()->references("id")->on("purchases");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
        Schema::dropIfExists('user_property');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('items');
        Schema::dropIfExists('calendars');
        Schema::dropIfExists('years');
        Schema::dropIfExists('months');
        Schema::dropIfExists('days');
        Schema::dropIfExists('graphics');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('temp_users');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('property_subscription');
        Schema::dropIfExists('item_subscription');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('intervals');
    }
}
