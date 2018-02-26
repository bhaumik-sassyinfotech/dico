@extends('template.default')
<title>@lang("label.DICOUser")</title>
@section('content')
    <div id="page-content" class="main-user-profile user-profile point-page all-group-list  super-user-employee user-table">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ route('/home') }}">@lang("label.adDashboard")</a></li>
                    <li class="active">@lang("label.adUser")</li>
                </ol>
                <h1 class="tp-bp-0">@lang("label.Users")</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <a class="btn btn-default" style="display: none;" href="{{ route('user.create') }}">
                            <i aria-hidden="true" class="fa fa-pencil-square-o fa-6"></i>
                            <span class="hidden-xs hidden-sm">@lang("label.CreateUser")</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-danger">
                            <div class="panel-body no-gapping">
                                <div class="row no-gapping">

                                    <div class="col-md-12 no-gapping">
                                        <div class="border-tabs">
                                            <div class="btn-toolbar form-group clearfix">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#employee" onclick="superUserGrid(1)" data-toggle="tab" data-order="desc1" data-sort="default" class="btn btn-default sort active ">
                                                            <i class="fa fa-list visible-xs icon-scale"></i><span class=" hidden-xs">@lang("label.Employee")</span></a></li>
                                                    <li class=""><a href="#users" onclick="superUserGrid(2)" data-toggle="tab" data-order="desc2"
                                                                    data-sort="data-name"
                                                                    class="btn btn-default sort "><span class=""><i
                                                                        class="fa fa-user fa-6 visible-xs"
                                                                        aria-hidden="true"></i><span class=" hidden-xs">@lang("label.GroupAdmin")</span></a>
                                                    </li>
                                                    <li class=""><a href="#other-managers" onclick="superUserGrid(3)" data-toggle="tab"
                                                                    data-order="asc" data-sort="data-name"
                                                                    class="btn btn-default sort "><span class=""><i
                                                                        class="fa fa-group visible-xs" aria=""
                                                                        hidden="true"></i><span class=" hidden-xs">@lang("label.OtherManagers")</span></a>
                                                    </li>
                                                </ul>

                                                <div class="btn-group top-set search-form">
                                                    <div method="post" class="search-form">
                                                        <input id="search_query" type="text" placeholder="@lang('label.SearchUser')"/>
                                                        <input type="button" id="search_btn" value="#" class="search-icon"/>
                                                    </div>
                                                    <button id="GoList" class="grid-view">
                                                        <img src="{{asset('assets/img/icon/group-list.png')}}" alt="group"
                                                             class="block">
                                                        <img src="{{asset('assets/img/icon/group-lis-hover.png')}}" alt="group"
                                                             class="none" style="display:none">
                                                    </button>
                                                    <button id="GoGrid" class="grid-view active">
                                                        <img src="{{asset('assets/img/icon/grid-view.png')}}" alt="group list"
                                                             class="block">
                                                        <img src="{{asset('assets/img/icon/grid-view-hover.png')}}" alt="group list"
                                                             class="none" style="display:none">

                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Employee GRID START -->
                                        <input type="hidden" name="offset" id="offset" data-tab="1" value="0">
                                        <ul class="gallery list-unstyled" id="display-grid">
                                            <?php if(count($users) > 0) { ?>
                                                @foreach($users as $user)
                                                @php
                                                    $class = 'industrial';
                                                    if($loop->index % 3 == 1)
                                                        $class = 'nature';
                                                    else if($loop->index % 3 == 2)
                                                        $class = 'architecture';
                                                @endphp
                                                <li data-name="{{ $user->name }}" class="mix {{ $class }} mix_all userList" style="display: inline-block;  opacity: 1;">
                                                    <div class="list-block super-user">
                                                        <div class="panel-heading">
                                                            <div class="pull-right">
                                                              <?php /*  <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                                <?php
                                                                    $edit_url = route('user.edit', Helpers::encode_url($user->id));
                                                                ?>
                                                                <a href="{{$edit_url}}" onclick="window.open('<?=$edit_url?>','_self')"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>*/?>
                                                            </div>

                                                        </div>
                                                        <div class="panel-body">
                                                            <fieldset>
                                                                <div class="grid-image">
                                                                    @php
                                                                        $profile_pic = asset(DEFAULT_PROFILE_IMAGE);
                                                                        if($user->profile_image != "")
                                                                            $profile_pic = asset(PROFILE_PATH.$user->profile_image);
                                                                    @endphp
                                                                    <img src="{{ $profile_pic }}" alt="super-user">
                                                                </div>
                                                                <div class="grid-details">
                                                                    <h4><a onclick="window.open('<?= route('view_profile', Helpers::encode_url($user->id))?>','_self')" href="{{route('view_profile', Helpers::encode_url($user->id))}}">{{ $user->name }}</a></h4>
                                                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                                                    <h4>@lang("label.Employee")</h4>
                                                                </div>

                                                            </fieldset>
                                                            <div class="btn-wrap">
                                                                @php
                                                                    $text = "";
                                                                    if(!empty($user->followers) && count($user->followers) > 0)
                                                                    {
                                                                        $text = __('label.Following');
                                                                    } else {
                                                                        $text = __('label.Follow');
                                                                    }
                                                                    $url = route('view_profile',Helpers::encode_url($user->id));
                                                                @endphp
                                                                <a onclick="window.open('{{ $url }}' ,'_self')" href="{{ $url }}">{{ $text }}</a>
                                                                <?php $pts = Helpers::user_points($user->id);?>
                                                                <a href="#">@lang("label.Point"):{{ $pts['points'] }}</a>

                                                            </div>
                                                            <div class="panel-body-wrap">
                                                                <div class="follower-text pull-left">
                                                                    <p>@lang("label.Followers"):<span>{{ $user->followers_count }}</span></p>
                                                                </div>
                                                                <div class="follower-text pull-right">
                                                                    <p>@lang("label.Following"):<span>{{ count($user->following) }}</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforeach
                                                @if($users_count > POST_DISPLAY_LIMIT)
                                                <div class="all_viewmore col-md-12">
                                                    <a href="javascript:void(0)" id="load_post" onclick="loadMoreUser()" data-id="0">@lang("label.ViewMore")</a>
                                                </div>
                                                @endif

                                            <?php } else { echo __('label.NoDatafound');}?>
                                        </ul>
                                        <input type="hidden" name="role_id" id="role_id" value="3">
                                        <input type="hidden" name="superadmin_user_listing" id="page_name" value="superadmin_user_listing">
                                        <!-- Employee GRID END -->
                                        <div class="hide-table none">
                                            <div tabindex="5000"
                                                 class="tab-pane active employee-tab" id="employee">
                                                <div class="container">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-info" style="overflow-x:auto;">
                                                                <div class="panel-heading trophy">
                                                                    <h4 class="icon">@lang("label.UsersList")</h4>
                                                                    <div class="pull-right">
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table" id="emp-table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>
                                                                                        <label>@lang("label.UserName")<!-- <input type="checkbox" class="checkAllBox">
                                                                                            <span class="checkmark"></span>-->
                                                                                        </label>
                                                                                    </th>
                                                                                    <th><label>@lang("label.UserEmailId")</label></th>
                                                                                    <th><label>@lang("label.Role")</label></th>
                                                                                    <th><label>@lang("label.Following")</label></th>
                                                                                    <th><label>@lang("label.Followers")</label></th>
                                                                                    <th><label>@lang("label.adPoints")</label></th>
                                                                                    <th style="display: none;"><label>@lang("label.adAction")</label></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>
                                                                        </table>
                                                                        <?php /*<div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct" class="action">
                                                                                    <option value="active">Active</option>
                                                                                    <option value="inactive">Inactive</option>
                                                                                    <option value="suspend">Suspend</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="action notice-left" style="padding: 0px 10px; ">
                                                                               <input type="button" name="" class="st-btn multiple-action" value="Submit">
                                                                            </div>
                                                                        </div>*/?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div tabindex="5002"  class="tab-pane"
                                                 id="users">
                                                <div class="container">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-info" style="overflow-x:auto;">
                                                                <div class="panel-heading trophy">
                                                                    <h4 class="icon">@lang("label.UsersList")</h4>
                                                                    <div class="pull-right">
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table" id="company-manager-table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    <label>@lang("label.GroupManagerDetails")<!-- <input type="checkbox" class="checkAllBox">
                                                                                        <span class="checkmark"></span>-->
                                                                                    </label>
                                                                                </th>
                                                                                <th><label>@lang("label.Role")</label></th>
                                                                                <th><label>@lang("label.Groups")</label></th>
                                                                                <th><label>@lang("label.Following")</label></th>
                                                                                <th><label>@lang("label.Followers")</label></th>
                                                                                <th><label>@lang("label.adPoints")</label></th>
                                                                                <th style="display: none;"><label>@lang("label.adAction")</label></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>

                                                                        </table>
                                                                        <?php /*<div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct" class="action">
                                                                                    <option value="active">Active</option>
                                                                                    <option value="inactive">Inactive</option>
                                                                                    <option value="suspend">Suspend</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="action notice-left" style="padding: 0px 10px; ">
                                                                               <input type="button" name="" class="st-btn multiple-action" value="Submit">
                                                                            </div>
                                                                        </div>*/?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div tabindex="5002" class="tab-pane" id="other-managers">
                                                <div class="container">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-info" style="overflow-x:auto;">
                                                                <div class="panel-heading trophy">
                                                                    <h4 class="icon">@lang("label.UsersList")</h4>
                                                                    <div class="pull-right">
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table" id="other-managers-table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    <label>@lang("label.UserName")</label>
                                                                                </th>
                                                                                <th><label>@lang("label.UserEmailId")</label></th>
                                                                                <th><label>@lang("label.Role")</label></th>
                                                                                <th><label>@lang("label.Position")</label></th>
                                                                                <th><label>@lang("label.Following")</label></th>
                                                                                <th><label>@lang("label.Followers")</label></th>
                                                                                <th><label>@lang("label.adPoints")</label></th>
                                                                                <th style="display: none;"><label>@lang("label.adAction")</label></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
                                                                        <?php /*<div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct" class="action">
                                                                                    <option value="active">Active</option>
                                                                                    <option value="inactive">Inactive</option>
                                                                                    <option value="suspend">Suspend</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="action notice-left" style="padding: 0px 10px; ">
                                                                               <input type="button" name="" class="st-btn multiple-action" value="Submit">
                                                                            </div>
                                                                        </div>*/?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!--col-lg-12-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascripts')
    <script type="text/javascript">
        function loadMoreUser() {
            $('#spinner').show();
            var offSet_selector = $('#offset');
            var tab_id = offSet_selector.attr('data-tab');
            //alert(tab_id);
            var id = offSet_selector.val();
            var offset = parseInt(id) + {{POST_DISPLAY_LIMIT}};
            var searchText = $('#search_text').val();
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            //console.log(formData);
            if(tab_id == 1)
                var new_url = '/user/employeeGrid';
            if(tab_id == 2)
                var new_url = '/user/adminGrid';
            else if(tab_id == 3)
                var new_url = '/user/otherManagersGrid';
            $.ajax({
                url: SITE_URL +'/'+LANG+new_url,
                type: "POST",
                data: formData,
                async:true,
                success: function (response)
                {
                    $('#spinner').hide();
                    if(response != "")
                    {
                        if(response.html != "")
                        {
                            // $("#display-grid").empty();
                            $("#display-grid li.userList:last").after(response.html);
                            offSet_selector.attr('value',offset);
                            if( $('.userList').length == response.count )
                            {
                                $('#load_post').hide();
                                $(".all_viewmore").hide();
                            }
                            $(".all_viewmore").not(':last').remove();
                        } else {
                            //$('#threads .postlist:last').after("No post found.");
                        }
                    }
                },
                error: function() {
                }
            });
        }
    </script>
@endpush