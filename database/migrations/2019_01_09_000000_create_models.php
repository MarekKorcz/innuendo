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
            $table->text('email');
            $table->integer('phone_number')->nullable();
            $table->string('street');
            $table->string('street_number')->nullable();
            $table->string('house_number')->nullable();
            $table->string('post_code')->nullable();
            $table->string('city');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('boss_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
        });
        
        Schema::create('invoice_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('website');
            $table->text('email');
            $table->text('nip');
            $table->text('bank_name');
            $table->text('account_number');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('property_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("properties");
            $table->integer('owner_id')->unsigned()->index()->foreign()->references("id")->on("users");
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
            $table->integer('interval_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("intervals");
            $table->integer('purchase_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("purchases");
        });
        
        Schema::create('temp_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->integer('phone_number');
            $table->string('register_code')->nullable();
            $table->boolean('isBoss')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('temp_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('email');
            $table->integer('phone_number')->nullable();
            $table->string('street');
            $table->string('street_number')->nullable();
            $table->string('house_number')->nullable();
            $table->string('post_code')->nullable();
            $table->string('city');
            $table->integer('temp_user_id')->unsigned()->index()->foreign()->references("id")->on("temp_users");
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
        
        Schema::create('temp_property_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('temp_property_id')->unsigned();
            $table->integer('subscription_id')->unsigned();
        });
        
        Schema::create('substarts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('user_id')->nullable();
            $table->integer('boss_id')->nullable();
            $table->boolean('isActive')->default(0);
            $table->integer('property_id');
            $table->integer('subscription_id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('purchase_id')->unsigned()->index()->foreign()->references("id")->on("purchases");
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
            $table->integer('substart_id')->nullable();
            $table->integer('subscription_id')->unsigned()->index()->foreign()->references("id")->on("subscriptions");
            $table->integer('chosen_property_id')->unsigned()->index()->foreign()->references("id")->on("chosen_properties");
        });
        
        Schema::create('intervals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('available_units')->nullable();
            $table->integer('used_units')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->integer('interval_id')->nullable();
            $table->integer('substart_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("substarts");
            $table->integer('purchase_id')->unsigned()->index()->foreign()->references("id")->on("purchases");
        });
        
        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('boss_id')->unsigned()->index()->foreign()->references("id")->on("users");
        });
        
        Schema::create('chosen_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('code_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("codes");
            $table->integer('user_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
            $table->integer('property_id')->unsigned()->index()->foreign()->references("id")->on("properties");
        });
        
        Schema::create('chosen_property_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chosen_property_id')->unsigned()->index()->foreign()->references("id")->on("chosen_properties");
            $table->integer('subscription_id')->unsigned()->index()->foreign()->references("id")->on("subscriptions");
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
        Schema::dropIfExists('invoice_data');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('items');
        Schema::dropIfExists('calendars');
        Schema::dropIfExists('years');
        Schema::dropIfExists('months');
        Schema::dropIfExists('days');
        Schema::dropIfExists('graphics');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('temp_users');
        Schema::dropIfExists('temp_properties');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('property_subscription');
        Schema::dropIfExists('temp_property_subscription');
        Schema::dropIfExists('substarts');
        Schema::dropIfExists('item_subscription');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('intervals');
        Schema::dropIfExists('codes');
        Schema::dropIfExists('chosen_properties');
        Schema::dropIfExists('chosen_property_subscription');
    }
}
