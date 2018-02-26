@extends('template.default')
<title>@lang("label.DICOPost")</title>
@section('content')

<div id="page-content" class="idea-details challenges idea-page">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ route('/home') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('post.index') }}">@lang("label.adPost")</a></li>
                <li class="active">@lang("label.ViewPost")</li>
            </ol>
            <h1 class="icon-mark tp-bp-0">@lang("label.Challenge")</h1>
            <hr class="border-out-hr">
        </div>
        <div class="container">
            <div class="row">
                <div id="post-detail-left" class="col-sm-8">
                    <!-- START TITLE -->
                    <div class="group-wrap">
                        <div class="pull-left">
                            <h3 class="profanity">{{$post->post_title}}</h3>
                            <div class="user-wrap">
                                <div class="user-img">
                                    @if(empty($post->postUser->profile_image))
                                        <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                                    @else
                                        <img src="{{ asset(PROFILE_PATH.$post->postUser->profile_image) }}">
                                    @endif
                                </div>
                                <p class="user-icon">-<?php if ($post['is_anonymous'] == 0) { ?>
                                    <a href="{{route('view_profile', Helpers::encode_url($post->postUser->id))}}">{{$post->postUser->name}}</a>
                                    <?php } else { echo __("label.Anonymous"); } ?> <span>@lang("label.on") {{date(DATE_FORMAT,strtotime($post->created_at))}}</span></p>      
                            </div>    
                        </div> 

                        <div class="pull-right">
                            <div class="options">
                                <div class="fmr-10">
                                    <a class="set-alarm" href="">a</a>
                                    <?php
if ($post['user_id'] == Auth::user()->id) {
	?>
                                    <?php /*<a class="set-edit" href="{{url('edit',Helpers::encode_url($post->id))}}">w</a>*/?>
                                    <a class="set-edit" href="{{route('post.edit',Helpers::encode_url($post->id))}}">e</a>
                                    <a class="set-delete" href="javascript:void(0);" onclick="deletepost({{$post->id}})">w</a>
                                    <?php
                                        }
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
                    <!-- END TITLE -->
                    <div class="post-wrap-details">
                        <p class="text-12 profanity">{{$post->post_description}}</p>
                        <div class="post-details-like">
                            <div class="like like-wrap"><a href="javascript:void(0)" id="like_post" onclick="likePost({{$post['id']}})">
                                <?php
if (!empty($post['postUserLike'])) {
	?>
                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                <?php } else {?>
                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                <?php }?></a>
                                <p id="post_like_count"><?php echo count($post['postLike']); ?></p></div>
                            <div class="unlike like-wrap"><a href="javascript:void(0)" id="dislike_post" onclick="dislikePost({{$post['id']}})">
                                    <?php
if (!empty($post['postUserDisLike'])) {
	?>
                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                    <?php } else {?>
                                        <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                    <?php }?>
                                </a><p id="post_dislike_count"><?php echo count($post['postDisLike']); ?></p></div>
                        
                            <?php /*<div class="comment like-wrap"><a href="javascript:void(0)">
                                <?php
                                if (!empty($post['postComment'])) {
                                    ?>
                                    <i class="fa fa-comment"></i>
                                <?php } else { ?>
                                    <i class="fa fa-comment-o"></i>
                                <?php } ?>
                                </a>
                                <span><?php echo $post['post_comment_count']; ?></span>
                            </div>*/?>
                        </div>
                    </div>    
                    <hr class="border-in-hr">
                    <form class="post-form" name="post_comment_form" id="post_comment_form" method="post" action="{{route('savecomment',$post->id)}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="post_id" id="post_id" value="{{$post['id']}}">
                        <div class="field-group comment">
                            <textarea name="comment_text" id="comment_text" class="form-control autosize" placeholder="@lang('label.Leavecommenthere')" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 71.9792px;"></textarea>
                        </div>
                        <?php /*<div class="field-group files">
                                    <input disabled="disabled" placeholder="upload file" id="uploadFile">
                                    <label class="custom-file-input">
                                        <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                    </label>
                        </div>*/?>

                        <div class="field-group checkbox-btn">
                            <?php
                                if(count($post['company']) > 0 && $post['company']->allow_anonymous == 1) {
                            ?>
                            <div class="pull-left">
                                <label class="check">@lang('label.PostasAnonymous') <input type="checkbox" name="is_anonymous" id="is_anonymous"><span class="checkmark"></span></label>
                            </div>
                            <?php } ?>
                            <div class="pull-right">
                                <input type="submit" name="submit" id="submit" value="@lang('label.Submit')" class="st-btn">
                            </div>
                        </div>
                    </form>
                    <br>
                    <hr>
                    <!-- Comment Box start -->
                    <div class="container">
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="flaggedComment" class="modal fade warning-flag" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="desktop-close" type="button"></button>
                                        <h4 class="modal-title">@lang('label.RequestflaggedComment')</h4>
                                    </div>
                                    <form method="post" class="common-form" name="comment_flagged_form" id="comment_flagged_form">
                                        <input type="hidden" name="comment_flagged_id" id="comment_flagged_id">
                                        <input type="hidden" name="comment_user_id" id="comment_user_id">
                                     <div class="form-group">
                                        <label>@lang('label.MessageToAuthor'):</label>
                                        <textarea type="text" placeholder="@lang('label.Typehere')" id="comment_message_autor" name="comment_message_autor"></textarea>
                                     </div>
                                     <div class="form-group">
                                         <div class="btn-wrap-div">
                                             <input class="st-btn" type="button" value="@lang('label.Submit')" name="submit" id="submit" onclick="reportCommentFlagged();">
                                              <input value="@lang('label.Cancel')" class="st-btn" aria-hidden="true" data-dismiss="modal" type="reset">
                                         </div>
                                     </div>
                                    </form>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div>
                        <form name="commentbox_form" id="commentbox_form" class="form-horizontal row-border">
                        <?php
                            if (!empty($post['postComment'])) {
                                foreach ($post['postComment'] as $postComment) {
                                    if (!empty($postComment['commentUser'])) { 
                                        $commentUser = $postComment['commentUser'];
                                        if (!empty($commentUser->profile_image) && $postComment['is_anonymous'] == 0) {
                                            $profile_image = PROFILE_PATH . $commentUser->profile_image;
                                        } else {
                                            //$profile_image = 'public/assets/demo/avatar/jackson.png';
                                            $profile_image = DEFAULT_PROFILE_IMAGE;
                                        }
                        ?>
                        <div class="row" id="commentreply_{{$postComment['id']}}">

                            <div class="col-sm-2 user-image">
                                <div class="img-wrap">
                                    <img alt="post user" src="{{asset($profile_image)}}" id="profile"/>
                                </div>
                                <?php
                        $comment_id = Helpers::encode_url($commentUser->id);
			if (!empty($commentUser['followers']) && count($commentUser['followers']) > 0 && $commentUser->id != Auth::user()->id) {
				if ($commentUser['followers'][0]->status == 1) {
					?>
                                            <a href="{{ route('view_profile',$comment_id) }}" class="btn btn-primary" >@lang('label.Unfollow')</a>
                                            <?php
} else {
					?>
                                            <a href="{{ route('view_profile',$comment_id) }}" class="btn btn-primary" >@lang('label.Follow')</a>
                                            <?php
}
			} else if ($commentUser->id != Auth::user()->id) {
				?>
                                        <a href="{{ route('view_profile',$comment_id) }}" class="btn btn-primary" >@lang('label.Follow')</a>
                                        <?php
}
			?>
                            </div>
                            <div class="col-sm-10 user-rply">
                                <div class="post-inner-reply">
                                    <div class="pull-left post-user-nam">
                                        <h3 class="text-12"><?php if ($postComment['is_anonymous'] == 0) { ?>
                                            <a href="{{route('view_profile', Helpers::encode_url($commentUser['id']))}}">{{$commentUser['name']}}</a>
                                         <?php } else { echo __('label.Anonymous'); } ?></h3>
                                        <p>- @lang('label.on') <?php echo date(DATE_FORMAT, strtotime($postComment['created_at'])); ?></p>
                                    </div>
                                    <div class="pull-right post-reply-pop">
                                        <div class="options">
                                            <div class="star-wrap">
                                                <?php
$active = "";
			if ($postComment['is_correct'] == 0) {
				$active = "disactive";
			} else {
				$active = "active";
			}
			?>
                                                <p id="icon_{{$postComment['id']}}" class="<?php echo $active; ?>">
                                                    <?php //if ($commentUser['id'] == Auth::user()->id) {?>
                                                    <?php if ($commentUser['id'] == Auth::user()->id || $commentUser['role_id'] > Auth::user()->role_id || count(Auth::user()->group) > 0) { ?>
                                                        <a id="solution_{{$postComment['id']}}" href="javascript:void(0)" onclick="markSolution({{$postComment['id']}}, {{$commentUser['id']}}, {{$post['id']}})">@lang('label.Correct')</a>
                                                    <?php } else {?>
                                                        @lang('label.Correct')
                                                    <?php }?>
                                                </p>
                                                <?php /*if ($post['user_id'] == Auth::user()->id) { ?>
			<?php
			if ($postComment['is_correct'] == 1) { ?>
			<i class="fa fa-star" id="icon_{{$postComment['id']}}" aria-hidden="true"></i><?php
			} else { ?>
			<i class="fa fa-star-o" id="icon_{{$postComment['id']}}" aria-hidden="true"></i><?php
			} ?>
			<a id="solution_{{$postComment['id']}}" href="javascript:void(0)" onclick="markSolution({{$postComment['id']}}, {{$commentUser['id']}}, {{$post['id']}})">
			Solution</a>
			<?php
			} else {
			if ($postComment['is_correct'] == 1) {
			?><i class="fa fa-star" aria-hidden="true"></i>Solution<?php
			}
			}*/
			?>
                                            </div>
                                            <div class="fmr-10">
                                                <?php    
                                                if(!empty($postComment['commentFlagged'])) {
                                                    if($postComment['commentFlagged']['flag_by'] == Auth::user()->id) {
                                                ?>
                                                <a class="set-warning no-fontawesome"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                                <?php } else { ?>
                                                <a class="set-warning" href="javascript:void(0)" onclick="openFlagComment({{$postComment['id']}}, {{$commentUser['id']}})">w</a>
                                                    <?php } } else { ?>
                                                <a class="set-warning" href="javascript:void(0)" onclick="openFlagComment({{$postComment['id']}}, {{$commentUser['id']}})">w</a>
                                                <?php } ?>
                                                <?php if ($commentUser['id'] == Auth::user()->id) { ?>

                                                <a class="set-edit" href="javascript:void(0)" onclick="editComment(<?=$postComment['id']?>);">e</a>
                                                <a class="set-alarm" href="{{route('deletecomment',$postComment['id'])}}">a</a>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="profanity" id="comment_disp_<?=$postComment['id']?>"><?php echo $postComment['comment_text']; ?></p>
                                <textarea name="comment_text" id="comment_text_<?=$postComment['id']?>" readonly="" class="text-12 textarea-width" style="display: none;"><?php echo $postComment['comment_text']; ?></textarea>
                                <div class="btn-wrap-div">
                                    <input type="button" name="update_comment" id="update_comment_<?=$postComment['id']?>" value="Save" class="st-btn" onclick="updateComment(<?=$postComment['id']?>,<?=$postComment['id']?>)" style="display: none;"/>
                                    <input type="button" name="cancel_comment" id="cancel_comment_<?=$postComment['id']?>" value="Cancel" class="btn btn-secondary" onClick=" this.form.reset();closeComment(<?=$postComment['id']?>)" style="display: none;"/>
                                </div>
                                <div class="rply-box">
                                    <div class="rply-count like">

                                        <a href="javascript:void(0)" id="like_comment_{{$postComment['id']}}" onclick="likeComment({{$postComment['id']}},{{$postComment['id']}});" >
                                            <?php
if (!empty($postComment['commentUserLike'])) {
				?>
                                                <i class="fa fa-thumbs-up"></i>
                                            <?php } else {?>
                                                <i class="fa fa-thumbs-o-up"></i>
                                            <?php }?>
                                        </a><span id="comment_like_count_{{$postComment['id']}}"><?php echo count($postComment['commentLike']) ?></span>
                                        <!-- <img alt="post-like" src="assets/img/like.png"><p>08</p>-->
                                    </div> 
                                    <div class="rply-count dislike">

                                        <a href="javascript:void(0)" id="dislike_comment_{{$postComment['id']}}" onclick="dislikeComment({{$postComment['id']}},{{$postComment['id']}});" >
                                            <?php
if (!empty($postComment['commentUserDisLike'])) {
				?>
                                                <i class="fa fa-thumbs-down"></i>
                                            <?php } else {?>
                                                <i class="fa fa-thumbs-o-down"></i>
                                            <?php }?>
                                        </a>
                                        <span id="comment_dislike_count_{{$postComment['id']}}"><?php echo count($postComment['commentDisLike']); ?></span>
                                        <!-- <img alt="post-rply" src="assets/img/post-rply.png"> <p>04</p>-->
                                    </div> 
                                   <div class="rply-count">
                                       <a href="javascript:void(0);" onclick="openCommentReplyBox({{$postComment['id']}})"><i class="fa fa-reply" aria-hidden="true"></i></a>
                                       <span><?php echo count($postComment['commentReply']); ?></span>
                                   </div>
                                </div>
                        </div>
                        </div>
                                    <?php } 
                                         
                            } 
                                if($post['post_comment_count'] > COMMENT_DISPLAY_LIMIT) {
                            ?>
                            <div><a href="javascript:void(0)" data-toggle="modal" data-target="#LoadModal" onclick="allComments();">@lang('label.Viewallcomments')</a></div>
                                <?php }
                        } ?>
                            <div class="modal fade" id="LoadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">@lang('label.Viewallcomments')</h5>
                                      <button type="button" class="close-button-small" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"></span>
                                      </button>
                                    </div>
                                      <div class="modal-body" id="allcomments_box" style="height: 280px;overflow: auto;">
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('label.Close')</button>
                                      <!-- <button type="button" class="btn btn-primary">Save changes</button>-->
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </form>
                        <div id="myModalComment" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close-button-small" data-dismiss="modal"></button>
                                    <div id="commentReplyList"></div>
                                    <h4 class="modal-title">@lang('label.CommentHere')</h4>
                                </div>
                                <form name="reply_form" id="reply_form" class="form-horizontal row-border  profile-page">
                                    <div class="modal-body">
                                        <input type="hidden" id="commentId" name="commentId">
                                        <div class="row">
                                            <textarea name="comment_reply_text" id="comment_reply_text" class="form-control autosize" placeholder="@lang('label.Leavecommenthere')"></textarea>
                                        </div>
                                        <?php
                                            if(count($post['company']) > 0 && $post['company']->allow_anonymous == 1) {
                                        ?>
                                        <div class="row">
                                            <label class="checkbox-inline"><input type="checkbox" name="is_anonymous" id="is_anonymous">@lang('label.Anonymous')</label><br>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="comment_reply()">@lang('label.adSubmit')</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('label.Close')</button>
                                    </div>
                                </form>    
                            </div>

                        </div></div>
                    </div>
                    <!-- Comment Box end -->
                    </div>

                    <div class="col-sm-4" id="post-detail-right">
                    <!-- START RIGHT SIDEBAR -->
                        <div class="category">
                            <h2><span>@lang('label.adGroup')</span></h2>
                            <div class="idea-grp post-category">
                                    <?php
                                        if(!empty($post_group)) {
                                            foreach($post_group as $group) {
                                                if(!empty($group->group_image)) {
                                                    $group_image = GROUP_PATH.$group->group_image;
                                                } else {
                                                    $group_image = DEFAULT_GROUP_IMAGE;
                                                }
                                    ?>

                                    <div class="member-wrap">
                                        <div class="member-img">
                                            <img src="{{ asset($group_image)}}" alt="no">
                                        </div>
                                        <div class="member-details">
                                            <?php
                                                //if ($group->group_owner == Auth::user()->id || Auth::user()->role_id == 1) {
                                                    $group_link = route('group.edit',Helpers::encode_url($group->id));
                                                /*} else {
                                                    $group_link = route('editGroup',Helpers::encode_url($group->id));
                                                }*/
                                            ?>
                                            <a href="{{$group_link}}"><h3 class="text-12">{{$group->group_name}}</h3></a>
                                            <p class="text-10">@lang('label.Members'): <span>{{$group->groupUsersCount->cnt}}</span></p>
                                        </div>
                                    </div>
                                    <?php
}
} else {
	?>
                                        <div class="member-wrap">
                                            <p class="text-10">@lang('label.Nogroupselected')</p>
                                        </div>
                                    <?php
}
?>
                            </div>
                        </div>
                        <div class="category">
                            <h2>@lang('label.Tags')</h2>
                            <div class="post-circle post-category">
                                <?php
if (!empty($post->postTag) && count($post->postTag) > 0) {
	foreach ($post->postTag as $postTag) {
		?>
                                <a href="{{route('tag', Helpers::encode_url($postTag['tag']['id']))}}"> {{$postTag['tag']['tag_name']}}</a>
                                <?php 
                                        }
                                    } else { 
                                        ?>
                                        <p class="text-10">@lang('label.Notagsselected')</p>
                                <?php        
                                    }
                                ?>    
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
                        <?php
// if(!empty($post->postAttachment)) {
?>
                        <div class="category">
                            <h2>@lang('label.UploadedFiles')</h2>
                            <div class="wrap-name-upload">
                                <div class="select">
                                    <select id="slct" name="slct">
                                            <option>@lang('label.adName')</option>
                                            <option>@lang('label.Admin')</option>
                                            <option value="Super User">@lang('label.SuperUser')</option>
                                            <option value="Employee">@lang('label.Employee')</option>
                                    </select>
                                </div>
                                <div class="upload-btn-wrapper">
                                    <form name="uploadfile" id="uploadfile" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="postId" id="postId" value="{{$post['id']}}">
                                        <button class="btn" id="uploadBtn">@lang('label.UploadFile')</button>
                                        <input name="file_upload" id="file_upload" type="file" onchange="uploadFile();">
                                    </form>
                                </div>
                            </div>
                            <div class="idea-grp post-category" id="postAttachment">
                                <?php
                                    //dd($post->postAttachment);
                                    if(!empty($post->postAttachment) && count($post->postAttachment) > 0) {
                                    foreach($post->postAttachment as $attachment) {
                                ?>
                                <div class="member-wrap files-upload">

                                    <div class="member-img">
                                        <img src="{{asset(DEFAULT_ATTACHMENT_IMAGE)}}" alt="no">
                                    </div>
                                    <div class="member-details">
                                        <h3 class="text-10">{{$attachment->file_name}}</h3>
                                        <p>@lang('label.UploadedBy'):<a href="{{route('view_profile', Helpers::encode_url($attachment->attachmentUser->id))}}">{{$attachment->attachmentUser->name}}</a></p>
                                    </div>
                                </div>    
                                <?php } }
                                    else {
                                        echo "<p>".__('label.Nofilesuploaded')."</p>";
                                    }
                                ?>
                            </div> 
                        </div>

                        <?php
// }
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
            $("#spinner").show();
            $.ajax({
                url: SITE_URL+'/'+LANG + '/deletepost',//"{{ route('post.destroy'," + id + ") }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    $("#spinner").hide();
                    var res = JSON.parse(response);
                    if (res.status == 1) {
                        ajaxResponse('success',res.msg);
                        //location.reload();
                        window.location.href = SITE_URL +'/'+LANG+ '/post';
                    }
                    else {
                        ajaxResponse('error',res.msg);
                    }
                }
            });
        });
    }
    function markSolution(commentid, userid, postid)
    {
        swal({
        title: "{{__('label.adAre you sure')}}",
                text: "{{__('label.WarningCorrect')}}",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: true,
                showLoaderOnConfirm: true
        }, function () {
        var _token = CSRF_TOKEN;
        var formData = {comment_id:commentid, user_id:userid, post_id:postid, _token};
        $("#spinner").show();
            $.ajax({
            url: SITE_URL +'/'+LANG+ '/comment_solution',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $("#spinner").hide();
                        var res = JSON.parse(response);
                        var html = "";
                        if (res.status == 1) {
                            if($('#icon_'+commentid).hasClass('disactive')) {
                                $('#icon_'+commentid).removeClass('disactive');
                                $('#icon_'+commentid).addClass('active');
                                ajaxResponse('success',res.msg);
                            }
                        } else if (res.status == 2) {
                            if($('#icon_'+commentid).hasClass('disactive')) {
                                $('#icon_'+commentid).removeClass('disactive');
                                $('#icon_'+commentid).addClass('active');
                            }
                            ajaxResponse('error',res.msg);
                        } else {
                            if($('#icon_'+commentid).hasClass('active')) {
                                $('#icon_'+commentid).removeClass('active');
                                $('#icon_'+commentid).addClass('disactive');
                            }
                        ajaxResponse('error',res.msg);
                        location.reload();
                    }
                    //$('#solution_' + commentid).before(html);
                    },error: function(e) {
                        swal("Error", e, "error");
                    }
            });
        });
    }
    function comment_reply() {
        //var commentid = $('#modalComment').attr('data-id');
        //alert($('#comment_replybox_form').valid());
        if($('#reply_form').valid() == 1) {
            var commentid = $('#commentId').val();
            var _token = CSRF_TOKEN;
            var post_id = $('#post_id').val();
            var comment_reply = $('#comment_reply_text').val();
            var anonymous = 0;
            var srno = $('#commentreply_' + commentid + ' .cmry:first').attr('id');
            //console.log(commentid, "::::", srno);
            if ($("#is_anonymous_" + commentid).is(':checked')) {
                anonymous = 1;
            } else {
                anonymous = 0;
            }
            var formData = {comment_id:commentid, comment_reply:comment_reply, post_id:post_id, is_anonymous:anonymous, srno:srno, _token};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL +'/'+LANG+ '/comment_reply',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#spinner").hide();
                    res = JSON.parse(response);
                     if (res.status == 1) {
                        ajaxResponse('success',res.msg);
                        location.reload();
                     } else {
                        ajaxResponse('error',res.msg);
                        //swal("Error", res.msg, "error");
                     }
                },
                error: function(e) {
                    swal("Error", e, "error");
                }
            });
        }
    }
    function updateComment(id,elementid) {
        if($('#commentbox_form').valid() == 1) {
            var comment = $('#comment_text_'+elementid).val();
            var _token = CSRF_TOKEN;
            formData = {id:id,comment:comment,_token};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL +'/'+LANG+ '/comment_update',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#spinner").hide();
                    res = JSON.parse(response);
                    if (res.status == 1) {
                        //swal("Success", res.msg, "success");
                        $('#comment_text_'+elementid).attr('readonly',true);
                        $('#comment_text_'+elementid).css('background-color','transparent');
                        $('#update_comment_'+elementid).css('display','none');
                        $('#cancel_comment_'+elementid).css('display','none');
                        ajaxResponse('success',res.msg);
                        location.reload();
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
    function updateCommentReply(id) {
        if($('#comment_replybox_form').valid() == 1) {
            var comment = $('#comment_reply_text_'+id).val();
            var _token = CSRF_TOKEN;
            formData = {id:id,comment:comment,_token};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL +'/'+LANG+ '/comment_reply_update',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#spinner").hide();
                    res = JSON.parse(response);
                    if (res.status == 1) {
                        //swal("Success", res.msg, "success");
                        $('#comment_reply_text_' + id).removeProp('readonly').slideUp('fast');
                        $('#update_comment_reply_' + id).css('display', 'none');
                        $('#comment_reply_text_'+id).css('background-color','transparent');
                        $("#comment_reply_text_disp_"+id).slideDown('fast');
                        $("#comment_reply_text_disp_"+id).html($('#comment_reply_text_' + id).val());
                        $('#cancel_comment_reply_'+id).css('display','none');
                        runProfanity();
                        /*$('#comment_reply_text_'+id).attr('readonly',true);
                        $('#comment_reply_text_'+id).css('background-color','transparent');
                        $('#update_comment_reply_'+id).css('display','none');
                        $('#cancel_comment_reply_'+id).css('display','none');*/
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
    function reportPostFlagged() {
        if($('#post_flagged_form').valid() == 1) {
            var reason = $('#post_message_autor').val();
            var post_id = {{$post['id']}};
            var user_id = {{Auth::user()->id}};
            var _token = CSRF_TOKEN;
            formData = {post_id:post_id,user_id:user_id,reason:reason,_token};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL+'/'+LANG+ '/post_flagged',
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
    function allComments() {
        var _token = CSRF_TOKEN;
        var post_id = $('#post_id').val();
        //formData = {post_id:post_id,offset:3,_token};
        formData = {post_id:post_id,offset:0,_token};
        $("#spinner").show();
        $.ajax({
            url: SITE_URL +'/'+LANG+ '/allComments',
            type: 'POST',
            data: formData,
            success: function(response) {
                res = JSON.parse(response);
                if(res.status == 1) {
                    $("#spinner").hide();
                    $('#allcomments_box').html(res.html);
                    runProfanity();
                } else {
                    ajaxResponse('error',res.msg);
                }
            },
            error: function(e) {
                swal("Error", e, "error");
            }
        });
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
                url: SITE_URL +'/'+LANG+ '/comment_flagged',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#spinner").hide();
                    res = JSON.parse(response);
                    if (res.status == 1) {
                        ajaxResponse('success',res.msg);
                        location.reload();
                        //$('#flaggedComment').modal('hide');
                        //window.location.href = SITE_URL + '/post';
                        //location.reload();
                        //$('#comment_text_'+id).attr('readonly',true);
                        //$('#update_comment_'+id).css('display','none');
                    } else {
                        ajaxResponse('error',res.msg);
                        location.reload();
                    }
                },
                error: function(e) {
                    swal("Error", e, "error");
                }
            });
        }
    }
    function openCommentReplyBox(commentid) {
        if(commentid != "") {
            var _token = CSRF_TOKEN;
            formData = {comment_id:commentid,_token};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL +'/'+LANG+ '/getCommentReply',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#spinner").hide();
                    var res = JSON.parse(response);
                    if(res.status == 1) {
                        $('#commentReplyList').html(res.html);
                        runProfanity();
                    } else {
                        ajaxResponse('error',res.msg);
                    }
                },
                error: function(e) {
                    swal("Error", e, "error");
                }
            });
            $('#reply_form')[0].reset();
            $('.error').html('');
            $('#myModalComment').modal('show');
            $('#commentId').val(commentid);
        }else {
            swal("Error", "{{__('label.commentnotfound')}}", "error");
        }
    }
</script>
@endpush