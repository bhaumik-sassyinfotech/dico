<?php
if (!empty($user_posts)) {
    foreach ($user_posts as $mypost) {
        $mypost_type = $mypost['post_type'];
        if($mypost_type == "idea") {
            $mypost_class = 1;
        }else if($mypost_type == "question") {
            $mypost_class = 2;
        }else if($mypost_type == "challenge") {
            $mypost_class = 3;
        }
        ?>
        <div class="col-md-4 userpostlist">
            <div class="panel-{{$mypost_class}} panel-primary">
                <div class="panel-heading">
                    <h4 class="icon">{{ucfirst($mypost['post_type'])}}</h4>
                    <div class="pull-right i-con-set">
                        <a><img src="assets/img/notification-icon.png"></a>  
                        <a><img src="assets/img/warning-icon.png"></a>
                    </div>
                </div>
                <div class="panel-body">
                    <h4><a href="{{url('viewpost', Helpers::encode_url($mypost['id']))}}">{{ str_limit($mypost['post_title'], $limit = POST_TITLE_LIMIT, $end = '...') }}</a></h4>
                    <p>-<?php if ($mypost['is_anonymous'] == 0) {
            echo $mypost['post_user']['name'];
        } else {
            echo "Anonymous";
        } ?><span>on {{date(DATE_FORMAT,strtotime($mypost['created_at']))}}</span></p>
                    <fieldset>
                       <?php /* <p>{{ str_limit($mypost['post_description'], $limit = POST_DESCRIPTION_LIMIT, $end = '...') }}</p>*/?>
                        <p class="desc-content" id="desc_mycontent_{{$mypost['id']}}">{{ $mypost['post_description'] }}</p>
                    </fieldset>
                    <div class="btn-wrap" id="mypostread{{$mypost['id']}}">
                        <a href="#" onclick ="mypostReadMore({{$mypost['id']}})">Read More</a>
                     </div>
                    <div class="panel-body-wrap">
                        <div class="wrap-social pull-left">
                            <div class="wrap-inner-icon"><a href="javascript:void(0)" id="like_post_{{$mypost['id']}}" onclick="like_post({{$mypost['id']}})">
                                    <?php
                                    if (!empty($mypost['post_user_like'])) {
                                        ?>
                                        <i class="fa fa-thumbs-up"></i>
        <?php } else { ?>
                                        <i class="fa fa-thumbs-o-up"></i>
                                    <?php } ?>
                                </a>
                                <span id="post_like_count_{{$mypost['id']}}"><?php echo count($mypost['post_like']); ?></span>
                            </div>

                            <div class="wrap-inner-icon"><a href="javascript:void(0)" id="dislike_post_{{$mypost['id']}}" onclick="dislike_post({{$mypost['id']}})">
                                    <?php
                                    if (!empty($mypost['post_user_dis_like'])) {
                                        ?>
                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
        <?php } else { ?>
                                        <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
        <?php } ?>
                                </a>
                                <span id="post_dislike_count_{{$mypost['id']}}"><?php echo count($mypost['post_dis_like']); ?></span>
                            </div>

                            <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>19</span></div>

                            <div class="wrap-inner-icon"><a href="javascript:void(0);">
                                    <?php
                                    if (!empty($mypost['post_comment'])) {
                                        ?>
                                        <i class="fa fa-comments"></i>
        <?php } else { ?>
                                        <i class="fa fa-comments-o"></i>
        <?php } ?>
                                </a></div>
                            <span><?php echo count($mypost['post_comment']); ?></span>
                        </div>
                        <div class="status pull-right">
                            <p>Status:<span>Active</span></p>
                        </div>  
                    </div> 
                        <?php
                        if (!empty($mypost['post_tag'])) {
                            ?>
                        <hr>
                        <div class="post-circle">
            <?php foreach ($mypost['post_tag'] as $mypost_tag) { ?><a href="#"><?= $mypost_tag['tag']['tag_name']; ?></a><?php } ?>
                        </div>
        <?php } ?>
                </div>
            </div>
        </div> 
        <?php
    }
}
?>