<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('meeting_title','255');
            $table->text('meeting_description')->nullable();
            $table->dateTime('date_of_meeting')->nullable();
            $table->tinyInteger('privacy')->comment('0 = public , 1 = private')->default(0);
            $table->integer('created_by')->default(0);
            $table->string('group_id','255')->nullable()->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->index();
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
        Schema::dropIfExists('meetings');
    }
}
