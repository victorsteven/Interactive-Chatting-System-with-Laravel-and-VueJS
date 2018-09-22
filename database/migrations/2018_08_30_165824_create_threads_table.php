<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->unsignedInteger('channel_id');
            $table->unsignedInteger('replies_count')->default(0);
            $table->unsignedInteger('visits')->default(0);
            $table->string("title");
            $table->string('slug')->unique()->nullable();
            $table->text("body");
            $table->unsignedInteger('best_reply_id')->nullable();
            $table->boolean('locked')->default(false);
            $table->timestamps();

            $table->foreign('best_reply_id')->references('id')->on('replies')->onDelete('set null');
            //not we cant use "cascade" we used "set null", if we use cascade, it will delete the thread as well, and dont want that
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
