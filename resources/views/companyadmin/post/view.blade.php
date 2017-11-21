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
                <form>
                    <div class="panel-body">
                        <div class="row">
                            <?php
                                if(!empty($post['postComment'])) {
                                    foreach($post['postComment'] as $postComment) {
                            ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <?php
                                    if(!empty($postComment['commentUser'])) {
                                        $commentUser = $postComment['commentUser'];
                                        if (!empty($commentUser->profile_image)) {
                                            $profile_image = 'public/uploads/profile_pic/' . $commentUser->profile_image;
                                        } else {
                                            $profile_image = 'public/assets/demo/avatar/jackson.png';
                                        }
                                    ?>
                                    <img src="{{asset($profile_image)}}" id="profile" alt="" class="pull-left" height="100px" width="100px" style="margin: 0 20px 20px 0"/>
                                </div>
                                <div class="col-md-10"><b><?php echo $commentUser['name'];?></b><br><?php echo $postComment['comment_text']; ?></div>
                            </div>
                            <?php
                                        }
                                    }
                                }
                            ?>
                        </div> 
                    </div>
                </form>    
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script type="text/javascript">
</script>
@endsection