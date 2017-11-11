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
            $table->increments('id')->unsigned();
            $table->string('name','255');
            $table->string('email','255')->unique();
            $table->string('password');
            $table->string('profile_image','255');
            $table->boolean('is_active')->default(1);
            $table->integer('role_id')->foreign('role_id')->references('id')->on('roles')->index();
            $table->integer('company_id')->foreign('company_id')->references('id')->on('company')->onDelete('cascade')->index();
            $table->integer('security_question_id');
            $table->string('google_id','255')->nullable();
            $table->string('linked_id','255')->nullable();
            $table->boolean('is_suspended')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
