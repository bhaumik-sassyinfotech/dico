<!-- Comment Box start -->
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
                            <h3><?php if ($postComment['is_anonymous'] == 0) {
                echo $commentUser['name'];
            } else {
                echo "<b>Anonymous</b>";
            } ?></h3>
                            <p>- on <?php echo date(DATE_FORMAT, strtotime($commentUser['created_at'])); ?></p>
                        </div>
                        <div class="pull-right post-reply-pop">
                            <div class="options">
                                <div class="star-wrap">
                                    <?php if ($post['user_id'] == Auth::user()->id) { ?>
                                        <?php if ($postComment['is_correct'] == 1) { ?>
                                            <i class="fa fa-star" id="icon_{{$postComment['id']}}" aria-hidden="true"></i><?php } else {
                                            ?>
                                            <i class="fa fa-star-o" id="icon_{{$postComment['id']}}" aria-hidden="true"></i><?php }
                    ?>  
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
                                            <?php if ($commentUser['id'] == Auth::user()->id) { ?>
                                                <li><a href="javascript:void(0)" onclick="editComment(<?= $postComment['id'] ?>);">Edit Comment</a></li>
            <?php } ?>
                                            <li><a href="#">Report Comment</a></li>
            <?php if ($commentUser['id'] == Auth::user()->id) { ?>
                                                <li><a href="{{url('/deletecomment',$postComment['id'])}}">Delete Comment</a></li><?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>    
                        </div> 
                    </div>
                    <input type="text" name="comment_text" id="comment_text_<?= $postComment['id'] ?>" value="<?php echo $postComment['comment_text']; ?>" readonly="" class="text-12"/>
                    <input type="button" name="update_comment" id="update_comment_<?= $postComment['id'] ?>" value="Save" onclick="updateComment(<?= $postComment['id'] ?>)" style="display: none;"/>
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
        <?php
        }
    }
}
?>
<!-- Comment Box end -->
