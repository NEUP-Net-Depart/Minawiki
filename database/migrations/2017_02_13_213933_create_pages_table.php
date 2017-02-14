<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('father_id');
            $table->index('father_id');
            $table->string('title');
            $table->unique('title');
            $table->boolean('is_folder')->default(false);
            $table->boolean('allow_child_folder')->default(true);
            $table->boolean('protect_children')->default(false);
            $table->tinyInteger('power')->default(0);
            $table->boolean('is_notice')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('pages');
    }
}
