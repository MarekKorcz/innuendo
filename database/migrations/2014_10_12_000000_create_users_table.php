<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->string('slug')->nullable();
            $table->integer('phone_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->boolean('is_approved')->default(1);
            $table->boolean('isAdmin')->nullable();
            $table->boolean('isBoss')->nullable();
            $table->boolean('isEmployee')->nullable();
            $table->integer('boss_id')->nullable()->unsigned()->index()->foreign()->references("id")->on("users");
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
