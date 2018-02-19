@extends('template.default')
@section('content')

    <div id="page-content" class="group-listing">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('group.index') }}">Group</a></li>
                    <li class="active">Update Group</li>
                </ol>
                <h1 class="tp-bp-0">Update Group</h1>
            </div>

            <div class="container">
                <div class="row">
                    @include('template.notification')
                   <?php
/*
<div class="col-sm-12 col-md-2">
<form id="upload_form" method="post" action="{{ url('group/uploadGroupPicture') }}" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" value="{{ $groupData->id }}" name="group_id">
<div class="update-wrap">
<input type="file" id="image" name="group_picture" class="fileinput">
<label>Upload New Photo</label>
<div class="preview_box">
@php
$img = asset('assets/img/upload-image.png');
if($groupData->group_image != "")
$img = asset('public/uploads/groups/'.$groupData->group_image);
@endphp
<img id="preview_img" src="{{ $img }}">
</div>
</div>

</form>

</div>
 */
?>



                    <div class="group-images-left col-md-9 col-sm-12">
                        <div class="form_box grp-left">
                            <form id="upload_form" method="post">

                            </form>
                            <form id="upload_form" method="post" action="{{ url('group/uploadGroupPicture') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" value="{{ $groupData->id }}" name="group_id">
                                <input type="hidden" id="group_id" value="{{ $groupId }}">
                                <input type="hidden" id="company_id" value="{{ $groupData->company_id }}">

                                <div class="update-wrap">
                                    @if($currUserIsAdmin == '1')
                                        <input type="file" id="image" name="group_picture" class="fileinput">
                                        <label>Upload Photo</label>
                                    @endif
                                    <div class="preview_box">
                                        @php
                                            $img = asset('assets/img/upload-image.png');
                                            if($groupData->group_image != "")
                                                $img = asset('public/uploads/groups/'.$groupData->group_image);
                                        @endphp
                                        <img id="preview_img" src="{{ $img }}">
                                    </div>
                                </div>
                                @if($currUserIsAdmin == '1')
                                    <input class="update-button st-btn" style="position:relative;display: block; width: 100%;" type="submit" value="Submit" name="">
                                @endif
                            </form>
                        </div>
                        <div class="group-left-list grp-left">
                            <div class="panel panel-midnightblue">
                                <div class="panel-heading">
                                    <h4>Group Description:</h4>
                                    <div class="pull-right">
                                        <a href="#"><img  src="{{ asset('assets/img/notification.png') }}" alt="notification"></a>
                                        @if($currUserIsAdmin == '1')
                                            <a href="javascript:;" data-group-id="{{ $groupId }}" data-company-id="{{ $groupData->company_id }}" class="addUserToGroup"><img  src="{{ asset('assets/img/add-agent.png') }}" alt="add user"></a>
                                        @endif
                                    </div>
                                </div>
                                <textarea class="panel-body" onchange="saveGroupDesc()" name="group_desc" id="group_desc" style="margin: 0px;width: 603px;height: 94px;">{{$groupData->description}}</textarea>
                                <?php /*<div class="panel-body">    
                                    @if(empty($groupData->description))
                                        <p>-</p>
                                    @else
                                        <p>{{ nl2br($groupData->description) }}</p>
                                    @endif
                                </div>*/?>
                            </div>
                        </div>

                        <div class="group-box">
                            <div class="group-item one">
                                <h2 id="total_admin">{{ $count['admins']}}</h2>
                                <p>Group Admins</p>
                            </div>
                            <div class="group-item two">
                                <h2 id="total_users">{{ $count['total_users'] }}</h2>
                                <p>Group Members</p>
                            </div>
                            <div class="group-item three">
                                <h2 id="total_posts">{{ $count['total_posts'] }}</h2>
                                <p>Group Posts</p>
                            </div>
                        </div>
                    </div>
                    <div class="group-right-list col-sm-12 col-md-3">

                        <div class="category">
                            <h2>Group Admins</h2>
                            <div class="post-category">
                                <?php
                                if(!empty($groupData->groupUsers) && count($groupData->groupUsers) > 0) {
                                foreach($groupData->groupUsers as $user) {
                                    if($user->user_id == $groupData->group_owner || $user->is_admin == '1') { ?>
                                        <div class="member-wrap">
                                            <div class="member-img">
                                                @if($user->userDetail->profile_image != "")
                                                    <img src="{{ asset('public/uploads/profile_pic/'.$user->userDetail->profile_image) }}" alt="no">
                                                @else
                                                    <img src="{{ asset('assets/img/member1.PNG') }}" alt="no">
                                                @endif
                                            </div>
                                            <div class="member-details">
                                                <h3 class="text-12"><a href="{{ url('view_profile/'.Helpers::encode_url($user->userDetail->id)) }}">{{ $user->userDetail->name }}</a></h3>
                                                <a href="mailto:ricardo_ranchet@gmail.com">{{ $user->userDetail->email }}</a>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                } else {
                                    echo "<div class='member-wrap'>No group admin assign.</div>";
                                }
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="profile-page col-sm-12 col-md-12">
                        <div class="panel panel-midnightblue group-tabs">
                            <div class="panel-heading">
                                <h4>
                                    <ul class="nav nav-tabs">
                                        <li class=""><a href="#threads" data-toggle="tab"><i class="fa fa-list visible-xs icon-scale"></i><span class="hidden-xs">Posts</span></a></li>
                                        <li class="active"><a href="#users" data-toggle="tab"><i class="fa fa-comments visible-xs icon-scale"></i><span class="hidden-xs">Group Members</span></a></li>
                                    </ul>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div tabindex="5000" style="overflow-y: hidden;" class="tab-pane" id="threads">
                                        <div  class="post-slider owl-carousel">
                                            
                                        <?php   if (!empty($userPosts)) {
                                            //dd($userPosts);
                                            foreach ($userPosts as $post) {
                                                    $post_type = $post['post_type'];
                                                    if ($post_type == "idea") {
                                                            $post_class = 1;
                                                    } else if ($post_type == "question") {
                                                            $post_class = 2;
                                                    } else if ($post_type == "challenge") {
                                                            $post_class = 3;
                                                    }
                                                    ?>
                                            <div class="item">
                                                    <div class="panel-{{$post_class}} panel-primary">
                                                         <div class="panel-heading">
                                                             <h4 class="icon">{{ucfirst($post['post_type'])}}</h4>
                                                             <div class="pull-right no-icon">
                                                                <a><img src="{{asset('assets/img/notification-icon.png')}}"></a>
                                                                <?php
                                                                     if(!empty($post['post_flagged'])) {
                                                                ?>
                                                                    <a class="" href="javascript:void(0)"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                                                <?php } else { ?>
                                                                    <a class="" href="javascript:void(0)"><img src="{{asset('assets/img/warning-icon.png')}}"></a>
                                                                <?php } ?>
                                                             </div>
                                                         </div>
                                                         <div class="panel-body meetings">
                                                             <h4><a href="{{url('viewpost', Helpers::encode_url($post['id']))}}" class="profanity post-title">{{ str_limit($post['post_title'], $limit = POST_TITLE_LIMIT, $end = '...') }}</a></h4>
                                                             <div class="user-wrap"> 
                                                                <div class="user-img">
                                                                    @if(empty($post['postUser']['profile_image']) || $post['is_anonymous'] == 1)
                                                                        <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                                                                    @else
                                                                        <img src="{{ asset(PROFILE_PATH.$post['postUser']['profile_image']) }}">
                                                                    @endif
                                                                </div> 
                                                                <p class="user-icon">-<?php if($post['is_anonymous'] == 0) { ?>
                                                                    <a href="{{url('view_profile', Helpers::encode_url($post['postUser']['id']))}}" class="user-a-post">{{$post['postUser']['name']}}</a>
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
                                                                   <a href="#" onclick ="postReadMore({{$post['id']}})">Read More</a>
                                                                </div>
                                                             <?php
                                                                }
                                                             ?>
                                                             <div class="panel-body-wrap">
                                                                 <div class="wrap-social pull-left">
                                                                     <div class="wrap-inner-icon"><a href="javascript:void(0)" id="like_post_{{$post['id']}}" onclick="like_post({{$post['id']}})">
                                                                         <?php
                                                                            if (!empty($post['post_user_like'])) {
                                                                                                    ?>
                                                                             <i class="fa fa-thumbs-up"></i>
                                                                         <?php } else {?>
                                                                             <i class="fa fa-thumbs-o-up"></i>
                                                                         <?php }?>
                                                                         </a>
                                                                         <span id="post_like_count_{{$post['id']}}"><?php echo count($post['post_like']); ?></span>
                                                                     </div>

                                                                     <div class="wrap-inner-icon"><a href="javascript:void(0)" id="dislike_post_{{$post['id']}}" onclick="dislike_post({{$post['id']}})">
                                                                         <?php
if (!empty($post['post_user_dis_like'])) {
			?>
                                                                             <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                                                         <?php } else {?>
                                                                             <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                                                         <?php }?>
                                                                         </a>
                                                                         <span id="post_dislike_count_{{$post['id']}}"><?php echo count($post['post_dis_like']); ?></span>
                                                                     </div>

                                                                     <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>{{$post['post_view_count']}}</span></div>

                                                                     <div class="wrap-inner-icon"><a href="javascript:void(0);">
                                                                         <?php
if (!empty($post['post_comment'])) {
			?>
                                                                                 <i class="fa fa-comments"></i>
                                                                             <?php } else {?>
                                                                                 <i class="fa fa-comments-o"></i>
                                                                             <?php }?>
                                                                         </a></div>
                                                                     <span><?php echo count($post['post_comment']); ?></span>
                                                                 </div>
                                                                 <div class="status pull-right">
                                                                       <p>Status:<span>Active</span></p>
                                                                 </div>
                                                             </div>
                                                             <?php
if (!empty($post['post_tag'])) {
			?>
                                                             <hr>
                                                             <div class="post-circle">
                                                                 <?php foreach ($post['post_tag'] as $post_tag) {?><a href="{{url('tag', Helpers::encode_url($post_tag['tag']['id']))}}"><?=$post_tag['tag']['tag_name'];?></a><?php }?>
                                                              </div>
                                                                 <?php }?>
                                                         </div>
                                                    </div>
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        echo '<p class="postlist">No post found.</p>';
                                    }?>
                                            </div>
                                    </div>
                                    <div tabindex="5002" style="overflow-y: hidden;" class="tab-pane tab-border active" id="users">
                                        <table class="table table-responsive" id="group_users_edit_table">
                                            <thead>
                                            <tr>
                                                <th>Group Users Details</th>
                                                <th>Followings</th>
                                                <th>Followers</th>
                                                <th>Points</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                           @php
                                           /*
                                            <tbody>
                                            @foreach($groupData->groupUsers as $user)
                                                <tr>
                                                    <td><p class="blue">{{ $user->userDetail->name }}<span>{{ $user->userDetail->email }}</span></p></td>
                                                    <td><p>{{ count($user->following) }}<span></span></p></td>
                                                    <td><p>{{ count($user->followers) }}<span></span></p></td>
                                                    <td><p>02<span></span></p></td>
                                                    <td><p><a class="promoteToAdmin {{ ( $groupData->group_owner == $user->user_id OR $user->is_admin == '1') ? 'active-admin' : 'deactive-admin' }}">Make Group Admin<span></span></a></p></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                           */
                                           @endphp
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div><!--wrap -->
    </div><!-- page-content -->
@endsection