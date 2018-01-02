<?php
    
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    
    class CreateMeetingAttachmentsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('meeting_attachments' , function (Blueprint $table) {
                $table->increments('id');
//                $table->integer('meeting_id')->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade')->index()->default(0);
                $table->integer('type')->comment('1 => meeting , 2 => comment')->default(0);
                $table->integer('type_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->string('file_name')->comment('Encrypted filename.');
                $table->string('original_file_name');
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
            Schema::dropIfExists('meeting_attachments');
        }
    }
