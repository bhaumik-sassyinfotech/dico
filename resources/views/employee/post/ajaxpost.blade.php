<?php
    if (!empty($posts)) {
        foreach ($posts as $post) { 
            $post_type = $post['post_type'];
            if($post_type == "idea") {
                $post_class = 1;
            }else if($post_type == "question") {
                $post_class = 2;
            }else if($post_type == "challenge") {
                $post_class = 3;
            }
            ?>
            <div class="col-md-4 postlist">
                   <div class="panel-{{$post_class}} panel-primary">
                        <div class="panel-heading">
                            <h4 class="icon">{{ucfirst($post['post_type'])}}</h4>
                            <div class="pull-right i-con-set">
                              <a><img src="assets/img/notification-icon.png"></a>  
                              <a><img src="assets/img/warning-icon.png"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <h4><a href="{{url('viewpost', Helpers::encode_url($post['id']))}}">{{ str_limit($post['post_title'], $limit = POST_TITLE_LIMIT, $end = '...') }}</a></h4>
                            <p>-<?php if($post['is_anonymous'] == 0) { echo $post['post_user']['name']; } else { echo "Anonymous"; } ?><span>on {{date(DATE_FORMAT,strtotime($post['created_at']))}}</span></p>
                            <fieldset>
                                <?php /*<p class="desc_content">{{ str_limit($post['post_description'], $limit = POST_DESCRIPTION_LIMIT, $end = '...') }}</p>*/?>
                                <p class="desc-content" id="desc_content_{{$post['id']}}">{{ $post['post_description'] }}</p>
                            </fieldset>
                            <?php
                                if(strlen($post['post_description']) > POST_DESCRIPTION_LIMIT) {
                            ?>
                            <div class="btn-wrap" id="postread{{$post['id']}}">
                                <a href="#" onclick ="postReadMore({{$post['id']}})">Read More</a>
                             </div>
                                <?php } ?>
                            <div class="panel-body-wrap">
                                <div class="wrap-social pull-left">
                                    <div class="wrap-inner-icon"><a href="javascript:void(0)" id="like_post_{{$post['id']}}" onclick="like_post({{$post['id']}})">
                                        <?php
                                        if (!empty($post['post_user_like'])) {
                                            ?>
                                            <i class="fa fa-thumbs-up"></i>
                                        <?php } else { ?>
                                            <i class="fa fa-thumbs-o-up"></i>
                                        <?php } ?>
                                        </a>
                                        <span id="post_like_count_{{$post['id']}}"><?php echo count($post['post_like']); ?></span>
                                    </div>

                                    <div class="wrap-inner-icon"><a href="javascript:void(0)" id="dislike_post_{{$post['id']}}" onclick="dislike_post({{$post['id']}})">
                                        <?php
                                        if (!empty($post['post_user_dis_like'])) {
                                            ?>
                                            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                        <?php } else { ?>
                                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                        <?php } ?>
                                        </a>
                                        <span id="post_dislike_count_{{$post['id']}}"><?php echo count($post['post_dis_like']); ?></span>
                                    </div>

                                    <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>{{$post['post_view_count']}}</span></div>

                                    <div class="wrap-inner-icon"><a href="javascript:void(0);">
                                        <?php
                                            if (!empty($post['post_comment'])) {
                                                ?>
                                                <i class="fa fa-comments"></i>
                                            <?php } else { ?>
                                                <i class="fa fa-comments-o"></i>
                                            <?php } ?>
                                        </a></div>
                                    <span><?php echo count($post['post_comment']); ?></span>
                                </div>
                                <div class="status pull-right">
                                      <p>Status:<span>Active</span></p>
                                </div>  
                            </div> 
                            <?php
                                if(!empty($post['post_tag'])) {
                            ?>
                            <hr>
                            <div class="post-circle">
                                <?php foreach($post['post_tag'] as $post_tag) { ?><a href="{{url('tag', Helpers::encode_url($post_tag['tag']['id']))}}"><?= $post_tag['tag']['tag_name'];?></a><?php } ?>
                             </div>
                                <?php } ?>
                        </div>
                   </div>
               </div> 
<?php 
        } 
       
    } 
?>
                                