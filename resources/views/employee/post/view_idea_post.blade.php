@extends('template.default')
<title>@lang("label.DICOPost")</title>
@section('content')
    
    <div id="page-content" class="idea-details post-details">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ route('/home') }}">@lang("label.adDashboard")</a></li>
                    <li><a href="{{ route('post.index') }}">@lang("label.adPost")</a></li>
                    <li class="active">@lang("label.ViewPost")</li>
                </ol>
                <h1 class="icon-mark tp-bp-0">@lang("label.IdeaPost")</h1>
                <hr class="border-out-hr">
            
            </div>
            <div class="container">
                <div class="row">
                    <div id="post-detail-left" class="col-sm-8">
                        <div class="group-wrap">
                            <div class="pull-left">
                                <h3 class="profanity">{{$post->post_title}}</h3>
                                <div class="user-wrap">
                                    <div class="user-img">
                                        @if(empty($post->postUser->profile_image))
                                            <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                                        @else
                                            <img src="{{ asset('public/uploads/profile_pic/'.$post->postUser->profile_image) }}">
                                        @endif
                                    </div>
                                    <p class="user-icon">-
                                        <a href="{{route('view_profile', Helpers::encode_url($post->postUser->id))}}">{{$post->postUser->name}}</a>
                                        <span>@lang("label.on") {{date(DATE_FORMAT,strtotime($post->created_at))}}</span></p>
                                </div>
                            </div>
                            <div class="pull-right">
                                <div class="options">
                                    <div class="fmr-10">
                                        <a class="set-alarm" href="">a</a>
                                        <?php
                                            if ($post['user_id'] == Auth::user()->id) {
                                        ?>
                                        <a class="set-edit" href="{{route('post.edit',Helpers::encode_url($post->id))}}">e</a>
                                        <?php /*<a class="set-edit" href="{{route('idea.edit',$post->id)}}">w</a>*/?>
                                        <a class="set-delete" href="javascript:void(0);" onclick="deletepost({{$post->id}})">w</a>
                                        <?php
                                            }
                                        ?>
                                        <?php 
                                            if(!empty($post['postFlagged'])) {
                                                if($post['postFlagged']['user_id'] == Auth::user()->id) {
                                        ?>
                                        <a class="set-warning no-fontawesome"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                        <?php } else { ?>
                                        <a class="set-warning" href="#flagged" data-toggle="modal">w</a>
                                        <?php } } else { ?>
                                        <a class="set-warning" href="#flagged" data-toggle="modal">w</a>
                                        <?php } ?>
                                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="flagged" class="modal fade" style="display: none;">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button aria-hidden="true" data-dismiss="modal" class="desktop-close" type="button"></button>
                                                        <h4 class="modal-title">@lang("label.RequestflaggedPost")</h4>
                                                    </div>
                                                    <form method="post" class="common-form" name="post_flagged_form" id="post_flagged_form">
                                                        <div class="form-group">
                                                           <label>@lang("label.MessageToAuthor"):</label> 
                                                           <textarea type="text" placeholder="Type here" id="post_message_autor" name="post_message_autor"></textarea>
                                                        </div> 
                                                        <div class="form-group">
                                                            <div class="btn-wrap-div">
                                                                <input class="st-btn" type="button" value="@lang('label.Submit')" name="submit" id="submit" onclick="reportPostFlagged();">
                                                                 <input value="@lang('label.Cancel')" class="st-btn" aria-hidden="true" data-dismiss="modal" type="reset">
                                                            </div>     
                                                        </div>     
                                                    </form>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="post-wrap-details">
                            <p class="text-12 profanity">{{$post->post_description}}</p>
                            <div class="post-details-like">
                                <div class="like like-wrap">
                                    <p>
                                        <a href="javascript:void(0)" id="like_post" onclick="likePost({{$post->id}})">
                                            <?php
                                            if(!empty($post[ 'postUserLike' ])) {
                                            ?>
                                            <i class="fa fa-thumbs-up"></i>
                                            <?php } else { ?>
                                            <i class="fa fa-thumbs-o-up"></i>
                                            <?php } ?>
                                        </a>
                                        <span id="post_like_count"><?php echo count($post->postLike);?></span>
                                    </p>
                                </div>
                                <div class="unlike like-wrap">
                                    <p>
                                        <a href="javascript:void(0)" id="dislike_post" onclick="dislikePost({{$post->id}})">
                                            <?php
                                            if(!empty($post[ 'postUserUnLike' ])) {
                                            ?>
                                            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                            <?php } else { ?>
                                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                            <?php } ?>
                                        </a>
                                        <span id="post_dislike_count"><?php echo count($post->postUnLike);?></span>
                                    </p>
                                </div>
                            </div>
                            @if($post->idea_status == null && $currUser->role_id < 3)
                            <div class="post-btn-wrap">
                                <div class="post-btn approve">
                                    <a href="javascript:void(0)" class="ideaStatus" data-post-status="approve">@lang('label.Approve')</a>
                                </div>
                                <div class="post-btn deny">
                                    <a href="javascript:void(0)" class="ideaStatus" data-post-status="deny">@lang('label.Denied')</a>
                                </div>
                                <div class="post-btn ammend">
                                    <a href="javascript:void(0)" class="ideaStatus" data-post-status="amend">@lang('label.Modify')</a>
                                </div>
                            </div>
                            @else
                            
                                @if($post->idea_status == null)
                                    <div class="approved">
                                        <p><span>@lang('label.ActionPending')</span></p>
                                    </div>
                                @else
                                    @php
                                        $ideaStatus = '';
                                        if($post->idea_status === 'approve')
                                            $ideaStatus = __('label.Approved');
                                        else if($post->idea_status === 'deny')
                                            $ideaStatus = __('label.Denied');
                                        else if($post->idea_status === 'amend')
                                            $ideaStatus = __('label.Modified');
                                    @endphp
                                    <div class="approved">
                                        <p>{{ $ideaStatus }} @lang('label.By'):<span>{{ $post->ideaUser->name }}</span></p>
                                    </div>
                                @endif
                            @endif
                            <!-- <hr class="border-in-hr">-->
                            <form id="post-form" class="post-form">
                            {{--<form name="post_comment_form" class="post-form" id="post_comment_form" method="post" action="{{url('savecomment',$post->id)}}">--}}
                                {{ csrf_field() }}
                                <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
                                {{--<div class="field-group comment">--}}
                                    {{--<textarea placeholder="Leave a comment here"></textarea>--}}
                                {{--</div>--}}
                                {{--<div class="field-group checkbox-btn">--}}
                                    {{--<div class="pull-left">--}}
                                        {{--<input value="Submit" class="st-btn" type="submit">--}}
                                    {{--</div>--}}
                                    {{--<div class="pull-left">--}}
                                        {{--<label class="check">Post as Anonymous--}}
                                            {{--<input type="checkbox">--}}
                                            {{--<span class="checkmark"></span>--}}
                                        {{--</label>--}}
                                    {{----}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </form>
                        
                        </div>
                        <!-- <hr class="border-in-hr">-->
                       
                            <?php
                            /*
                              <div class="container">
                             <div class="row">
                                
                                <div class="col-sm-2 user-image">
                                    <div class="img-wrap">
                                        <img alt="post user" src="assets/img/post-userone.PNG">
                                    </div>
                                    <a href="#">Follow</a>
                                
                                </div>
                                <div class="col-sm-10 user-rply">
                                    <div class="post-inner-reply">
                                        <div class="pull-left post-user-nam">
                                            <h3>Chester Bennigton</h3>
                                            <p>- on 24th Sep 2017</p>
                                        </div>
                                        <div class="pull-right post-reply-pop">
                                            <div class="options">
                                                <div class="fmr-10">
                                                    <a href="" class="set-warning">w</a>
                                                    <a href="" class="set-edit">e</a>
                                                    <a href="" class="set-alarm">a</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-12">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                    </p>
                                    <div class="rply-box">
                                        <div class="rply-count">
                                            <img alt="post-like" src="assets/img/like.png"><p>08</p>
                                        </div>
                                        <div class="rply-count">
                                            <a href="#myModal" data-toggle="modal" ><img src="assets/img/post-rply.png" alt="post-rply"> </a>
                                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button aria-hidden="true" data-dismiss="modal" class="desktop-close" type="button"></button>
                                                            <h4 class="modal-title">Report Comment</h4>
                                                        </div>
                                                        <form method="post" class="common-form">
                                                            <div class="form-group">
                                                                <label>Message To Author:</label>
                                                                <textarea type="text" placeholder="Type here"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="btn-wrap-div">
                                                                    <input class="st-btn" type="submit" value="Submit">
                                                                    <input value="Cancel" class="st-btn" aria-hidden="true" data-dismiss="modal" type="reset">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <p>4</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-sm-2 user-image">
                                    <div class="img-wrap">
                                        <img alt="post user" src="assets/img/post-usertwo.PNG">
                                    </div>
                                    <a href="#">Follow</a>
                                
                                </div>
                                <div class="col-sm-10 user-rply">
                                    <div class="post-inner-reply">
                                        <div class="pull-left post-user-nam">
                                            <h3>Ashley Crawford</h3>
                                            <p>- on 24th Sep 2017</p>
                                        </div>
                                        <div class="pull-right post-reply-pop">
                                            <div class="options">
                                                <div class="fmr-10">
                                                    <a class="set-warning" href="">w</a>
                                                    <a class="set-edit" href="">e</a>
                                                    <a class="set-alarm" href="">a</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-12">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                    </p>
                                    <div class="rply-box">
                                        <div class="rply-count">
                                            <img src="assets/img/like.png" alt="post-like"><p>08</p>
                                        </div>
                                        <div class="rply-count">
                                            <a href="#myModal" data-toggle="modal" ><img src="assets/img/post-rply.png" alt="post-rply"> </a>
                                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button aria-hidden="true" data-dismiss="modal" class="desktop-close" type="button"></button>
                                                            <h4 class="modal-title">Report Comment</h4>
                                                        </div>
                                                        <form method="post" class="common-form">
                                                            <div class="form-group">
                                                                <label>Message To Author:</label>
                                                                <textarea type="text" placeholder="Type here"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="btn-wrap-div">
                                                                    <input class="st-btn" type="submit" value="Submit">
                                                                    <input value="Cancel" class="st-btn" aria-hidden="true" data-dismiss="modal" type="reset">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <p>4</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            */
                            ?>
                    
                    </div>
                    <div class="col-sm-4" id="post-detail-right">
                        <div class="category">
                            <h2>@lang('label.adGroup')</h2>
                            <div class="idea-grp post-category">
                                @if(count($post_group) > 0)
                                    @foreach($post_group as $grp)
                                        <?php 
                                            if(!empty($grp->group_image)) {
                                                $group_image = GROUP_PATH.$grp->group_image;
                                            } else {
                                                $group_image = DEFAULT_GROUP_IMAGE;
                                            }
                                        ?>        
                                        <div class="member-wrap">
                                            <div class="member-img">
                                                <img src="{{ asset($group_image) }}" alt="no">
                                            </div>
                                            <div class="member-details">
                                                <?php
                                                    //if ($group->group_owner == Auth::user()->id || Auth::user()->role_id == 1) {
                                                        $group_link = route('group.edit',Helpers::encode_url($grp->id));
                                                    /*} else {
                                                        $group_link = route('editGroup',Helpers::encode_url($group->id));
                                                    }*/
                                                ?>
                                                <a href="{{$group_link}}"><h3 class="text-12">{{ $grp->group_name }}</h3></a>
                                                <p class="text-10">@lang('label.Members'): <span>{{ $grp->groupUsersCount->cnt }}</span></p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="member-wrap">
                                            <p class="text-12">@lang('label.Nogroupselected')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="category">
                            <h2>@lang('label.Tags')</h2>
                            <div class="post-circle post-category">
                                 @if(count($post->postTag) > 0)
                                @foreach($post->postTag as $tag)
                                    <a href="{{ route('tag',Helpers::encode_url($tag->tag_id)) }}"> {{ $tag->tag->tag_name }}</a>
                                @endforeach
                                @else
                                    <p class="text-12">@lang('label.Notagsselected')</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="category">
                            <h2>@lang('label.SimilarPosts')</h2>
                            <div class="post-links">
                                <?php
                                    if(!empty($similar_post) && count($similar_post) > 0) {
                                        foreach($similar_post as $similar) {
                                ?>
                                <a href="{{route('viewpost', Helpers::encode_url($similar->id))}}" class="profanity">{{$similar->post_title}}</a>
                                <?php 
                                        }
                                    } else {
                                ?>
                                <a href="javascript:void(0)">@lang('label.Nosimilarpostfound')</a>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                        {{--Uploaded files--}}
                        {{--<div class="category">--}}
                            {{--<h2>Uploaded Files</h2>--}}
                            {{--<div class="idea-grp post-category">--}}
                                {{----}}
                                {{--<div class="member-wrap files-upload">--}}
                                    {{--<div class="member-img">--}}
                                        {{--<img alt="no" src="assets/img/uploadfiles1.PNG">--}}
                                    {{--</div>--}}
                                    {{--<div class="member-details">--}}
                                        {{--<h3 class="text-12">Sales Report.Pdf</h3>--}}
                                        {{--<p class="text-10">Uploaded By: <a href="#">Jason Mcdowney</a></p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="member-wrap files-upload">--}}
                                    {{--<div class="member-img">--}}
                                        {{--<img alt="no" src="assets/img/uploadfiles2.PNG">--}}
                                    {{--</div>--}}
                                    {{--<div class="member-details">--}}
                                        {{--<h3 class="text-12">Managament List.jpeg</h3>--}}
                                        {{--<p class="text-10">Uploaded By: <a href="#">Jason Mcdowney</a></p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="member-wrap files-upload">--}}
                                    {{--<div class="member-img">--}}
                                        {{--<img alt="no" src="assets/img/uploadfiles3.PNG">--}}
                                    {{--</div>--}}
                                    {{--<div class="member-details">--}}
                                        {{--<h3 class="text-12">Attendance</h3>--}}
                                        {{--<p class="text-10">Uploaded By: <a href="#">Jason Mcdowney</a></p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="member-wrap files-upload">--}}
                                    {{--<div class="member-img">--}}
                                        {{--<img alt="no" src="assets/img/uploadfiles4.PNG">--}}
                                    {{--</div>--}}
                                    {{--<div class="member-details">--}}
                                        {{--<h3 class="text-12">Expense Sheet.Xlxs</h3>--}}
                                        {{--<p class="text-10">Uploaded By:<a href="#">Jason Mcdowney</a></p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    
                    </div>
                
                </div>
            </div>
        </div>
    </div> <!-- container -->
    
@stop
@push('javascripts')
<script type="text/javascript">
    function deletepost(id) {
        swal({
            title: "{{__('label.adAre you sure')}}",
            text: "{{__('label.WarningPost')}}",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            var token = '<?php echo csrf_token() ?>';
            var formData = {post_id : id, _token : token};
            $.ajax({
                url: SITE_URL+'/'+LANG  + '/deletepost',//"{{ route('post.destroy'," + id + ") }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res.status == 1) {
                        ajaxResponse('success',res.msg);
                        //location.reload();
                        window.location.href = SITE_URL+'/'+LANG + '/post';
                    }
                    else {
                        ajaxResponse('error',res.msg);
                    }
                }
            });
        });
    }
    function reportPostFlagged() {
        if($('#post_flagged_form').valid() == 1) {
            var reason = $('#post_message_autor').val();
            var post_id = {{$post['id']}};
            var user_id = {{Auth::user()->id}};
            var _token = CSRF_TOKEN;
            formData = {post_id:post_id,user_id:user_id,reason:reason,_token};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL+'/'+LANG  + '/post_flagged',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#spinner").hide();
                    res = JSON.parse(response);
                    if (res.status == 1) {
                        ajaxResponse('success',res.msg);
                        window.location.href = SITE_URL + '/post';
                    } else {
                        ajaxResponse('error',res.msg);
                    }
                },
                error: function(e) {
                    swal("Error", e, "error");
                }
            });
        }
    }
    function openFlagComment(comment_id,user_id) {
        $('#comment_message_autor').val('');
        $('#comment_flagged_id').val(comment_id);
        $('#comment_user_id').val(user_id);
        $('#flaggedComment').modal('show');
    }
    function reportCommentFlagged() {
        if($('#comment_flagged_form').valid() == 1) {
            var comment_id = $('#comment_flagged_id').val();
            var user_id  = $('#comment_user_id').val();
            var comment_message_autor = $('#comment_message_autor').val();
            var flag_by = {{Auth::user()->id}};
            var _token = CSRF_TOKEN;
            formData = {comment_id:comment_id,user_id:user_id,reason:comment_message_autor,flag_by:flag_by,_token};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL+'/'+LANG  + '/comment_flagged',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#spinner").hide();
                    res = JSON.parse(response);
                    if (res.status == 1) {
                        ajaxResponse('success',res.msg);
                        $('#flaggedComment').modal('hide');
                        //window.location.href = SITE_URL + '/post';
                        //location.reload();
                        //$('#comment_text_'+id).attr('readonly',true);
                        //$('#update_comment_'+id).css('display','none');
                    } else {
                        ajaxResponse('error',res.msg);
                    }
                },
                error: function(e) {
                    swal("Error", e, "error");
                }
            });
        }
    }
</script>
@endpush