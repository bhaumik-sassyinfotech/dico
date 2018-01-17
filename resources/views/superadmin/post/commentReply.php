<!-- Comment Box start -->
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
                    if (!empty($commentUser['following']) && count($commentUser['following']) > 0 && $commentUser->id != Auth::user()->id) {
                        if ($commentUser['following'][0]->status == 1) {
                            ?>
                            <a href="<?php echo url('/view_profile/'.$comment_id);?>" class="btn btn-primary" >Unfollow</a>
                            <?php
                        } else {
                            ?>
                            <a href="<?php echo url('/view_profile/'.$comment_id);?>" class="btn btn-primary" >Follow</a>
                            <?php
                        }
                    } else if ($commentUser->id != Auth::user()->id) {
                        ?>
                        <a href="<?php echo url('/view_profile/'.$comment_id);?>" class="btn btn-primary" >Follow</a>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-10 user-rply">
                    <div class="post-inner-reply"> 
                        <div class="pull-left post-user-nam">
                            <h3 class="text-12"><?php
                                if ($commentReply['is_anonymous'] == 0) {
                                    echo $commentUser['name'];
                                } else {
                                    echo "Anonymous";
                                }
                                ?></h3>
                            <p>- on <?php echo date(DATE_FORMAT, strtotime($commentUser['created_at'])); ?></p>
                        </div>
                        <div class="pull-right post-reply-pop">
                            <div class="options">
                                <div class="fmr-10">
                                    <?php if ($commentUser['id'] == Auth::user()->id) { ?><a class="set-alarm" href="<?php echo url('/deletecommentReply',$commentReply['id']);?>">a</a><?php } ?>
                                </div>
                            </div>    
                        </div> 
                    </div>
                    <textarea name="comment_text" id="comment_text_<?= $commentReply['id'] ?>" readonly="" class="text-12 textarea-width"><?php echo $commentReply['comment_reply']; ?></textarea>
                    <div class="btn-wrap-div">
                        <input type="button" name="update_comment" id="update_comment_<?=$commentReply['id'] ?>" value="Save" onclick="updateComment(<?= $commentReply['id'] ?>)" style="display: none;"/>
                        <input type="button" name="cancel_comment" id="cancel_comment_<?=$commentReply['id']?>" value="Cancel" class="btn btn-secondary" onClick=" this.form.reset();closeComment(<?=$commentReply['id']?>)" style="display: none;"/>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
<!-- Comment Box end -->
