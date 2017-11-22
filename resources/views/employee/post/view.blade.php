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
                <form name="post_comment_form" id="post_comment_form" method="post" action="{{url('savecomment',$post->id)}}">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label><b>{{$post->post_title}}</b></label><br>
                                <small>{{$post->postUser->name}} on {{date('d/m/Y',strtotime($post->created_at))}}</small>
                            </div>
                            <div class="col-xs-12 form-group">
                                    <label>{{$post->post_description}}</label>
                            </div>
                            <div class="col-xs-12 form-group">
                                <div class="col-md-2">
                                    <a href="{{url('like_post',$post['id'])}}">
                                        <?php
                                            if(!empty($post['postUserLike'])) {
                                        ?>
                                        <i class="fa fa-thumbs-up"></i>
                                        <?php } else { ?>
                                        <i class="fa fa-thumbs-o-up"></i>
                                        <?php } ?>
                                    </a>
                                    <span><?php echo count($post['postLike']);?></span>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{url('unlike_post',$post['id'])}}">
                                        <?php
                                            if(!empty($post['postUserUnLike'])) {
                                        ?>
                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                        <?php } else { ?>
                                        <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                        <?php } ?>
                                    </a>
                                    <span><?php echo count($post['postUnLike']);?></span>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:void(0)">
                                        <?php
                                            if(!empty($post['postComment'])) {
                                        ?>
                                        <i class="fa fa-comments"></i>
                                        <?php } else { ?>
                                        <i class="fa fa-comments-o"></i>
                                        <?php } ?>
                                    </a>
                                    <span><?php echo count($post['postComment']);?></span>
                                </div>
                            </div>
                        </div>  
                        <hr>
                        <div class="form-group">
                            <textarea name="comment_text" id="comment_text" class="form-control autosize" placeholder="Leave a comment here" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 71.9792px;"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Is Anonymous</label><br/>
                                <input type="checkbox" name="is_comment_anonymous" id="is_comment_anonymous">
                            </div>
                        </div>
                        
                        <div class="row">
                            <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">
                            <a href="{{route('post.edit',$post->id)}}" class="btn btn-primary">Edit</a>
                        </div>    
                    </div>
                </form>
                <!-- Comment Box start -->
                <form class="form-horizontal row-border">
                    <div class="panel-body">
                        <div class="row">
                            <?php
                                if(!empty($post['postComment'])) {
                                    foreach($post['postComment'] as $postComment) {
                            ?>
                            <div class="form-group">
                                <div class="row" style="margin:0 !important;">
                                <div class="col-md-2">
                                    <div class="row">
                                    <?php
                                    if(!empty($postComment['commentUser'])) { ?> 
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
                                if(!empty($commentUser['following']) && count($commentUser['following']) > 0) {
                                    if($commentUser['following'][0]->status == 1) {
                                    ?>
                                        <a href="{{ url('/view_profile/'.$commentUser->id) }}" class="btn btn-primary" >Unfollow</a>
                                        <?php    
                                    }else {
                                    ?>
                                      <a href="{{ url('/view_profile/'.$commentUser->id) }}" class="btn btn-primary" >Follow</a>  
                                    <?php    
                                    }
                                    
                                } else if($commentUser->id != Auth::user()->id) {
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
                                        <span style="float:left;"><b><?php echo $commentUser['name'];?></b><br>
                                        <small><?php echo " - on ".date('m/d/Y',strtotime($commentUser['created_at'])); ?></small></span>
                                        <span style="float: right;"><i class="fa fa-star-o" aria-hidden="true"></i>  Solution</span><br>
                                    </div>
                                    <div class="row">
                                        <?php echo $postComment['comment_text']; ?>
                                    </div>    
                                </div>
                                </div>
                                <div class="row">
                                        <div class="col-md-9"></div>
                                        <div class="col-md-3">
                                            <div class="col-md-1"><a href=""><i class="fa fa-thumbs-o-up"></i></a></div>
                                            <div class="col-md-1"><a href=""><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a></div>
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
@section('javascript')
<script type="text/javascript">
</script>
@endsection