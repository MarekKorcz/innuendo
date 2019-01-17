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
            $table->string('phone_number');
            $table->string('street');
            $table->string('street_number');
            $table->string('house_number')->nullable();
            $table->string('city');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('user_id')->unsigned()->index()->foreign()->references("id")->on("users");
        });
        
        Schema::create('property_employee', function (Blueprint $table) {
            $table->integer('property_id');
            $table->integer('employee_id');
            $table->timestamps();
            $table->primary(['property_id', 'employee_id']);
        });
        
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('property_id')->unsigned()->index()->foreign()->references("id")->on("properties");
        });
        
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->decimal('price');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('category_id')->unsigned()->index()->foreign()->references("id")->on("categories");
        });
        
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');
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
            $table->timestamps();
            $table->softDeletes();
            $table->integer('employee_id')->unsigned()->index()->foreign()->references("id")->on("users");
            $table->integer('day_id')->unsigned()->index()->foreign()->references("id")->on("days");
            $table->integer('user_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
            $table->integer('item_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("items");
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
        Schema::dropIfExists('property_employee');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('items');
        Schema::dropIfExists('calendars');
        Schema::dropIfExists('years');
        Schema::dropIfExists('months');
        Schema::dropIfExists('days');
        Schema::dropIfExists('graphics');
        Schema::dropIfExists('appointments');
    }
}
