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
                            <a href="{{ route('view_profile',$comment_id) }}" class="btn btn-primary" >@lang('label.Unfollow')</a>
                            <?php
                        } else {
                            ?>
                            <a href="{{ route('view_profile',$comment_id) }}" class="btn btn-primary" >@lang('label.Follow')</a>
                            <?php
                        }
                    } else if ($commentUser->id != Auth::user()->id) {
                        ?>
                        <a href="{{ url('/view_profile/'.$comment_id) }}" class="btn btn-primary" >@lang('label.Follow')</a>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-10 user-rply">
                    <div class="post-inner-reply"> 
                        <div class="pull-left post-user-nam">
                            <h3 class="text-12"><?php
                                if ($postComment['is_anonymous'] == 0) { ?>
                                <a href="{{route('view_profile', Helpers::encode_url($commentUser['id']))}}"><?php echo $commentUser['name'];?></a>
                                <?php } else {
                                    echo __('label.Anonymous');
                                }
                                ?></h3>
                            <p>- @lang('label.on') <?php echo date(DATE_FORMAT, strtotime($commentUser['created_at'])); ?></p>
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
                                        <?php if ($commentUser['id'] == Auth::user()->id || $commentUser['role_id'] > Auth::user()->role_id || count(Auth::user()->group) > 0) { ?>
                                            <a id="solution_{{$postComment['id']}}" href="javascript:void(0)" onclick="markSolution({{$postComment['id']}}, {{$commentUser['id']}}, {{$post['id']}})">@lang('label.Correct')</a>
                                        <?php } else { ?>
                                            @lang('label.Correct')  
                                    <?php } ?>    
                                    </p>
                                </div>
                                <div class="fmr-10">
                                    <?php    
                                        if(!empty($postComment['commentFlagged'])) {
                                            if($postComment['commentFlagged']['flag_by'] == Auth::user()->id) {
                                        ?>
                                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                        <?php } else { ?>
                                        <a class="set-warning" href="javascript:void(0)" onclick="openFlagComment({{$postComment['id']}}, {{$commentUser['id']}})">w</a>
                                            <?php } } else { ?>
                                        <a class="set-warning" href="javascript:void(0)" onclick="openFlagComment({{$postComment['id']}}, {{$commentUser['id']}})">w</a>
                                        <?php } ?>
                                    <?php if ($commentUser['id'] == Auth::user()->id) { ?><a class="set-edit" href="javascript:void(0)" onclick="editComment('<?= 'popup_'.$postComment['id'] ?>');">e</a>
                                        <a class="set-alarm" href="{{route('deletecomment',$postComment['id'])}}">a</a><?php } ?>
                                </div>
                            </div>    
                        </div> 
                    </div>
                    <p class="profanity" id="comment_disp_popup_<?=$postComment['id']?>"><?php echo $postComment['comment_text']; ?></p>
                    <textarea name="comment_text" id="comment_text_popup_<?= $postComment['id'] ?>" readonly="" class="text-12 textarea-width" style="display: none;"><?php echo $postComment['comment_text']; ?></textarea>
                    <div class="btn-wrap-div">
                        <input type="button" name="update_comment" id="update_comment_popup_<?= $postComment['id'] ?>" value="@lang('label.Save')" onclick="updateComment(<?= $postComment['id'] ?>,'<?= 'popup_'.$postComment['id'] ?>')" class="st-btn" style="display: none;"/>
                        <input type="button" name="cancel_comment" id="cancel_comment_popup_<?=$postComment['id']?>" value="@lang('label.Cancel')" class="btn btn-secondary btn-st" onClick=" this.form.reset();closeComment('<?= 'popup_'.$postComment['id']?>')" style="display: none;"/>
                    </div>
                    <div class="rply-box">
                        <div class="rply-count like">
                            <a href="javascript:void(0)" id="like_comment_popup_{{$postComment['id']}}" onclick="likeComment({{$postComment['id']}},'<?='popup_'.$postComment['id']?>');" >
                                <?php
                                if (!empty($postComment['commentUserLike'])) {
                                    ?>
                                    <i class="fa fa-thumbs-up"></i>
                                <?php } else { ?>
                                    <i class="fa fa-thumbs-o-up"></i>
                                <?php } ?>
                            </a><span id="comment_like_count_popup_{{$postComment['id']}}"><?php echo count($postComment['commentLike']) ?></span>
                            <!-- <img alt="post-like" src="assets/img/like.png"><p>08</p>-->
                        </div> 
                        <div class="rply-count dislike">
                            <a href="javascript:void(0)" id="dislike_comment_popup_{{$postComment['id']}}" onclick="dislikeComment({{$postComment['id']}},'<?='popup_'.$postComment['id']?>');" >
                                <?php
                                if (!empty($postComment['commentUserDisLike'])) {
                                    ?>
                                    <i class="fa fa-thumbs-down"></i>
                                <?php } else { ?>
                                    <i class="fa fa-thumbs-o-down"></i>
            <?php } ?>
                            </a>
                            <span id="comment_dislike_count_popup_{{$postComment['id']}}"><?php echo count($postComment['commentDisLike']); ?></span>
                            <!-- <img alt="post-rply" src="assets/img/post-rply.png"> <p>04</p>-->
                        </div> 
                        <div class="rply-count">
                            <a href="javascript:void(0);" onclick="openCommentReplyBox({{$postComment['id']}})" id="modalComment"><i class="fa fa-reply" aria-hidden="true"></i></a>
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
