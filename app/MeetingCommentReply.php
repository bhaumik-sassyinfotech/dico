<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    class MeetingCommentReply extends Model
    {
        //
        protected $table = 'meeting_comment_replies';
        use SoftDeletes;
        
        public function commentReplyUser()
        {
            return $this->hasOne('App\User' , 'id' , 'user_id');
        }
    }
