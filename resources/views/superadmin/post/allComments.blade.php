<!-- Comment Box start -->
<?php
if (!empty($post['postComment'])) {
    foreach ($post['postComment'] as $postComment) {
        if (!empty($postComment['commentUser'])) {
            $commentUser = $postComment['commentUser'];
            if (!empty($commentUser->profile_image) && $postComment['is_anonymous'] == 0) {
                $profile_image = 'public/uploads/profile_pic/' . $commentUser->profile_image;
            } else {
                $profile_image = DEFAULT_PROFILE_IMAGE;
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
                            <h3 class="text-12"><?php
                                if ($postComment['is_anonymous'] == 0) {
                                    echo $commentUser['name'];
                                } else {
                                    echo "Anonymous";
                                }
                                ?></h3>
                            <p>- on <?php echo date(DATE_FORMAT, strtotime($commentUser['created_at'])); ?></p>
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
                                        <?php if ($commentUser['id'] == Auth::user()->id) { ?>
                                            <a id="solution_{{$postComment['id']}}" href="javascript:void(0)" onclick="markSolution({{$postComment['id']}}, {{$commentUser['id']}}, {{$post['id']}})">Correct</a>
                                        <?php } else { ?>
                                            Correct  
            <?php } ?>    
                                    </p>
                                </div>
                                <div class="fmr-10">
                                    <a class="set-warning" href="#flaggedComment" data-toggle="modal">w</a>
                                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="flaggedComment" class="modal fade" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button aria-hidden="true" data-dismiss="modal" class="desktop-close" type="button"></button>
                                                    <h4 class="modal-title">Request flagged in the Post</h4>
                                                </div>
                                                <form method="post" class="common-form" name="post_flagged_form" id="post_flagged_form">
                                                    <div class="form-group">
                                                        <label>Message To Author:</label> 
                                                        <textarea type="text" placeholder="Type here" id="post_message_autor" name="post_message_autor"></textarea>
                                                    </div> 
                                                    <div class="form-group">
                                                        <div class="btn-wrap-div">
                                                            <input class="st-btn" type="button" value="Submit" name="submit" id="submit" onclick="reportPostFlagged();">
                                                            <input value="Cancel" class="st-btn" aria-hidden="true" data-dismiss="modal" type="reset">
                                                        </div>     
                                                    </div>     
                                                </form>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div>   
            <?php if ($commentUser['id'] == Auth::user()->id) { ?><a class="set-edit" href="javascript:void(0)" onclick="editComment(<?= $postComment['id'] ?>);">e</a>
                                        <a class="set-alarm" href="{{url('/deletecomment',$postComment['id'])}}">a</a><?php } ?>
                                </div>
                            </div>    
                        </div> 
                    </div>
                    <textarea name="comment_text" id="comment_text_<?= $postComment['id'] ?>" readonly="" class="text-12 textarea-width"><?php echo $postComment['comment_text']; ?></textarea>
                    <div class="btn-wrap-div">
                        <input type="button" name="update_comment" id="update_comment_<?= $postComment['id'] ?>" value="Save" onclick="updateComment(<?= $postComment['id'] ?>)" style="display: none;"/>
                        <input type="button" name="cancel_comment" id="cancel_comment_<?=$postComment['id']?>" value="Cancel" class="btn btn-secondary" onClick=" this.form.reset();closeComment(<?=$postComment['id']?>)" style="display: none;"/>
                    </div>
                    <div class="rply-box">
                        <div class="rply-count like">
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
                        <div class="rply-count dislike">
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
                        <div class="rply-count">
                            <a href="javascript:void(0);" data-toggle="modal" data-id="{{$postComment['id']}}" id="modalComment" data-target="#myModalComment"><i class="fa fa-reply" aria-hidden="true"></i></a>
                            <span><?php echo count($postComment['commentReply']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
<!-- Comment Box end -->
