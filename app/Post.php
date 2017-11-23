<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Auth;
    
    class Post extends Model
    {
        //
        use SoftDeletes;
        
        public function postUser()
        {
            return $this->hasOne('App\User' , 'id' , 'user_id')->where('company_id' , Auth::user()->company_id);
        }
        
        public function postLike()
        {
            return $this->hasMany('App\PostLike' , 'post_id' , 'id')->where('flag' , 1);
        }
        
        public function postDisLike()
        {
            return $this->hasMany('App\PostLike' , 'post_id' , 'id')->where('flag' , 2);
        }
        
        public function postUserLike()
        {
            return $this->hasOne('App\PostLike' , 'post_id' , 'id')->where('flag' , 1);
        }
        
        public function postUserDisLike()
        {
            return $this->hasOne('App\PostLike' , 'post_id' , 'id')->where('flag' , 2);
        }
        
        public function postAttachment()
        {
            return $this->hasOne('App\Attachment' , 'type_id' , 'id')->where('type' , 1);
        }
        
        public function postComment()
        {
            return $this->hasMany('App\Comment' , 'post_id' , 'id')->orderBy('id' , 'desc');
        }
    
        public function ideaUser()
        {
            return $this->hasOne('App\User' , 'id' , 'idea_status_updated_by');
        }
    }
