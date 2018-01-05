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
        
        public function postView()
        {
            return $this->hasMany('App\PostView' , 'post_id' , 'id');
        }
        public function postViewCount()
        {
         return $this->postView;
        }
        
        public function postLike()
        {
            return $this->hasMany('App\PostLike' , 'post_id' , 'id')->where('flag' , 1);
        }
        
        public function postLikeCount()
        {
         return $this->postLike;
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
            return $this->hasMany('App\Attachment' , 'type_id' , 'id')->where('type' , 1);
        }
        
        public function postComment()
        {
            return $this->hasMany('App\Comment' , 'post_id' , 'id');
        }
    
        public function ideaUser()
        {
            return $this->hasOne('App\User' , 'id' , 'idea_status_updated_by');
        }
        public function postTag()
        {
            return $this->hasMany('App\PostTag' , 'post_id' , 'id');
        }
        
    }