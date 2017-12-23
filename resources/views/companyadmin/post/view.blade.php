@extends('template.default')
<title>DICO - Post</title>
@section('content')

<div id="page-content" class="post-details">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ route('post.index') }}">Post</a></li>
                <li class="active">View Post</li>
            </ol>
            <h1 class="icon-mark tp-bp-0">Question Post</h1>
            <hr class="border-out-hr">
        </div>
        <div class="container">
            <div class="row">
                <div id="post-detail-left" class="col-sm-8">
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
                    <!-- START TITLE -->
                    <div class="group-wrap">   
                        <div class="pull-left">
                            <h3>{{$post->post_title}}</h3>
                            <p class="user-icon">-<?php if ($post['is_anonymous'] == 0) { echo $post->postUser->name; } else { echo "Anonymous"; } ?> <span>on {{date(DATE_FORMAT,strtotime($post->created_at))}}</span></p>      
                        </div> 
                        <div class="pull-right">
                            <div class="options">
                                <div class="btn-toolbar">
                                    <div class="btn-group hidden-xs">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <div class="btn-toolbar">
                                                <line></line>
                                                <line></line>
                                                <line></line>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Notification off</a></li>
                                            <?php
                                                if ($post['user_id'] == Auth::user()->id) {
                                                    ?>
                                                    <li><a href="{{route('post.edit',Helpers::encode_url($post->id))}}">Edit Post</a></li>
                                                    <?php
                                                }
                                            ?>
                                            <li><a href="#">Delete Post</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <!-- END TITLE -->
                    <div class="post-wrap-details"> 
                        <p class="text-12">{{$post->post_description}}</p>
                        <div class="post-details-like">
                            <div class="like"><a href="javascript:void(0)" id="like_post" onclick="likePost({{$post['id']}})">
                                <?php
                                if (!empty($post['postUserLike'])) {
                                    ?>
                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                <?php } else { ?>
                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                <?php } ?></a>
                                <p id="post_like_count"><?php echo count($post['postLike']); ?></p></div>
                            <div class="unlike"><a href="javascript:void(0)" id="dislike_post" onclick="dislikePost({{$post['id']}})">
                                    <?php
                                    if (!empty($post['postUserDisLike'])) {
                                        ?>
                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                    <?php } ?>
                                </a><p id="post_dislike_count"><?php echo count($post['postDisLike']); ?></p></div>
                        </div>
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
                    <hr>
                    <form class="post-form" name="post_comment_form" id="post_comment_form" method="post" action="{{url('savecomment',$post->id)}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="post_id" id="post_id" value="{{$post['id']}}">
                        <div class="field-group comment">
                            <textarea name="comment_text" id="comment_text" class="form-control autosize" placeholder="Leave a comment here" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 71.9792px;"></textarea>
                        </div>
                        <div class="field-group files">
                                    <input disabled="disabled" placeholder="upload file" id="uploadFile">
                                    <label class="custom-file-input">
                                        <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                    </label>
                        </div>

                        <div class="field-group checkbox-btn">
                            <div class="pull-left">
                                <label class="check">Post as Anonymous <input type="checkbox" name="is_anonymous" id="is_anonymous"><span class="checkmark"></span></label>
                            </div>
                            <div class="pull-right">
                                <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                    <br>
                    <hr>
                    <!-- Comment Box start -->
                    <div class="container">
                        <form class="form-horizontal row-border">
                        <?php
                            if (!empty($post['postComment'])) {
                                foreach ($post['postComment'] as $postComment) {
                                    if (!empty($postComment['commentUser'])) { 
                                        $commentUser = $postComment['commentUser'];
                                        if (!empty($commentUser->profile_image) && $postComment['is_anonymous'] == 0) {
                                            $profile_image = 'public/uploads/profile_pic/' . $commentUser->profile_image;
                                        } else {
                                            $profile_image = 'public/assets/demo/avatar/jackson.png';
                                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-2 user-image">
                                <div class="img-wrap">
                                    <img alt="post user" src="{{asset($profile_image)}}" id="profile"/>
                                </div>
                                <?php
                                    $comment_id = Helpers::encode_url($commentUser->id);
                                    if (!empty($commentUser['following']) && count($commentUser['following']) > 0 && $commentUser->id != Auth::user()->id) {
                                        if ($commentUser['following'][0]->status == 1) {
                                            ?>
                                            <a href="{{ url('/view_profile/'.$comment_id) }}" class="btn btn-primary" >Unfollow</a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="{{ url('/view_profile/'.$comment_id) }}" class="btn btn-primary" >Follow</a>
                                            <?php
                                        }
                                    } else if ($commentUser->id != Auth::user()->id) {
                                        ?>
                                        <a href="{{ url('/view_profile/'.$comment_id) }}" class="btn btn-primary" >Follow</a>
                                        <?php
                                    }
                                ?>
                            </div>
                            <div class="col-sm-10 user-rply">
                                <div class="post-inner-reply"> 
                                    <div class="pull-left post-user-nam">
                                        <h3><?php if ($postComment['is_anonymous'] == 0) { echo $commentUser['name']; } else { echo "<b>Anonymous</b>"; } ?></h3>
                                        <p>- on <?php echo date(DATE_FORMAT, strtotime($commentUser['created_at'])); ?></p>
                                    </div>
                                    <div class="pull-right post-reply-pop">
                                        <div class="options">
                                            <div class="star-wrap">
                                                <?php if ($post['user_id'] == Auth::user()->id) { ?>
                                                    <?php 
                                                        if ($postComment['is_correct'] == 1) { ?>
                                                                <i class="fa fa-star" aria-hidden="true"></i><?php
                                                        } else { ?>
                                                                <i class="fa fa-star-o" aria-hidden="true"></i><?php
                                                            } ?>  
                                                        <a id="solution_{{$postComment['id']}}" href="javascript:void(0)" onclick="markSolution({{$postComment['id']}}, {{$commentUser['id']}}, {{$post['id']}})">
                                                        Solution</a> 
                                                    <?php
                                                } else {
                                                    if ($postComment['is_correct'] == 1) {
                                                        ?><i class="fa fa-star" aria-hidden="true"></i>Solution<?php
                                                    }
                                                }
                                                ?>
                                            </div>            
                                                  <div class="btn-toolbar">
                                                    <div class="btn-group hidden-xs">
                                                         <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                                            <div class="btn-toolbar">
                                                                <line></line>
                                                                <line></line>
                                                                <line></line>
                                                            </div>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="#">Edit Comment</a></li>
                                                            <li><a href="#">Report Comment</a></li>
                                                             <?php if ($commentUser['id'] == Auth::user()->id) { ?>
                                                            <li><a href="{{url('/deletecomment',$postComment['id'])}}">Delete Comment</a></li><?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                        </div>    
                                    </div> 
                                </div>
                                <p class="text-12">
                                    <?php echo $postComment['comment_text']; ?>
                                </p>
                               <div class="rply-box">
                                    <div class="rply-count">
                                        <a href="javascript:void(0)" id="like_comment_{{$postComment['id']}}" onclick="likeComment({{$postComment['id']}});" >
                                            <?php
                                            if (!empty($postComment['commentUserLike'])) {
                                                ?>
                                                <i class="fa fa-thumbs-up"></i>
                                            <?php } else { ?>
                                                <i class="fa fa-thumbs-o-up"></i>
                                            <?php } ?>
                                        </a><span id="comment_like_count_{{$postComment['id']}}"><?php echo count($postComment['commentLike']) ?></span>
                                        <!-- <img alt="post-like" src="assets/img/like.png"><p>08</p>-->
                                    </div> 
                                    <div class="rply-count">
                                        <a href="javascript:void(0)" id="dislike_comment_{{$postComment['id']}}" onclick="dislikeComment({{$postComment['id']}});" >
                                            <?php
                                            if (!empty($postComment['commentUserDisLike'])) {
                                                ?>
                                                <i class="fa fa-thumbs-down"></i>
                                            <?php } else { ?>
                                                <i class="fa fa-thumbs-o-down"></i>
                                            <?php } ?>
                                        </a>
                                        <span id="comment_dislike_count_{{$postComment['id']}}"><?php echo count($postComment['commentDisLike']); ?></span>
                                        <!-- <img alt="post-rply" src="assets/img/post-rply.png"> <p>04</p>-->
                                    </div> 
                                   <div class="rply_count">
                                       <a href="javascript:void(0);" data-toggle="modal" data-id="{{$postComment['id']}}" id="modalComment" data-target="#myModalComment"><i class="fa fa-reply" aria-hidden="true"></i></a>
                                   </div>
                                </div>
                        </div>
                        </div>
                                    <?php } 
                                         
                            } 
                        } ?>
                        </form>
                        <div id="myModalComment" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Comment Here</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <textarea name="comment_text" id="comment_text" class="form-control autosize" placeholder="Leave a comment here"></textarea>
                                    </div>
                                    <div class="row">
                                        <label class="checkbox-inline"><input type="checkbox" name="is_anonymous" id="is_anonymous">Anonymous</label><br>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="comment_reply()">Submit</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div></div>
                    </div>    
                    <?php /*<form class="form-horizontal row-border">
                        <div class="panel-body">
                            <div class="row">
                                <?php
                                if (!empty($post['postComment'])) {
                                    foreach ($post['postComment'] as $postComment) {
                                        ?>
                                        <div class="form-group" id="commentreply_{{$postComment['id']}}">
                                            <div class="row" style="margin:0 !important;">
                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <?php if (!empty($postComment['commentUser'])) { ?> 
                                                            <div class="col-md-2">
                                                                <?php
                                                                $commentUser = $postComment['commentUser'];
                                                                if (!empty($commentUser->profile_image) && $postComment['is_anonymous'] == 0) {
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
                                                            $comment_id = Helpers::encode_url($commentUser->id);
                                                            if (!empty($commentUser['following']) && count($commentUser['following']) > 0 && $commentUser->id != Auth::user()->id) {
                                                                if ($commentUser['following'][0]->status == 1) {
                                                                    ?>
                                                                    <a href="{{ url('/view_profile/'.$comment_id) }}" class="btn btn-primary" >Unfollow</a>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <a href="{{ url('/view_profile/'.$comment_id) }}" class="btn btn-primary" >Follow</a>
                                                                    <?php
                                                                }
                                                            } else if ($commentUser->id != Auth::user()->id) {
                                                                ?>
                                                                <a href="{{ url('/view_profile/'.$comment_id) }}" class="btn btn-primary" >Follow</a>
                                                                <?php
                                                            }
                                                        }
                                                        ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="row">
                                                        <span style="float:left;">
                                                            <?php if ($postComment['is_anonymous'] == 0) { ?>
                                                                <b><?php echo $commentUser['name']; ?></b>
                                                                <?php
                                                            } else {
                                                                echo "<b>Anonymous</b>";
                                                            }
                                                            ?><br>
                                                            <small><?php echo " - on " . date('m/d/Y', strtotime($commentUser['created_at'])); ?></small></span>
                                                        <?php if ($post['user_id'] == Auth::user()->id) { ?>
                                                            <span style="float: right;">
                                                                <a id="solution_{{$postComment['id']}}" href="javascript:void(0)" onclick="markSolution({{$postComment['id']}}, {{$commentUser['id']}}, {{$post['id']}})">
                                                                    <?php if ($postComment['is_correct'] == 1) { ?>
                                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-star-o" aria-hidden="true"></i>
                                                                    <?php } ?>  </a>Solution
                                                            </span>
                                                            <?php
                                                        } else {
                                                            if ($postComment['is_correct'] == 1) {
                                                                ?><span style="float: right;"><a href="javascript:void(0)"><i class="fa fa-star" aria-hidden="true"></i></a> Solution</span><?php
                                                            }
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
                                                    <div class="col-md-1"><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal{{$postComment['id']}}"><i class="fa fa-reply" aria-hidden="true"></i></a></div>
                                                    <div id="myModal{{$postComment['id']}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">Comment Here</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <textarea name="comment_text" id="comment_text_{{$postComment['id']}}" class="form-control autosize" placeholder="Leave a comment here"></textarea>
                                                                    </div>
                                                                    <div class="row">
                                                                        <label class="checkbox-inline"><input type="checkbox" name="is_anonymous" id="is_anonymous_{{$postComment['id']}}">Anonymous</label><br>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="comment_reply({{$postComment['id']}})">Submit</button>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- comment reply box start -->
                                            <?php
                                            //dd($postComment['commentReply']);
                                            if (!empty($postComment['commentReply'])) {
                                                $srno = 0;
                                                foreach ($postComment['commentReply'] as $commentReply) {
                                                    $srno++;
                                                    ?>
                                                    <div class="form-group row cmry" id="{{$srno}}"><div class="col-md-12">
                                                            <span style="float:left;">
                                                                <?php if ($commentReply['is_anonymous'] == 0) { ?>
                                                                    <b><?php echo $commentReply['commentReplyUser']['name']; ?></b>
                                                                    <?php
                                                                } else {
                                                                    echo "<b>Anonymous</b>";
                                                                }
                                                                ?>

                                                                <br>
                                                                <small><?php echo " - on " . date('d/m/Y', strtotime($commentReply['created_at'])); ?></small>
                                                            </span>  <br>
                                                            <div class="col-md-12">    
                                                                <?php echo $commentReply['comment_reply']; ?></div>
                                                        </div>  
                                                        <?php if ($commentReply['user_id'] == Auth::user()->id) { ?>
                                                            <span style="float:right;">
                                                                <a href="{{url('/deletecommentReply',$commentReply['id'])}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                            </span><?php } ?></div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <!-- comment reply box end --> 
                                        </div>    
                                        <?php
                                    }
                                }
                                ?>
                            </div> 
                        </div>
                    </form>  */?>
                    <!-- Comment Box end -->
                    </div>  
               
                    <div class="col-sm-4" id="post-detail-right">
                    <!-- START RIGHT SIDEBAR -->
                        <div class="category">
                            <h2>Group</span></h2>
                            <div class="idea-grp post-category">
                                <?php
                                    if(!empty($post_group)) {
                                        foreach($post_group as $group) {
                                ?>
                                <div class="member-wrap">
                                    <div class="member-details">
                                        <h3>{{$group->group_name}}</h3>
                                        <p>Members: <span>{{$group->groupUsersCount->cnt}}</span></p>
                                    </div>
                                </div>
                                <?php
                                        }
                                    }
                                ?>
                            </div>  
                        </div>
                        <div class="category">
                            <h2>Tags</h2>
                            <div class="post-circle post-category">
                                <?php
                                    if(!empty($post->postTag)) {
                                        foreach($post->postTag as $postTag) {
                                ?>
                                <a href="#"> {{$postTag['tag']['tag_name']}}</a>
                                <?php 
                                        }
                                    }
                                ?>    
                            </div>  
                        </div>  

                        <div class="category">
                            <h2>Similar Posts</h2>
                            <div class="post-links">
                                <a href="#">who has any right to find fault with....</a>
                                <a href="#">who has any right to find fault with....</a>
                                <a href="#">who has any right to find fault with....</a>
                                <a href="#">who has any right to find fault with....</a>
                            </div>  
                        </div> 
                        <?php
                            if(!empty($post->postAttachment)) {
                        ?>
                        <div class="category">
                            <h2>Uploaded Files</h2>
                            <div class="idea-grp post-category">
                                <?php
                                //dd($post->postAttachment);
                                    foreach($post->postAttachment as $attachment) {
                                ?>
                                <div class="member-wrap files-upload">
                                    <div class="member-img">
                                        <img src="{{asset('assets/img/uploadfiles1.PNG')}}" alt="no">
                                    </div>
                                    <div class="member-details">
                                        <h3>{{$attachment->file_name}}</h3>
                                        <p>Uploaded By:<a href="#">{{$attachment->attachmentUser->name}}</a></p>
                                    </div>
                                </div>
                                    <?php } ?>
                            </div> 
                    </div>
                        <?php
                            }
                        ?>
                    <!-- END RIGHT SIDEBAR -->
                </div> 
                 </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('javascripts')
<script type="text/javascript">
    function markSolution(commentid, userid, postid)
    {
    swal({
    title: "Are you sure?",
            text: "This will be consider as answer and publish to post user.",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: true
    }, function () {
    var _token = CSRF_TOKEN;
    var formData = {comment_id:commentid, user_id:userid, post_id:postid, _token};
    $.ajax({
    url: SITE_URL + '/comment_solution',
            type: 'POST',
            data: formData,
            success: function(response) {
            var res = JSON.parse(response);
            var html = "";
            if (res.status == 1) {
            html += '<i class="fa fa-star" aria-hidden="true">';
            } else if (res.status == 2) {
            html += '<i class="fa fa-star" aria-hidden="true">';
            swal("Error", res.msg, "error");
            } else {
            html += '<i class="fa fa-star-o" aria-hidden="true">';
            swal("Error", res.msg, "error");
            }
            $('#solution_' + commentid).html(html);
            },
            error: function(e) {
            swal("Error", e, "error");
            }
    });
    });
    }
    function comment_reply() {
        var commentid = $('#modalComment').attr('data-id');
        var _token = CSRF_TOKEN;
        var post_id = $('#post_id').val();
        var comment_reply = $('#comment_text_' + commentid).val();
        var anonymous = 0;
        var srno = $('#commentreply_' + commentid + ' .cmry:first').attr('id');
        if ($("#is_anonymous_" + commentid).is(':checked')) {
            anonymous = 1;
        } else {
            anonymous = 0;
        }
        var formData = {comment_id:commentid, comment_reply:comment_reply, post_id:post_id, is_anonymous:anonymous, srno:srno, _token};
        $.ajax({
            url: SITE_URL + '/comment_reply',
            type: 'POST',
            data: formData,
            success: function(response) {
                /*res = JSON.parse(response);
                 if (res.status == 1) {
                 location.reload();
                 } else {
                 swal("Error", res.msg, "error");
                 }*/
                console.log(commentid, "::::", srno);
                $('#commentreply_' + commentid + ' #' + srno).before(response);
            },
            error: function(e) {
                swal("Error", e, "error");
            }
        });
    }
</script>
@endpush
