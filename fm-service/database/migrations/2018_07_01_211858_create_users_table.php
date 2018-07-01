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
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
        });
        
        Schema::create('friend', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('friend_user_id');
        });
        
        Schema::create('subscribe', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requestor_user_id');
            $table->integer('target_user_id');
        });
        
        Schema::create('block_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requestor_user_id');
            $table->integer('target_user_id');
        });
        
        Schema::create('updates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('text');
            $table->timestamp('created_at');
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
