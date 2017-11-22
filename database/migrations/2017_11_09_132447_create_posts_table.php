<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->foreign('company_id')->references('id')->on('company')->onDelete('cascade')->index();
            $table->integer('group_id')->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->index();
            $table->string('post_title','255');
            $table->text('post_description')->nullable();
            $table->enum('post_type',['idea','question','challenge']);
            $table->integer('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->index();
            $table->boolean('is_anonymous')->default(0);	
            $table->boolean('status')->default(1);
            $table->enum('idea_status',['approve','deny','amend'])->nullable();
            $table->string('idea_reason','255')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
