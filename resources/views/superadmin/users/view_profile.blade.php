@extends('template.default')
<title>DICO - Profile</title>
@section('content')

    <div id="page-content" class="profile-page">
        <div id='wrap'>
            <div class="container">
                <div class="row">
                    <div class="follow_box col-sm-12 col-md-2">
                        <div id="follow-box">
                            <div class="preview-box">
                                <?php
$profile_image = '';
if (!empty($user->profile_image)) {
	$profile_image = asset(PROFILE_PATH . $user->profile_image);
} else {
	$profile_image = asset('public/assets/demo/avatar/jackson.png');
}
?>
                                <img src="{{ $profile_image }}" id="user-profile">
                            </div>

                            @if(Auth::user()->id != $user->id)
                                @if(!empty($user->following) && count($user->following) > 0)
                                    @if($user->following[0]->status == 1)
                                        <a href="{{ url('/unfollow/'.$user->id) }}">Unfollow</a>
                                    @else
                                        <a href="{{ url('/follow/'.$user->id) }}"> Follow</a>
                                    @endif
                                @else
                                    <a href="{{ url('/follow/'.$user->id) }}" >Follow</a>
                                @endif
                            @endif
                            <div style="display: none;" class="modal fade" id="followers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Followers({{ (isset($user->followers)) ? count($user->followers) : 0 }})</h3>
                                            <div class="followers-list">
                                                @if(isset($user->followers))
                                                    @foreach($user->followers as $follower)
                                                        <div class="followers-details">
                                                            <div class="follow-img">
                                                                <?php
$profile_image = '';
if (!empty($follower->followUser->profile_image)) {
	$profile_image = asset(PROFILE_PATH . $follower->followUser->profile_image);
} else {
	$profile_image = asset('public/assets/demo/avatar/jackson.png');
}
?>
                                                                <img src="{{ $profile_image }}" alt="followers" >
                                                            </div>
                                                            <div class="follow-name">
                                                                <p>{{ $follower->followUser->name }}</p>
                                                                <a href="mailto:{{ $follower->followUser->email }}">{{ $follower->followUser->email }}</a>
                                                            </div>
                                                            @php
                                                                $str='';
                                                                if($follower->followUser->role_id == '1')
                                                                    $str = 'Super Admin';
                                                                else if($follower->followUser->role_id == '2')
                                                                    $str = 'Company Admin';
                                                                else
                                                                    $str='Employee';
                                                            @endphp
                                                            <p class="jobs-title emp">{{ $str }}</p>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="followers-details">
                                                        <p class="jobs-title emp">No followers yet</p>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>

                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                            <div style="display: none;" class="modal fade" id="following" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Following({{ (isset($user->following)) ? count($user->following) : 0 }})</h3>
                                            <div class="followers-list">
                                                @if(isset($user->following))
                                                    @foreach($user->following as $following)
                                                        <div class="followers-details">
                                                            <div class="follow-img">
                                                                <?php
$profile_image = '';
if (!empty($following->followingUser->profile_image)) {
	$profile_image = asset(PROFILE_PATH . $following->followingUser->profile_image);
} else {
	$profile_image = asset('public/assets/demo/avatar/jackson.png');
}
?>
                                                                <img src="{{ $profile_image }}" alt="followers" >
                                                            </div>
                                                            <div class="follow-name">
                                                                <p>{{ $following->followingUser->name }}</p>
                                                                <a href="mailto:{{ $following->followingUser->email }}">{{ $following->followingUser->email }}</a>
                                                            </div>
                                                            @php
                                                                $str='';
                                                                if($following->followingUser->role_id == '1')
                                                                    $str = 'Super Admin';
                                                                else if($following->followingUser->role_id == '2')
                                                                    $str = 'Company Admin';
                                                                else
                                                                    $str='Employee';
                                                            @endphp
                                                            <p class="jobs-title emp">{{ $str }}</p>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="followers-details">
                                                        <p class="jobs-title emp">No followers yet</p>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>

                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                        </div>
                    </div>
                    <div class="follow-block col-sm-12 col-md-10">
                        <div class="group-box">
                            <div class="group-item one">
                                <p>Name : <span>{{$user->name}}</span></p>
                                <p>Email Id : <span>{{$user->email}}</span></p>
                                <p>Role : <span><a href="#">@if($user->role_id == '2') {{ "Company Admin" }} @elseif($user->role_id == '3') {{ "Employee" }} @else {{ "Super Admin" }} @endif</a></span></p>
                                <p>Points : <span>{{ $points }}</span></p>
                            </div>
                            <div class="group-item two">
                                <h2>{{ isset($user->followers) ? count($user->followers) : 0}}</h2>
                                <p><a href="#followers" data-toggle="modal">Followers</a></p>
                            </div>
                            <div class="group-item three">
                                <h2><h2>{{ isset($user->following) ? count($user->following) : 0}}</h2></h2>
                                <p><a href="#following" data-toggle="modal">Following</a></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="container">

                <div class="row group-listing">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel panel-midnightblue group-tabs profile-user-tabs">
                            <div class="panel-heading">
                                <h4>
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#threads"><i class="fa fa-list visible-xs icon-scale hidden-xs"></i><span>User Groups</span></a></li>
                                    </ul>
                                </h4>
                                <div class="pull-right">
                                    <form method="post" class="search-form">
                                        <input type="hidden" id="user_id" value="{{ $user_id }}" name="">
                                        <input type="text" id="search_group_text" placeholder="Search Group">
                                        <input type="button" value="#" id="search_group_btn" class="search-icon">
                                    </form>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div id="threads" class="tab-pane active" style="overflow-y: hidden;" tabindex="5000">
                                        <div  class="profile-slider owl-carousel" id="view_user_groups">
                                            @if( count($groupDetails) > 0 && !empty($groupDetails))
                                                @foreach($groupDetails as $group)
                                                    <div class="item">
                                                        <div class="list-block">
                                                            <div class="panel-heading">
                                                            </div>
                                                            <div class="panel-body">
                                                                <fieldset>
                                                                    <div class="grid-image">
                                                                        @php
                                                                            if(empty($group->group_image))
                                                                                $group_img = asset('assets/img/business-development.png');
                                                                            else
                                                                                $group_img = asset('assets/img/'.$group->group_image);
                                                                        @endphp
                                                                        <img alt="super-user" src="{{ asset('assets/img/custome-service.png') }}">
                                                                    </div>
                                                                    <div class="grid-details">
                                                                        <h4>{{ $group->group_name }}</h4>
                                                                    </div>
                                                                </fieldset>
                                                                <p class="profanity">
                                                                    {{ $group->description }}
                                                                </p>
                                                                <div class="panel-body-wrap">
                                                                    <div class="follower-text pull-left">
                                                                        <p>Total Posts:<span>{{ $group->total_posts }}</span></p>
                                                                    </div>
                                                                    <div class="follower-text pull-right">
                                                                        <p>Total Members:<span>{{ $group->total_members }}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <p>No data found.</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel panel-midnightblue group-tabs profile-post-tabs">
                            <div class="panel-heading">
                                <h4>
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#threads"><i class="fa fa-list visible-xs icon-scale hidden-xs"></i><span>User Posts</span></a></li>
                                    </ul>
                                </h4>
                                <div class="options">
                                    <div class="btn-toolbar">
                                        <div class="btn-left  hidden-xs">
                                            <div class="btn-group color-changes">
                                                <a style="display: none;" data-toggle="dropdown" class="btn btn-default dropdown-toggle" href="#"><i aria-hidden="true" class="fa fa-filter fa-6"></i><span class="hidden-xs hidden-sm hidden-md">Filter</span> </a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#">Notification off</a></li>
                                                    <li><a href="#">Edit Post</a></li>
                                                    <li><a href="#">Delete Post</a></li>
                                                </ul>
                                            </div>

                                        </div>
                                        <a class="btn-left ">
                                            <form method="post" class="search-form">
                                                <input type="text" placeholder="Search Post" id="search_post_text">
                                                <input type="button" value="#" id="search_post_btn" class="search-icon">
                                            </form>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div id="threads" class="tab-pane active" style="overflow-y: hidden;" tabindex="5000">
                                        <div  class="post-slider owl-carousel" id="view_posts">
                                            @if(count($userPosts))
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
                                                                    <form action="{{ route('deletePost') }}" method="post" id="delete_post_form">
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
                                                                <h4><a class="profanity" href="{{ url('viewpost/'.Helpers::encode_url($post->id)) }}">{{ $post->post_title }}</a></h4>
                                                                <p class="user-icon">-{{ $post->postUser->name }}<span>on {{ date('Y-m-d H:i' , strtotime($post->created_at)) }}</span></p>
                                                                <fieldset>
                                                                    <p class="profanity">{{ $post->post_description }}</p>
                                                                </fieldset>
                                                                <div class="btn-wrap">
                                                                    <a href="#">Read More</a>
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
                                                                        @foreach($post->postTag as $tag)
                                                                            <a href="{{ url('tag/'.Helpers::encode_url($tag->tag->id)) }}"> {{ $tag->tag->tag_name }}</a>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <p>No data found.</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- container -->
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
//        alert($('.sec_question').length);
            //$('.sec_question').trigger('change');
        });
    </script>
@endpush