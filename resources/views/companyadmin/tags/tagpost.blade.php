@extends('template.default')
<title>@lang('label.adDICO - Tags')</title>
@section('content')

<div id="page-content" class="group-listing posts">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
               <li><a href="{{ url('/home') }}">@lang('label.adDashboard')</a></li>
               <li class="active">@lang('label.adTags')</li>
            </ol>
            <h1 class="tp-bp-0">@lang('label.adTags')</h1>
            <div class="options">
                <div class="btn-toolbar">
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
                                <li class="active"><a href="#threads" data-toggle="tab"><i class="fa fa-list visible-xs icon-scale"></i><span class="hidden-xs">{{ucfirst($tag['tag_name'])}} @lang('label.adPosts')</span></a></li>
                                <input type="hidden" name="tag_id" id="tag_id" value="{{$tag['id']}}">
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
                                                             <div class="pull-right no-icon">
                                                               <a><img src="{{asset('assets/img/notification-icon.png')}}"></a>  
                                                                <?php
                                                                     if(!empty($post['post_flagged'])) {
                                                                ?>
                                                                    <a href="javascript:void(0)"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                                                <?php } else { ?>
                                                                    <a href="javascript:void(0)"><img src="{{asset('assets/img/warning-icon.png')}}"></a>
                                                                <?php } ?>
                                                             </div>
                                                         </div>
                                                         <div class="panel-body meetings">
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
                                                             <?php
                                                                if(strlen($post['post_description']) > POST_DESCRIPTION_LIMIT) {
                                                            ?>
                                                             <div class="btn-wrap" id="postread{{$post['id']}}">
                                                                <a href="#" onclick ="postReadMore({{$post['id']}})">Read More')</a>
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
                                                                       <p>@lang('label.adStatus'):<span>@lang('label.adActive')</span></p>
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
                                    <input type="hidden" id="count_post" value="{{$count_post}}">
                                    <?php if(!empty($count_post) && $count_post > POST_DISPLAY_LIMIT) {
                                    ?>
                                    <div class="all_viewmore col-md-12"><a href="javascript:void(0)" id="load_post" onclick="loadMorePost();" data-id="0">@lang('label.ViewMore')</a></div>
                                    <?php
                                            } ?>
                                    
                                </div>
                                <!-- END ALL POST -->
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
    title: "@lang('label.adAre you sure')",
            text: "@lang('label.adyou will not able to recover this post')",
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
                swal("@lang('label.adSuccess')", res.msg, "success");
                location.reload();
            }
            else {
                swal("@lang('label.adError')", res.msg, "error");
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
        var tag_id = $('#tag_id').val();
        var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText,tag_id:tag_id};
        $.ajax({
            url: SITE_URL+"/loadmoretagpost",
            type: "POST",
            data: formData,
            success: function (response) {
                if(response != "") {
                    $('#threads .postlist:last').after(response.html);
                    runProfanity();
                    $('#load_post').attr('data-id',offset);
                    if($('.postlist').length == response.count) {
                        $('#load_post').hide();
                    }
                }
            },
            error: function() {
            }
        });
    }
    
    function searchPost() {
        
            var id = $('#load_post').attr('data-id');
            var offset = 0;
            var searchText = $('#search_text').val();
            var tag_id = $('#tag_id').val();
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText,tag_id:tag_id};
            $.ajax({
                url: SITE_URL+"/loadmoretagpost",
                type: "POST",
                data: formData,
                success: function (response) {
                    if(response != ""){
                        if(response.html != "") {
                            $('#threads .postlist').remove();
                            //$('#count_post').remove();
                            $('#threads #count_post').before(response.html);
                            runProfanity();
                            $('#load_post').attr('data-id',offset);
                            if(response.count < {{POST_DISPLAY_LIMIT}}) {
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
