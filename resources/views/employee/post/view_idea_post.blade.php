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
                    <form name="post_comment_form" id="post_comment_form" method="post"
                          action="{{url('savecomment',$post->id)}}">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label><b>{{$post->post_title}}</b></label><br>
                                    <small>{{$post->postUser->name}}
                                        on {{date('d/m/Y',strtotime($post->created_at))}}</small>
                                </div>
                                <div class="col-xs-12 form-group">
                                    <label>{{$post->post_description}}</label>
                                </div>
                                <div class="row col-xs-12 form-group">
                                    <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
                                    <div class="col-md-2">
                                        <a href="javascript:void(0)" id="like_post" onclick="likePost({{$post['id']}})">

                                            <?php
                                            if(!empty($post[ 'postUserLike' ])) {
                                            ?>
                                            <i class="fa fa-thumbs-up"></i>
                                            <?php } else { ?>
                                            <i class="fa fa-thumbs-o-up"></i>
                                            <?php } ?>
                                        </a>
                                        <span id="post_like_count"><?php echo count($post[ 'postLike' ]);?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="javascript:void(0)" id="dislike_post" onclick="dislikePost({{$post['id']}})">
                                            <?php
                                            if(!empty($post[ 'postUserUnLike' ])) {
                                            ?>
                                            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                            <?php } else { ?>
                                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                            <?php } ?>
                                        </a>
                                        <span id="post_dislike_count"><?php echo count($post[ 'postUnLike' ]);?></span>
                                    </div>
                                    @if($post->idea_status == null && $currUser->role_id < 3)
                                        <div class="col-md-2"><a class="btn btn-success ideaStatus"
                                                                 href="javascript:void(0)" data-post-status="approve">Approve</a>
                                        </div>
                                        <div class="col-md-2"><a class="btn btn-danger ideaStatus"
                                                                 href="javascript:void(0)"
                                                                 data-post-status="deny">Deny</a></div>
                                        <div class="col-md-2"><a class="btn btn-warning ideaStatus"
                                                                 href="javascript:void(0)" data-post-status="amend">Amend</a>
                                        </div>
                                    @else
                                        @if($post->idea_status == null)
                                            <div class="col-md-6"><strong>Action is yet to be taken on this post.</strong></div>
                                        @else
                                            @php
                                                $ideaStatus = '';
                                                if($post->idea_status === 'approve')
                                                    $ideaStatus = 'Approved';
                                                else if($post->idea_status === 'deny')
                                                    $ideaStatus = 'Denied';
                                                else if($post->idea_status === 'amend')
                                                    $ideaStatus = 'Amended';
                                            @endphp
                                            <div class="col-md-6">{{ $ideaStatus }} by: <strong>{{ $post->ideaUser->name }}</strong></div>

                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12 btn-toolbar">
                                        <a href="{{route('post.index')}}" class="btn btn-default">Back</a>
                                        @if($currUser->id == $post->user_id)
                                            <a href="{{route('idea.edit',$post->id)}}" class="btn btn-primary">Edit</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop