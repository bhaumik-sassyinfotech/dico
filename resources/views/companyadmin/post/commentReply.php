<!-- Comment Box start -->
<form name="comment_replybox_form" id="comment_replybox_form" method="post" class="form-horizontal row-border  profile-page">
<?php
if (!empty($comment['commentReply'])) {
    foreach ($comment['commentReply'] as $commentReply) {
        if (!empty($commentReply['commentReplyUser'])) {
            $commentUser = $commentReply['commentReplyUser'];
            if (!empty($commentUser->profile_image) && $commentReply['is_anonymous'] == 0) {
                $profile_image = 'public/uploads/profile_pic/' . $commentUser->profile_image;
            } else {
                $profile_image = DEFAULT_PROFILE_IMAGE;
            }
            ?>
            <div class="row">
                <div class="col-sm-2 user-image">
                    <div class="img-wrap">
                        <img alt="post user" src="<?php echo asset($profile_image);?>" id="profile"/>
                    </div>
                    <?php
                    $comment_id = Helpers::encode_url($commentUser->id);
                    if (!empty($commentUser['followers']) && count($commentUser['followers']) > 0 && $commentUser->id != Auth::user()->id) {
                        if ($commentUser['followers'][0]->status == 1) {
                            ?>
                            <a href="<?php echo route('view_profile',$comment_id);?>" class="btn btn-primary" >@lang('label.Unfollow')</a>
                            <?php
                        } else {
                            ?>
                            <a href="<?php echo route('view_profile',$comment_id);?>" class="btn btn-primary" >@lang('label.Follow')</a>
                            <?php
                        }
                    } else if ($commentUser->id != Auth::user()->id) {
                        ?>
                        <a href="<?php echo route('view_profile',$comment_id);?>" class="btn btn-primary" >@lang('label.Follow')</a>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-10 user-rply">
                    <div class="post-inner-reply"> 
                        <div class="pull-left post-user-nam">
                            <h3 class="text-12"><?php
                                if ($commentReply['is_anonymous'] == 0) { ?>
                                <a href="{{route('view_profile', Helpers::encode_url($commentUser['id']))}}"><?php echo $commentUser['name'];?></a>
                                <?php } else {
                                    echo __('label.Anonymous');
                                }
                                ?></h3>
                            <p>- @lang('label.on') <?php echo date(DATE_FORMAT, strtotime($commentReply['created_at'])); ?></p>
                        </div>
                        <div class="pull-right post-reply-pop">
                            <div class="options">
                                <div class="fmr-10">
                                    <?php if ($commentUser['id'] == Auth::user()->id) { ?>
                                    <a class="set-edit" href="javascript:void(0)" onclick="editCommentReply(<?=$commentReply['id']?>);">e</a>
                                    <a class="set-alarm" href="<?php echo route('deletecommentReply',$commentReply['id']);?>">a</a>
                                    <?php } ?>
                                </div>
                            </div>    
                        </div> 
                    </div>
                    <p class="profanity" id="comment_reply_text_disp_<?=$commentReply['id']?>"><?php echo $commentReply['comment_reply']; ?></p>
                    <textarea name="comment_reply_text" id="comment_reply_text_<?= $commentReply['id'] ?>" readonly="" class="text-12 textarea-width" style="display: none;"><?php echo $commentReply['comment_reply']; ?></textarea>
                    <div class="btn-wrap-div">
                        <input type="button" name="update_comment_reply" id="update_comment_reply_<?=$commentReply['id'] ?>" value="@lang('label.Save')" class="st-btn" onclick="updateCommentReply(<?= $commentReply['id'] ?>)" style="display: none;"/>
                        <input type="button" name="cancel_comment_reply" id="cancel_comment_reply_<?=$commentReply['id']?>" value="@lang('label.Cancel')" class="btn btn-secondary btn-st" onClick=" this.form.reset();closeCommentReply(<?=$commentReply['id']?>)" style="display: none;"/>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
</form>
<!-- Comment Box end -->
