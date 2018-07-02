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
            $table->integer('user_id')->unsigned();
            $table->integer('friend_user_id')->unsigned();
        });
        Schema::table('friend', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('friend_user_id')->references('id')->on('user');
        });
        
        
        Schema::create('subscribe', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requestor_user_id')->unsigned();
            $table->integer('target_user_id')->unsigned();
        });
        Schema::table('subscribe', function(Blueprint $table) {
            $table->foreign('requestor_user_id')->references('id')->on('user');
            $table->foreign('target_user_id')->references('id')->on('user');
        });
        
        Schema::create('block_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requestor_user_id')->unsigned();
            $table->integer('target_user_id')->unsigned();
        });
        Schema::table('block_list', function(Blueprint $table) {
            $table->foreign('requestor_user_id')->references('id')->on('user');
            $table->foreign('target_user_id')->references('id')->on('user');
        });
        
        Schema::create('updates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->text('text');
            $table->timestamp('created_at');
        });
        Schema::table('updates', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('updates');
        Schema::dropIfExists('block_list');
        Schema::dropIfExists('subscribe');
        Schema::dropIfExists('friend');
        Schema::dropIfExists('user');
    }
}
