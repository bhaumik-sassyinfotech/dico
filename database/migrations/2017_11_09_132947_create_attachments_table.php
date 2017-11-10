<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('attachment_id')->unsigned();
            $table->integer('type')->comment('1-Post,2-Answer/Comment');
            $table->integer('type_id');
            $table->integer('user_id')->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->index();
            $table->string('file_name','255');
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
        Schema::dropIfExists('attachments');
    }
}
