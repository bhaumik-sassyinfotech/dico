@extends('template.default')
<title>DICO - Post</title>
@section('content')

@if(session()->has('success'))
<div class="alert alert-success">
    {{ session()->get('success') }}
</div>
@endif
@if(session()->has('err_msg'))
<div class="alert alert-danger">
    {{ session()->get('err_msg') }}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ route('post.index') }}">Post</a></li>
                <li class="active">View Post</li>
            </ol>
            <h1>Post</h1>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <form name="post_comment_form" id="post_comment_form" method="post" action="{{url('savecomment',$post->id)}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label><b>{{$post->post_title}}</b></label><br>
                                <small>
                                   <?php
                                    if($post['is_anonymous'] == 0) {
                                   
                                    echo $post->postUser->name; }
                                   ?> 
                                    on {{date('d/m/Y',strtotime($post->created_at))}}</small>
                            </div>
                            <div class="col-xs-12 form-group">
                                <label>{{$post->post_description}}</label>
                            </div>
                            <div class="col-xs-12 form-group">
                                <div class="col-md-2">
                                    <a href="javascript:void(0)" id="like_post" onclick="likePost({{$post['id']}})">
                                        <?php
                                        if (!empty($post['postUserLike'])) {
                                            ?>
                                            <i class="fa fa-thumbs-up"></i>
                                        <?php } else { ?>
                                            <i class="fa fa-thumbs-o-up"></i>
                                        <?php } ?>
                                    </a>
                                    <span id="post_like_count"><?php echo count($post['postLike']); ?></span>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:void(0)" id="dislike_post" onclick="dislikePost({{$post['id']}})">
                                        <?php
                                        if (!empty($post['postUserDisLike'])) {
                                            ?>
                                            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                        <?php } else { ?>
                                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                        <?php } ?>
                                    </a>
                                    <span id="post_dislike_count"><?php echo count($post['postDisLike']); ?></span>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:void(0)">
                                        <?php
                                        if (!empty($post['postComment'])) {
                                            ?>
                                            <i class="fa fa-comments"></i>
                                        <?php } else { ?>
                                            <i class="fa fa-comments-o"></i>
                                        <?php } ?>
                                    </a>
                                    <span><?php echo count($post['postComment']); ?></span>
                                </div>
                            </div>
                        </div>  
                        <hr>
                        <div class="form-group">
                            <textarea name="comment_text" id="comment_text" class="form-control autosize" placeholder="Leave a comment here" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 71.9792px;"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <span class="btn btn-primary fileinput-button">
                                    <i class="fa fa-upload"></i>
                                    <span>upload</span>
                                    <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Is Anonymous</label><br/>
                                <input type="checkbox" name="is_anonymous" id="is_anonymous">
                            </div>
                        </div>

                        <div class="row">
                            <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">
                            <?php
                                if($post['user_id'] == Auth::user()->id) {
                            ?>
                            <a href="{{route('post.edit',$post->id)}}" class="btn btn-primary">Edit</a>
                            <?php
                                }
                                ?>
                        </div>    
                    </div>
                </form>
                <!-- Comment Box start -->
                <form class="form-horizontal row-border">
                    <div class="panel-body">
                        <div class="row">
                            <?php
                            if (!empty($post['postComment'])) {
                                foreach ($post['postComment'] as $postComment) {
                                    ?>
                                    <div class="form-group">
                                        <div class="row" style="margin:0 !important;">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <?php if (!empty($postComment['commentUser'])) { ?> 
                                                        <div class="col-md-2">
                                                            <?php
                                                            $commentUser = $postComment['commentUser'];
                                                            if (!empty($commentUser->profile_image)) {
                                                                $profile_image = 'public/uploads/profile_pic/' . $commentUser->profile_image;
                                                            } else {
                                                                $profile_image = 'public/assets/demo/avatar/jackson.png';
                                                            }
                                                            ?>
                                                            <img src="{{asset($profile_image)}}" id="profile" alt="" class="pull-left" height="100px" width="100px" style="margin: 0 20px 20px 0"/>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <?php
                                                        //dd($commentUser);
                                                        if (!empty($commentUser['following']) && count($commentUser['following']) > 0 && $commentUser->id != Auth::user()->id) {
                                                            if ($commentUser['following'][0]->status == 1) {
                                                                ?>
                                                                <a href="{{ url('/view_profile/'.$commentUser->id) }}" class="btn btn-primary" >Unfollow</a>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a href="{{ url('/view_profile/'.$commentUser->id) }}" class="btn btn-primary" >Follow</a>  
                                                                <?php
                                                            }
                                                        } else if ($commentUser->id != Auth::user()->id) {
                                                            ?>
                                                            <a href="{{ url('/view_profile/'.$commentUser->id) }}" class="btn btn-primary" >Follow</a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <span style="float:left;">
                                                        <?php if($postComment['is_anonymous'] == 0) { ?>
                                                        <b><?php echo $commentUser['name']; ?></b><br>
                                                        <?php } ?>
                                                        <small><?php echo " - on " . date('m/d/Y', strtotime($commentUser['created_at'])); ?></small></span>
                                                        <?php if ($post['user_id'] == Auth::user()->id) { ?>
                                                        <span style="float: right;">
                                                            <a id="solution_{{$postComment['id']}}" href="javascript:void(0)" onclick="markSolution({{$postComment['id']}},{{$commentUser['id']}})">
                                                            <?php if($postComment['is_correct'] == 1) { ?>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                    <?php } else {?>
                                                                <i class="fa fa-star-o" aria-hidden="true"></i>
                                                            <?php } ?>  </a>Solution
                                                        </span>
                                                        <?php } else {
                                                            if($postComment['is_correct'] == 1) { ?><span style="float: right;"><a href="javascript:void(0)"><i class="fa fa-star" aria-hidden="true"></i></a> Solution</span><?php }
                                                        }
?><br>
                                                    <?php if ($commentUser['id'] == Auth::user()->id) { ?>
                                                        <span style="float:right;">
                                                            <a href="{{url('/deletecomment',$postComment['id'])}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </span><?php } ?>
                                                </div>
                                                <div class="row">
        <?php echo $postComment['comment_text']; ?>
                                                </div> 
                                                    <?php
                                                    if (!empty($postComment['commentAttachment'])) {
                                                        ?>
                                                    <div class="row"><b>Attachment : </b>
                                                        <a href="#">{{$postComment['commentAttachment']['file_name']}}</a>
                                                    </div><?php } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9"></div>
                                            <div class="col-md-3">
                                                <div class="col-md-1">
                                                    <a href="javascript:void(0)" id="like_comment_{{$postComment['id']}}" onclick="likeComment({{$postComment['id']}});" >
        <?php
        if (!empty($postComment['commentUserLike'])) {
            ?>
                                                            <i class="fa fa-thumbs-up"></i>
                                                        <?php } else { ?>
                                                            <i class="fa fa-thumbs-o-up"></i>
                                                        <?php } ?>
                                                    </a>
                                                    <span id="comment_like_count_{{$postComment['id']}}"><?php echo count($postComment['commentLike']) ?></span>
                                                </div>
                                                <div class="col-md-1"><a href="javascript:void(0)" id="dislike_comment_{{$postComment['id']}}" onclick="dislikeComment({{$postComment['id']}});" >
        <?php
        if (!empty($postComment['commentUserDisLike'])) {
            ?>
                                                            <i class="fa fa-thumbs-down"></i>
                                                        <?php } else { ?>
                                                            <i class="fa fa-thumbs-o-down"></i>
                                                        <?php } ?>
                                                    </a>
                                                    <span id="comment_dislike_count_{{$postComment['id']}}"><?php echo count($postComment['commentDisLike']); ?></span>
                                                </div>
                                                <div class="col-md-1"><a href=""><i class="fa fa-reply" aria-hidden="true"></i></a></div>
                                            </div>
                                        </div>
                                    </div>    
        <?php
    }
}
?>
                        </div> 
                    </div>
                </form>  
                <!-- Comment Box end -->
            </div>
        </div>
    </div>
</div>
@stop
@push('javascripts')
<script type="text/javascript">
function markSolution(commentid,userid)
{
    var _token = CSRF_TOKEN;
    
    var formData = {comment_id:commentid,user_id:userid,_token};
   $.ajax({
    url: SITE_URL+'/comment_solution',
    type: 'POST',
    data: formData,
    success: function(response) {
       var res = JSON.parse(response);
       var html = "";
       if(res.status == 1) {
           html += '<i class="fa fa-star" aria-hidden="true">';
       } else {
           html += '<i class="fa fa-star-o" aria-hidden="true">';
       }
       $('#solution_'+commentid).html(html);
    },
    error: function() {
    }
 }); 
 }
</script>
@endpush
