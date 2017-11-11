<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTagPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tag_users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('post_id')->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->index();
            $table->integer('tagged_user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->index();;
            $table->integer('position')->nullable();
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
        Schema::dropIfExists('user_tag_posts');
    }
}
