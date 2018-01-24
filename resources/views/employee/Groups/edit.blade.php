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
                                    <input class="update-button st-btn"
                                           style="position:relative;display: block; width: 100%;" type="submit"
                                           value="Submit" name="">
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
                                <div class="panel-body">
                                    @if(empty($groupData->description))
                                        <p>-</p>
                                    @else
                                        <p>{{ nl2br($groupData->description) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="group-box">
                            <div class="group-item one">
                                <h2>{{ $count['admins'] + 1 }}</h2>
                                <p>Group Admins</p>
                            </div>
                            <div class="group-item two">
                                <h2>{{ $count['total_users'] }}</h2>
                                <p>Group Members</p>
                            </div>
                            <div class="group-item three">
                                <h2>{{ $count['total_posts'] }}</h2>
                                <p>Group Posts</p>
                            </div>
                        </div>
                    </div>
                    <div class="group-right-list col-sm-12 col-md-3">

                        <div class="category">
                            <h2>Group Admins</h2>
                            <div class="post-category">
                                @foreach($groupData->groupUsers as $user)
                                    @if($user->user_id == $groupData->group_owner || $user->is_admin == '1')
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
                                    @endif
                                @endforeach
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
                                            @if(count($userPosts) == 0)
                                                <div class="item">
                                                    <div class="panel-primary panel-1">
                                                        <div class="panel-heading"></div>
                                                        <div class="panel-body">
                                                            <p>Current no post belong to this group.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                @foreach($userPosts as $post)
                                                    <div class="item">
                                                        @php
                                                            $panelClass = '';
                                                            $question_type = $post->post_type;
                                                            if($question_type == 'idea')
                                                                $panelClass = 'panel-1';
                                                            else if($question_type == 'question')
                                                                $panelClass = 'panel-2';
                                                            else
                                                                $panelClass = 'panel-3';
                                                        @endphp

                                                        <div class="{{ $panelClass }} panel-primary">
                                                            <div class="panel-heading">
                                                                <h4 class="icon">{{ ucfirst($question_type) }}</h4>
                                                                <div class="pull-right">
                                                                    <form action="{{ url('/post/index') }}" method="post" id="delete_post_form">
                                                                        <input type="hidden" value="{{ $post->id }}">
                                                                        <a href="#"> <i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                                        @if($post->user_id == Auth::user()->id)
                                                                            <a href="{{ route('post.edit',[$post->id])  }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                            <a href="javascript:;" class="delete_post_btn">
                                                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                            </a>
                                                                        @endif
                                                                    </form>
                                                                </div>

                                                            </div>
                                                            <div class="panel-body">
                                                                <h4>{{ $post->post_title }}</h4>
                                                                <p class="user-icon">-{{ $post->postUser->name }}<span>on {{ date('Y-m-d H:i' , strtotime($post->created_at)) }}</span></p>
                                                                <fieldset>
                                                                    <p>{{ $post->post_description }}</p>
                                                                </fieldset>
                                                                <div class="btn-wrap">
                                                                    <a href="{{ url('viewpost/'.Helpers::encode_url($post->id)) }}">Read More</a>
                                                                </div>
                                                                <div class="panel-body-wrap">
                                                                    <div class="wrap-social pull-left">
                                                                        <div class="wrap-inner-icon"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span>{{ (isset($post->postLike) && count($post->postLike) != 0) ? count($post->postLike) : 0  }}</span></div>
                                                                        <div class="wrap-inner-icon"><i class="fa fa-eye" aria-hidden="true"></i> <span>{{ isset(Helpers::postViews($post->id)['views']) ? Helpers::postViews($post->id)['views'] : 0 }}</span></div>
                                                                        <div class="wrap-inner-icon"><i class="fa fa-comment-o" aria-hidden="true"></i><span>{{ (isset($post->postComment) && count($post->postComment) != 0) ? count($post->postComment) : 0  }}</span></div>
                                                                    </div>
                                                                    <div class="status pull-right">
                                                                        <p>Status:<span>{{ $post->status == '1' ? 'Active' : 'Inactive' }}</span></p>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="post-circle">
                                                                    @if(isset($post->postTag))
                                                                        {{--                                                                @foreach($post->postTag as $tag)--}}
                                                                        <a href="#"> Dummy</a>
                                                                        {{--@endforeach--}}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
/*
<div class="item">
<div class="panel-2 panel-primary">
<div class="panel-heading">
<h4 class="icon">Questions</h4>
<div class="pull-right">
<a href="#"> <i aria-hidden="true" class="fa fa-bell-o"></i></a><a href="#">
<i class="fa fa-pencil" aria-hidden="true"></i></a>
<a href="#"> <i class="fa fa-trash-o" aria-hidden="true"></i></a>
</div>
</div>
<div class="panel-body">
<h4>Lorem lpsum is dummy text</h4>
<p class="user-icon">-Ricardo Ranchet<span>on 24th sep 2017</span></p>
<fieldset>
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
</fieldset>
<div class="btn-wrap">
<a href="question-details.php">Read More</a>
</div>
<div class="panel-body-wrap">
<div class="wrap-social pull-left">
<div class="wrap-inner-icon"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span>106</span></div>
<div class="wrap-inner-icon"><i class="fa fa-eye" aria-hidden="true"></i> <span>19</span></div>
<div class="wrap-inner-icon"><i class="fa fa-comment-o" aria-hidden="true"></i><span>06</span></div>
</div>
<div class="status pull-right">
<p>Status:<span>Active</span></p>
</div>
</div>
<hr>
<div class="post-circle">
<a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">cuckoo's</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">Jane waterman</a>
</div>
</div>
</div>
</div>
<div class="item">
<div class="panel-3 panel-primary">
<div class="panel-heading">
<h4 class="icon">Challenge</h4>
<div class="pull-right">
<a href="#"> <i aria-hidden="true" class="fa fa-bell-o"></i></a><a href="#">
<i class="fa fa-pencil" aria-hidden="true"></i></a>
<a href="#"> <i class="fa fa-trash-o" aria-hidden="true"></i></a>
</div>
</div>
<div class="panel-body">
<h4>Lorem lpsum is dummy text</h4>
<p class="user-icon">-Ricardo Ranchet<span>on 24th sep 2017</span></p>
<fieldset>
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
</fieldset>
<div class="btn-wrap">
<a href="challenges.php">Read More</a>
</div>
<div class="panel-body-wrap">
<div class="wrap-social pull-left">
<div class="wrap-inner-icon"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span>106</span></div>
<div class="wrap-inner-icon"><i class="fa fa-eye" aria-hidden="true"></i> <span>19</span></div>
<div class="wrap-inner-icon"><i class="fa fa-comment-o" aria-hidden="true"></i><span>06</span></div>
</div>
<div class="status pull-right">
<p>Status:<span>Active</span></p>
</div>
</div>
<hr>
<div class="post-circle">
<a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">cuckoo's</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">Jane waterman</a>
</div>
</div>
</div>
</div>
 */
?>
                                                @endforeach
                                            @endif
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