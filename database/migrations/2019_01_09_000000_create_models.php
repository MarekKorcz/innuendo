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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
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
            $table->integer('category_id')->unsigned()->index()->foreign()->references("id")->on("categories");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('street');
            $table->string('street_number')->nullable();
            $table->string('house_number')->nullable();
            $table->string('city');
            $table->boolean('can_show')->default(0);
            $table->integer('boss_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
            $table->timestamps();
            $table->softDeletes();
        });
        
        
        Schema::create('years', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->integer('property_id')->unsigned()->index()->foreign()->references("id")->on("properties");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('months', function (Blueprint $table) {
            $table->increments('id');
            $table->string('month');
            $table->string('month_en');
            $table->integer('month_number');
            $table->integer('days_in_month');
            $table->integer('year_id')->unsigned()->index()->foreign()->references("id")->on("years");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('days', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('day_number');
            $table->integer('number_in_week');
            $table->integer('month_id')->unsigned()->index()->foreign()->references("id")->on("months");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('graphics', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('total_time');
            $table->integer('day_id')->unsigned()->index()->foreign()->references("id")->on("days");
            $table->integer('property_id')->unsigned()->index()->foreign()->references("id")->on("properties");
            $table->integer('employee_id')->unsigned()->index()->foreign()->references("id")->on("users");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('graphic_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('comment')->nullable();
            $table->integer('property_id')->unsigned()->index()->foreign()->references("id")->on("properties");
            $table->integer('day_id')->unsigned()->index()->foreign()->references("id")->on("days");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('graphic_request_employee', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('graphic_request_id')->unsigned()->index()->foreign()->references("id")->on("graphic_requests");
            $table->integer('employee_id')->unsigned()->index()->foreign()->references("id")->on("users");
        });
        
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('topic')->nullable();
            $table->string('email')->nullable();
            $table->text('text')->nullable();
            $table->integer('status')->nullable();
            $table->integer('user_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
            $table->integer('graphic_request_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("graphic_requests");
            $table->integer('promo_code_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("promo_codes");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('minutes');
            $table->integer('status')->default(0);
            $table->integer('graphic_id')->unsigned()->index()->foreign()->references("id")->on("graphics");
            $table->integer('day_id')->unsigned()->index()->foreign()->references("id")->on("days");
            $table->integer('item_id')->unsigned()->index()->foreign()->references("id")->on("items");
            $table->integer('user_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('temp_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->integer('phone_number')->nullable();
            $table->string('register_code')->nullable();
            $table->boolean('isBoss')->default(0);
            $table->boolean('isEmployee')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('temp_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('street');
            $table->string('street_number')->nullable();
            $table->string('house_number')->nullable();
            $table->string('city');
            $table->integer('temp_user_id')->unsigned()->index()->foreign()->references("id")->on("temp_users");
            $table->timestamps();
            $table->softDeletes();
        });        
        
        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->integer('boss_id')->unsigned()->index()->foreign()->references("id")->on("users");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('promos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('title_en');
            $table->string('description');
            $table->string('description_en');
            $table->integer('available_code_count')->nullable();
            $table->integer('used_code_count')->nullable()->default(0);
            $table->integer('total_code_count');
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->timestamp('activation_date')->nullable();
            $table->boolean('is_active')->default(0);
            $table->integer('boss_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
            $table->integer('promo_id')->unsigned()->index()->foreign()->references("id")->on("promos");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('invoice_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('company_name');
            $table->text('email');
            $table->text('phone_number')->nullable();
            $table->text('nip');
            $table->text('bank_name')->nullable();
            $table->text('account_number')->nullable();
            $table->integer('property_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("properties");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->text('invoice');
            $table->integer('property_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("properties");
            $table->integer('month_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("months");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('name_en');
            $table->string('slug');
            $table->string('description');
            $table->string('description_en');
            $table->integer('worker_threshold');
            $table->integer('percent');
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('policy_confirmations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip_address');
            $table->boolean('confirm');
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
        Schema::dropIfExists('categories');
        Schema::dropIfExists('items');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('years');
        Schema::dropIfExists('months');
        Schema::dropIfExists('days');
        Schema::dropIfExists('graphics');
        Schema::dropIfExists('graphic_employee');
        Schema::dropIfExists('graphic_requests');
        Schema::dropIfExists('graphic_request_employee');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('temp_users');
        Schema::dropIfExists('temp_properties');
        Schema::dropIfExists('codes');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('promo_codes');
        Schema::dropIfExists('invoice_datas');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('policy_confirmations');
    }
}
