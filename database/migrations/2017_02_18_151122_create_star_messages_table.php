<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStarMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('star_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('comment_id');
            $table->integer('times');
            $table->boolean('is_read');
            $table->index(['user_id', 'is_read']);
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
        Schema::dropIfExists('star_messages');
    }
}
