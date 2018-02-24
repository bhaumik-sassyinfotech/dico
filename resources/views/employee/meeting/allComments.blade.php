<!-- Comment Box start -->
<?php
if(!empty($meeting->meetingComment)) {
    foreach($meeting->meetingComment as $comment) { 
    ?>
    <div class="row" id="commentreply_{{$comment['id']}}">
        <div class="col-sm-2 user-image">
            <div class="img-wrap">
                <?php
                    $profile_img = '';
                    if(empty($comment->commentUser->profile_image))
                        $profile_img = DEFAULT_PROFILE_IMAGE;
                    else
                        $profile_img = PROFILE_PATH.$comment->commentUser->profile_image;
                ?>
                <img alt="post user" src="{{ asset($profile_img) }}">
            </div>
        <?php
            $commentUser = $comment->commentUser;
            $comment_id = Helpers::encode_url($commentUser->id);

            if (!empty($commentUser->following && count($commentUser->following) > 0 && $commentUser->id != Auth::user()->id))
            {
                if ($commentUser['following'][0]->status == 1)
                { ?>
                    <a href="{{ route('view_profile',$comment_id) }}">@lang("label.Unfollow")</a>
                    <?php
                } else
                { ?>
                    <a href="{{ route('view_profile',$comment_id) }}"
                    >@lang("label.Follow")</a>
                  <?php
                }
            } else if ($commentUser->id != Auth::user()->id)
            { ?>
                    <a href="{{ route('view_profile',$comment_id) }}"
                    >@lang("label.Follow")</a>
                <?php
            }
            ?>


        </div>
        <div class="col-sm-10 user-rply">
            <div class="post-inner-reply">
                <div class="pull-left post-user-nam">
                    <a href="{{url('view_profile', Helpers::encode_url($meeting->meetingCreator->id))}}">{{ $comment->commentUser->name }}</a>
                    <p>- @lang("label.on") {{ date(DATE_FORMAT,strtotime($comment->created_at)) }}</p>
                </div>
                <div class="pull-right post-reply-pop">
                    <div class="options">
                        <div class="fmr-10">
                              @if($comment->commentUser->id == Auth::user()->id)
                                <a class="set-edit" href="javascript:void(0)" onclick="editComment('<?= 'popup_'.$comment['id'] ?>');">e</a>
                                <a class="set-alarm" href="{{ url('meeting/deleteMeetingComment/'.$comment->id) }}">a</a>
                              @endif
                        </div>
                    </div>
                </div>
            </div>
            <p class="profanity" id="comment_disp_popup_<?=$comment['id']?>"><?php echo $comment->comment_reply; ?></p>
                <textarea name="comment_text" id="comment_text_popup_<?=$comment['id']?>" readonly="" class="text-12 textarea-width" style="display: none;"><?php echo  $comment->comment_reply; ?></textarea>
                <div class="btn-wrap-div">
                    <input type="button" name="update_comment" id="update_comment_popup_<?=$comment['id']?>" value="@lang('label.Save')" class="st-btn" onclick="updateComment(<?=$comment['id']?>,'<?= 'popup_'.$comment['id'] ?>')" style="display: none;"/>
                    <input type="button" name="cancel_comment" id="cancel_comment_popup_<?=$comment['id']?>" value="@lang('label.Cancel')" class="btn btn-secondary btn-st" onClick="this.form.reset();closeComment('<?= 'popup_'.$comment['id'] ?>')" style="display: none;"/>
                </div>    
            <div class="rply-box">
                <div class="rply-count like">
                    <a href="javascript:void(0)" id="like_comment_popup_{{$comment['id']}}" onclick="likeAttactmentComment({{$comment['id']}},'<?='popup_'.$comment['id']?>');">
                    <?php
                        if (!empty($comment['commentUserLike'])) {
                    ?>
                        <i class="fa fa-thumbs-up"></i>
                    <?php } else {?>
                        <i class="fa fa-thumbs-o-up"></i>
                    <?php }?>
                    </a>
                    <span id="comment_like_count_popup_{{$comment['id']}}"><?php echo count($comment['commentLike']); ?></span>
                </div>
                <div class="rply-count dislike">
                    <a href="javascript:void(0)" id="dislike_comment_popup_{{$comment['id']}}" onclick="dislikeAttachmentComment({{$comment['id']}},'<?='popup_'.$comment['id']?>');">
                    <?php
                        if (!empty($comment['commentUserDisLike'])) {
                    ?>
                        <i class="fa fa-thumbs-down"></i>
                    <?php } else {?>
                        <i class="fa fa-thumbs-o-down"></i>
                    <?php }?>
                    </a>
                    <span id="comment_dislike_count_popup_{{$comment['id']}}"><?php echo count($comment['commentDisLike']); ?></span>
                </div>
                <div class="rply-count">
                <a href="javascript:void(0);" onclick="openCommentReplyBox({{$comment['id']}})" id="modalComment"><i class="fa fa-reply" aria-hidden="true"></i></a>
                <span><?php echo count($comment['commentReply']); ?></span>
                </div>    
            </div>
        </div>
    </div>
<?php } }
?>
<!-- Comment Box end -->
