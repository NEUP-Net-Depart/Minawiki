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
            $table->string('tel')->unique();
            $table->string('password');
            $table->string('salt');
            $table->boolean('disable');
            $table->integer('active_point');
            $table->integer('contribute_point');
            $table->tinyInteger('power');
            $table->string('admin_name');
            $table->string('theme');
            $table->boolean('no_disturb');
            $table->string('token');
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
