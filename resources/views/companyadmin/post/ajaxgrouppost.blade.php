<?php
if (!empty($group_posts)) {
    foreach ($group_posts as $grouppost) {
        $mypost_type = $grouppost['post_type'];
        if ($mypost_type == "idea") {
            $mypost_class = 1;
        } else if ($mypost_type == "question") {
            $mypost_class = 2;
        } else if ($mypost_type == "challenge") {
            $mypost_class = 3;
        }
        ?>
        <div class="col-md-4 grouppostlist">
            <div class="panel-{{$mypost_class}} panel-primary">
                <div class="panel-heading">
                    <h4 class="icon">{{ucfirst($grouppost['post_type'])}}</h4>
                    <div class="pull-right no-icon">
                        <a><img src="{{asset('assets/img/notification-icon.png')}}"></a>  
                        <?php
                            if(!empty($grouppost['post_flagged'])) {
                        ?>
                           <a href="javascript:void(0)"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                        <?php } else { ?>
                           <a href="javascript:void(0)"><img src="{{asset('assets/img/warning-icon.png')}}"></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="panel-body meetings">
                    <h4><a href="{{route('viewpost', Helpers::encode_url($grouppost['id']))}}" class="profanity post-title">{{ str_limit($grouppost['post_title'], $limit = POST_TITLE_LIMIT, $end = '...') }}</a></h4>
                    <div class="user-wrap"> 
                        <div class="user-img"> 
                            @if(empty($grouppost['post_user']['profile_image']) || $grouppost['is_anonymous'] == 1)
                                <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                            @else
                                <img src="{{ asset(PROFILE_PATH.$grouppost['post_user']['profile_image']) }}">
                            @endif
                        </div> 
                        <p class="user-icon">-<?php if($grouppost['is_anonymous'] == 0) { ?>
                            <a href="{{route('view_profile', Helpers::encode_url($grouppost['post_user']['id']))}}" class="user-a-post">{{$grouppost['post_user']['name']}}</a>
                            <?php } else { echo __("label.Anonymous"); } ?><span>@lang("label.on") {{date(DATE_FORMAT,strtotime($grouppost['created_at']))}}</span></p>
                    </div>
                    <fieldset>
        <?php /* <p class="desc-content" id="desc_mycontent_{{$post['id']}}">{{ str_limit($mypost['post_description'], $limit = POST_DESCRIPTION_LIMIT, $end = '...') }}</p> */ ?>
                        <p class="desc-content profanity" id="desc_mycontent_{{$grouppost['id']}}">{{ $grouppost['post_description'] }}</p>
                    </fieldset>
                    <?php
                       if(strlen($grouppost['post_description']) > POST_DESCRIPTION_LIMIT) {
                    ?>
                        <div class="btn-wrap" id="mypostread{{$grouppost['id']}}">
                            <a href="#" onclick ="mypostReadMore({{$grouppost['id']}})">@lang("label.ReadMore")</a>
                        </div>
                    <?php } ?>
                    <div class="panel-body-wrap">
                        <div class="wrap-social pull-left">
                            <div class="wrap-inner-icon"><a href="javascript:void(0)" id="like_post_{{$grouppost['id']}}" onclick="like_post({{$grouppost['id']}})">
                                    <?php
                                    if (!empty($grouppost['post_user_like'])) {
                                        ?>
                                        <i class="fa fa-thumbs-up"></i>
        <?php } else { ?>
                                        <i class="fa fa-thumbs-o-up"></i>
        <?php } ?>
                                </a>
                                <span id="post_like_count_{{$grouppost['id']}}"><?php echo count($grouppost['post_like']); ?></span>
                            </div>

                            <div class="wrap-inner-icon"><a href="javascript:void(0)" id="dislike_post_{{$grouppost['id']}}" onclick="dislike_post({{$grouppost['id']}})">
                                    <?php
                                    if (!empty($grouppost['post_user_dis_like'])) {
                                        ?>
                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
        <?php } else { ?>
                                        <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
        <?php } ?>
                                </a>
                                <span id="post_dislike_count_{{$grouppost['id']}}"><?php echo count($grouppost['post_dis_like']); ?></span>
                            </div>

                            <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>{{$grouppost['post_view_count']}}</span></div>

                            <div class="wrap-inner-icon"><a href="javascript:void(0);">
                                    <?php
                                    if (!empty($grouppost['post_comment'])) {
                                        ?>
                                        <i class="fa fa-comments"></i>
        <?php } else { ?>
                                        <i class="fa fa-comments-o"></i>
        <?php } ?>
                                </a></div>
                            <span><?php echo count($grouppost['post_comment']); ?></span>
                        </div>
                        <div class="status pull-right">
                            <p>@lang("label.Status"):<span>@lang("label.Active")</span></p>
                        </div>  
                    </div> 
                        <?php
                        if (!empty($grouppost['post_tag'])) {
                            ?>
                        <hr>
                        <div class="post-circle">
            <?php foreach ($grouppost['post_tag'] as $mypost_tag) { ?><a href="{{route('tag', Helpers::encode_url($mypost_tag['tag']['id']))}}"><?= $mypost_tag['tag']['tag_name']; ?></a><?php } ?>
                        </div>
        <?php } ?>
                </div>
            </div>
        </div> 
        <?php
    }
}
?>