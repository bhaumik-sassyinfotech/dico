@extends('template.default')
<title>DICO - Post</title>
@section('content')

<div id="page-content" class="group-listing posts">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
               <li><a href="{{ url('/home') }}">Dashboard</a></li>
               <li class="active">Post</li>
            </ol>
            <h1 class="tp-bp-0">Post</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs">
                        <a href="{{ route('post.create') }}" class="btn btn-default"><i class="fa fa-pencil-square-o fa-6" aria-hidden="true"></i><span class="hidden-xs hidden-sm">News Post</span></a>
                    </div>
                    <div class="btn-group">
                        <a href="#" style="display: none;" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-filter fa-6" aria-hidden="true"></i><span class="hidden-xs hidden-sm hidden-md">Filter</span> </a>
                        
                    </div>
                    
                </div>
            </div>
        </div>    
        <div class="container">
            <div class="row">
                <div class=" profile-page col-sm-12 col-md-12">
                    <div class="panel panel-midnightblue group-tabs">
                        <div class="panel-heading">
                            <h4>
                                <ul class="nav nav-tabs">
                                       <li class="active"><a href="#threads" data-toggle="tab" onclick="document.getElementById('search_text').value = '';"><i class="fa fa-list visible-xs icon-scale"></i><span class="hidden-xs">All Posts</span></a></li>
                                       <li class=""><a href="#groups" data-toggle="tab" onclick="document.getElementById('search_text').value = '';"><i class="fa fa-group visible-xs icon-scale"></i><span class="hidden-xs">My Group Posts</span></a></li>
                                       <li class=""><a href="#users" data-toggle="tab" onclick="document.getElementById('search_text').value = '';"><i class="fa fa-group visible-xs icon-scale"></i><span class="hidden-xs">My Posts</span></a></li>
                                </ul>
                            </h4>
                            <div class="pull-right search-form">
                                  <!-- <form method="post" class="search-form">-->
                                      <input type="text" name="search_text" id="search_text" placeholder="Search  Posts">
                                      <input type="button" value="#" onclick="searchPost();" class="search-icon">
                                  <!-- </form>-->
                            </div>
                         </div>
                        <div class="panel-body">
                            @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                            @endif
                            @if(session()->has('err_msg'))
                            <div class="alert alert-danger">
                                {{ session()->get('err_msg') }}
                            </div>
                            @endif
                            <div class="tab-content">
                                <!-- START ALL POST -->
                                <div tabindex="5000" class="tab-pane active" id="threads">
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
                                                                <?php
                                                                     if(!empty($post['post_flagged'])) {
                                                                ?>
                                                                    <a href="javascript:void(0)"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                                                <?php } else { ?>
                                                                    <a href="javascript:void(0)"><img src="assets/img/warning-icon.png"></a>
                                                                <?php } ?>
                                                             </div>
                                                         </div>
                                                         <div class="panel-body">
                                                             <h4><a href="{{url('viewpost', Helpers::encode_url($post['id']))}}" class="profanity">{{ str_limit($post['post_title'], $limit = POST_TITLE_LIMIT, $end = '...') }}</a></h4>
                                                             <div class="user-wrap"> 
                                                                <div class="user-img">
                                                                    @if(empty($post['post_user']['profile_image']) || $post['is_anonymous'] == 1)
                                                                        <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                                                                    @else
                                                                        <img src="{{ asset(PROFILE_PATH.$post['post_user']['profile_image']) }}">
                                                                    @endif
                                                                </div> 
                                                                <p class="user-icon">-<?php if($post['is_anonymous'] == 0) { ?>
                                                                    <a href="{{url('view_profile', Helpers::encode_url($post['post_user']['id']))}}">{{$post['post_user']['name']}}</a>
                                                                    <?php } else { echo "Anonymous"; } ?><span>on {{date(DATE_FORMAT,strtotime($post['created_at']))}}</span></p>
                                                             </div>
                                                             <fieldset>
                                                                 <p class="desc-content profanity" id="desc_content_{{$post['id']}}">{{$post['post_description']}}</p>
                                                                 <?php /*<p class="desc-content">{{ str_limit($post['post_description'], $limit = POST_DESCRIPTION_LIMIT, $end = '...') }}</p>*/?>
                                                             </fieldset>
                                                             <div class="btn-wrap" id="postread{{$post['id']}}">
                                                                <a href="#" onclick ="postReadMore({{$post['id']}})">Read More</a>
                                                             </div>
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
                                        }  else {
                                            echo "No post found.";
                                        }
                                    ?>
                                    <?php if(!empty($count_post) && $count_post > POST_DISPLAY_LIMIT) {
                                    ?>
                                    <div class="all_viewmore col-md-12"><a href="javascript:void(0)" id="load_post" onclick="loadMorePost();" data-id="0">View More</a></div>
                                    <?php
                                            } ?>
                                    <input type="hidden" id="count_post" value="{{$count_post}}">
                                </div>
                                <!-- END ALL POST -->
                                <!-- START GROUP POST -->
                                <div tabindex="5002" class="tab-pane" id="groups">
                                    <?php
                                        if (!empty($group_posts)) {
                                            foreach ($group_posts as $grouppost) { 
                                                $mypost_type = $grouppost['post_type'];
                                                if($mypost_type == "idea") {
                                                    $mypost_class = 1;
                                                }else if($mypost_type == "question") {
                                                    $mypost_class = 2;
                                                }else if($mypost_type == "challenge") {
                                                    $mypost_class = 3;
                                                }
                                                ?>
                                                <div class="col-md-4 grouppostlist">
                                                       <div class="panel-{{$mypost_class}} panel-primary">
                                                            <div class="panel-heading">
                                                                <h4 class="icon">{{ucfirst($grouppost['post_type'])}}</h4>
                                                                <div class="pull-right i-con-set">
                                                                  <a><img src="assets/img/notification-icon.png"></a>  
                                                                  <?php
                                                                     if(!empty($grouppost['post_flagged'])) {
                                                                ?>
                                                                    <a href="javascript:void(0)"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                                                <?php } else { ?>
                                                                    <a href="javascript:void(0)"><img src="assets/img/warning-icon.png"></a>
                                                                <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <h4><a href="{{url('viewpost', Helpers::encode_url($grouppost['id']))}}">{{ str_limit($grouppost['post_title'], $limit = POST_TITLE_LIMIT, $end = '...') }}</a></h4>
                                                                <div class="user-wrap"> 
                                                                <div class="user-img"> 
                                                                    @if(empty($grouppost['post_user']['profile_image']) || $grouppost['is_anonymous'] == 1)
                                                                        <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                                                                    @else
                                                                        <img src="{{ asset(PROFILE_PATH.$grouppost['post_user']['profile_image']) }}">
                                                                    @endif
                                                                </div> 
                                                                <p class="user-icon">-<?php if($grouppost['is_anonymous'] == 0) { ?>
                                                                    <a href="{{url('view_profile', Helpers::encode_url($grouppost['post_user']['id']))}}">{{$grouppost['post_user']['name']}}</a>
                                                                    <?php } else { echo "Anonymous"; } ?><span>on {{date(DATE_FORMAT,strtotime($grouppost['created_at']))}}</span></p>
                                                                </div>
                                                                <fieldset>
                                                                   <?php /*<p class="desc-content" id="desc_mycontent_{{$post['id']}}">{{ str_limit($mypost['post_description'], $limit = POST_DESCRIPTION_LIMIT, $end = '...') }}</p>*/?>
                                                                    <p class="desc-content" id="desc_mycontent_{{$grouppost['id']}}">{{ $grouppost['post_description'] }}</p>
                                                                </fieldset>
                                                                <div class="btn-wrap" id="mypostread{{$grouppost['id']}}">
                                                                   <a href="#" onclick ="mypostReadMore({{$grouppost['id']}})">Read More</a>
                                                                </div>
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
                                                                          <p>Status:<span>Active</span></p>
                                                                    </div>  
                                                                </div> 
                                                                <?php
                                                                    if(!empty($grouppost['post_tag'])) {
                                                                ?>
                                                                <hr>
                                                                <div class="post-circle">
                                                                    <?php foreach($grouppost['post_tag'] as $mypost_tag) { ?><a href="{{url('tag', Helpers::encode_url($mypost_tag['tag']['id']))}}"><?= $mypost_tag['tag']['tag_name'];?></a><?php } ?>
                                                                 </div>
                                                                    <?php } ?>
                                                            </div>
                                                       </div>
                                                   </div> 
                                    <?php 
                                            } 
                                        } else {
                                            echo "No post found.";
                                        }
                                        ?>
                                    <?php
                                    if(!empty($count_group_post) && $count_group_post > POST_DISPLAY_LIMIT) {
                                    ?>
                                    <div class="group_viewmore col-md-12"><a href="javascript:void(0)" id="load_grouppost" onclick="loadMoreGroupPost();" data-id="0">View More</a></div>
                                    <?php
                                            } ?>
                                    <input type="hidden" id="count_grouppost" value="{{$count_group_post}}">
                                </div>
                                <!-- END GROUP POST -->
                                <!-- START USER POST -->
                                <div tabindex="5002" class="tab-pane" id="users">
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
                                                                  <?php
                                                                     if(!empty($mypost['post_flagged'])) {
                                                                ?>
                                                                    <a href="javascript:void(0)"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                                                <?php } else { ?>
                                                                    <a href="javascript:void(0)"><img src="assets/img/warning-icon.png"></a>
                                                                <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <h4><a href="{{url('viewpost', Helpers::encode_url($mypost['id']))}}" class="profanity">{{ str_limit($mypost['post_title'], $limit = POST_TITLE_LIMIT, $end = '...') }}</a></h4>
                                                                <div class="user-wrap"> 
                                                                <div class="user-img"> 
                                                                    @if(empty($mypost['post_user']['profile_image']) || $mypost['is_anonymous'] == 1)
                                                                        <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                                                                    @else
                                                                        <img src="{{ asset(PROFILE_PATH.$mypost['post_user']['profile_image']) }}">
                                                                    @endif
                                                                </div> 
                                                                <p class="user-icon">-<?php if($mypost['is_anonymous'] == 0) { ?>
                                                                    <a href="{{url('view_profile', Helpers::encode_url($mypost['post_user']['id']))}}">{{$mypost['post_user']['name']}}</a>
                                                                    <?php } else { echo "Anonymous"; } ?><span>on {{date(DATE_FORMAT,strtotime($mypost['created_at']))}}</span></p>
                                                                </div>
                                                                <fieldset>
                                                                   <?php /*<p class="desc-content" id="desc_mycontent_{{$post['id']}}">{{ str_limit($mypost['post_description'], $limit = POST_DESCRIPTION_LIMIT, $end = '...') }}</p>*/?>
                                                                    <p class="desc-content" id="desc_mycontent_{{$post['id']}}">{{ $mypost['post_description'] }}</p>
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
                                                                        
                                                                        <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>{{$post['post_view_count']}}</span></div>
                                                                        
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
                                                                    if(!empty($mypost['post_tag'])) {
                                                                ?>
                                                                <hr>
                                                                <div class="post-circle">
                                                                    <?php foreach($mypost['post_tag'] as $mypost_tag) { ?><a href="{{url('tag', Helpers::encode_url($mypost_tag['tag']['id']))}}"><?= $mypost_tag['tag']['tag_name'];?></a><?php } ?>
                                                                 </div>
                                                                    <?php } ?>
                                                            </div>
                                                       </div>
                                                   </div> 
                                    <?php 
                                            } 
                                        } else {
                                            echo "No post found.";
                                        }
                                        ?>
                                    <?php
                                    if(!empty($count_user_post) && $count_user_post > POST_DISPLAY_LIMIT) {
                                    ?>
                                    <div class="user_viewmore col-md-12"><a href="javascript:void(0)" id="load_mypost" onclick="loadMoreMyPost();" data-id="0">View More</a></div>
                                    <?php
                                            } ?>
                                    <input type="hidden" id="count_mypost" value="{{$count_user_post}}">
                                </div>
                                <!-- END USER POST -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascripts')
<script type="text/javascript">
    function deletepost(id) {
    swal({
    title: "Are you sure?",
            text: "you will not able to recover this post.",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
    }, function () {
        var token = '<?php echo csrf_token() ?>';
        var formData = {post_id : id, _token : token};
        $.ajax({
        url: "{{ route('post.destroy'," + id + ") }}",
        type: "POST",
        data: formData,
        success: function (response) {
            var res = JSON.parse(response);
            if (res.status == 1) {
                swal("Success", res.msg, "success");
                location.reload();
            }
            else {
                swal("Error", res.msg, "error");
            }
        }
    });
    });
    }
    function loadMorePost() {
    //alert("here");
    //alert($('#search_text').val()); return false;
        var id = $('#load_post').attr('data-id');
        var offset = parseInt(id) + {{POST_DISPLAY_LIMIT}};
        //var count_post = $('#count_post').val();
        var searchText = $('#search_text').val();
        var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
        $.ajax({
            url: SITE_URL+"/loadmorepost",
            type: "POST",
            data: formData,
            success: function (response) {
                if(response != "") {
                    if(response.html != "") {
                        $('#threads .postlist:last').after(response.html);
                        runProfanity();
                        $('#load_post').attr('data-id',offset);
                        if($('.postlist').length == response.count) {
                            $('#load_post').hide();
                        }
                    }else {
                        //$('#threads .postlist:last').after("No post found.");
                    }
                }
            },
            error: function() {
            }
        });
    }
    function loadMoreMyPost() {
        var id = $('#load_mypost').attr('data-id');
        var offset = parseInt(id) + {{POST_DISPLAY_LIMIT}};
        var searchText = $('#search_text').val();
        var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
        $.ajax({
            url: SITE_URL+"/loadmoremypost",
            type: "POST",
            data: formData,
            success: function (response) {
                //console.log(response);
                if(response != '') {
                    if(response.html != "") {
                        $('#users .userpostlist:last').after(response.html);
                        runProfanity();
                        $('#load_mypost').attr('data-id',offset);
                        if($('.userpostlist').length == response.count) {
                            $('#load_mypost').hide();
                        }else {
                        //$('#users .postlist:last').after("No post found.");
                        }
                    }
                }
            },
            error: function() {
            }
        });
    }
    function loadMoreGroupPost() {
        var id = $('#load_grouppost').attr('data-id');
        var offset = parseInt(id) + {{POST_DISPLAY_LIMIT}};
        var searchText = $('#search_text').val();
        var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
        $.ajax({
            url: SITE_URL+"/loadmoregrouppost",
            type: "POST",
            data: formData,
            success: function (response) {
                if(response != '') {
                    if(response.html != "") {
                        $('#groups .grouppostlist:last').after(response.html);
                        runProfanity();
                        $('#load_grouppost').attr('data-id',offset);
                        if($('.grouppostlist').length == response.count) {
                            $('#load_grouppost').hide();
                        }else {
                        //$('#users .postlist:last').after("No post found.");
                        }
                    }
                }
            },
            error: function() {
            }
        });
    }
    function searchPost() {
        if($('#threads').hasClass('active')) {
            var id = $('#load_post').attr('data-id');
            var offset = 0;
            var searchText = $('#search_text').val();
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            $.ajax({
                url: SITE_URL+"/loadmorepost",
                type: "POST",
                data: formData,
                success: function (response) {
                    //console.log(response.html);
                    if(response != ""){
                        if(response.html != "") {
                            $('#threads .postlist').remove();
                            //$('#count_post').remove();
                            $('#threads .all_viewmore').before(response.html);
                            runProfanity();
                            $('#load_post').attr('data-id',offset);
                            //console.log(response.count+":::"+{{POST_DISPLAY_LIMIT}});
                            if(response.count <= {{POST_DISPLAY_LIMIT}}) {
                                $('#load_post').hide();
                            } else {
                                $('#load_post').show();
                            }
                        } else {
                            $('#threads .postlist').remove();
                            $('#threads').append("<p class='postlist'>No post found.</p>");
                            $('#load_post').hide();
                            
                        }
                    }
                },
                error: function() {
                }
            });
        } else if($('#users').hasClass('active')) {
            var id = $('#load_mypost').attr('data-id');
            var offset = 0;
            var searchText = $('#search_text').val();
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            $.ajax({
                url: SITE_URL+"/loadmoremypost",
                type: "POST",
                data: formData,
                success: function (response) {
                    if(response != '') {
                        if(response.html != "") {
                            $('#users .userpostlist').remove();
                            $('#users .user_viewmore').before(response.html);
                            runProfanity();
                            $('#load_mypost').attr('data-id',offset);
                            if(response.count <= {{POST_DISPLAY_LIMIT}}) {
                                $('#load_mypost').hide();
                            } else {
                                $('#load_mypost').show();
                            }
                        } else {
                            $('#users .userpostlist').remove();
                            $('#users').append("<p class='userpostlist'>No post found.</p>");
                            $('#load_mypost').hide();
                            
                        }
                    }
                },
                error: function() {
                }
            });
        } else {
            var id = $('#load_grouppost').attr('data-id');
            var offset = 0;
            var searchText = $('#search_text').val();
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            $.ajax({
                url: SITE_URL+"/loadmoregrouppost",
                type: "POST",
                data: formData,
                success: function (response) {
                    if(response != '') {
                        if(response.html != "") {
                            $('#groups .grouppostlist').remove();
                            $('#groups .group_viewmore').before(response.html);
                            runProfanity();
                            $('#load_grouppost').attr('data-id',offset);
                            if(response.count <= {{POST_DISPLAY_LIMIT}}) {
                                $('#load_grouppost').hide();
                            } else {
                                $('#load_grouppost').show();
                            }
                        } else {
                            $('#groups .grouppostlist').remove();
                            $('#groups').append("<p class='grouppostlist'>No post found.</p>");
                            $('#load_grouppost').hide();
                            
                        }
                    }
                },
                error: function() {
                }
            });
        }
    }
    function postReadMore(id) {
        $('#desc_content_'+id).css('height','auto');
        $('#postread'+id).hide();
    }
    function mypostReadMore(id) {
        $('#desc_mycontent_'+id).css('height','auto');
        $('#mypostread'+id).hide();
    }
</script>
@endpush
