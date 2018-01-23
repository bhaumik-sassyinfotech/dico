@extends('template.default')
<title>DICO - User</title>
@section('content')

    <div id="page-content" class="main-user-profile user-profile point-page all-group-list  super-user-employee">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="active">User</li>
                </ol>
                <h1 class="tp-bp-0">Users</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <a class="btn btn-default" href="{{ route('user.create') }}">
                            <i aria-hidden="true" class="fa fa-pencil-square-o fa-6"></i>
                            <span class="hidden-xs hidden-sm">Create User</span>
                        </a>
                        <a class="btn btn-default">
                            <i class="fa fa-sort fa-6" aria-hidden="true"></i>
                            <span class="hidden-xs hidden-sm">Sort</span>
                        </a>
                        <div class="btn-group">
                            <div class="btn-group color-changes">
                                <a data-toggle="dropdown" class="btn btn-default dropdown-toggle" href="#"><i
                                            aria-hidden="true" class="fa fa-filter fa-6"></i><span
                                            class="hidden-xs hidden-sm">Filter</span> </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Notification off</a></li>
                                    <li><a href="#">Edit Post</a></li>
                                    <li><a href="#">Delete Post</a></li>
                                </ul>
                            </div>
                        </div>

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
                                                    <li class="active"><a href="#employee" onclick="superUserGrid(1)" data-toggle="tab"
                                                                          data-order="desc1" data-sort="default"
                                                                          class="btn btn-default sort active "><i
                                                                    class="fa fa-list visible-xs icon-scale"></i><span
                                                                    class=" hidden-xs">Employee</span></a></li>
                                                    <li class=""><a href="#users" onclick="superUserGrid(2)" data-toggle="tab" data-order="desc2"
                                                                    data-sort="data-name"
                                                                    class="btn btn-default sort "><span class=""><i
                                                                        class="fa fa-user fa-6 visible-xs"
                                                                        aria-hidden="true"></i><span class=" hidden-xs">Group Admin</span></a>
                                                    </li>
                                                    <li class=""><a href="#other-managers" onclick="superUserGrid(3)" data-toggle="tab"
                                                                    data-order="asc" data-sort="data-name"
                                                                    class="btn btn-default sort "><span class=""><i
                                                                        class="fa fa-group visible-xs" aria=""
                                                                        hidden="true"></i><span class=" hidden-xs">Other Managers</span></a>
                                                    </li>
                                                </ul>

                                                <div class="btn-group top-set">
                                                    <form method="post" class="search-form">
                                                        <input id="search_query" type="text" placeholder="Search User"/>
                                                        <input type="button" id="search_btn" value="#" class="search-icon"/>
                                                    </form>
                                                    <button id="GoList" class="grid-view">
                                                        <img src="assets/img/icon/group-list.png" alt="group"
                                                             class="block">
                                                        <img src="assets/img/icon/group-lis-hover.png" alt="group"
                                                             class="none" style="display:none">
                                                    </button>
                                                    <button id="GoGrid" class="grid-view active">
                                                        <img src="assets/img/icon/grid-view.png" alt="group list"
                                                             class="block">
                                                        <img src="assets/img/icon/grid-view-hover.png" alt="group list"
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
                                            @if(count($users) > 0)
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
                                                                <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                                <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                            </div>

                                                        </div>
                                                        <div class="panel-body">
                                                            <fieldset>
                                                                <div class="grid-image">
                                                                    @php
                                                                        $profile_pic = asset('assets/img/super-user.PNG');
                                                                        if($user->profile_image != "")
                                                                            $profile_pic = asset(PROFILE_PATH.$user->profile_image);
                                                                    @endphp
                                                                    <img src="{{ $profile_pic }}" alt="super-user">
                                                                </div>
                                                                <div class="grid-details">
                                                                    <h4>{{ $user->name }}</h4>
                                                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                                                    <h4>Employee</h4>
                                                                </div>

                                                            </fieldset>
                                                            <div class="btn-wrap">
                                                                <a href="#">Follow</a>
                                                                <a href="#">Point:246</a>

                                                            </div>
                                                            <div class="panel-body-wrap">
                                                                <div class="follower-text pull-left">
                                                                    <p>Followers:<span>{{ count($user->followers) }}</span></p>
                                                                </div>
                                                                <div class="follower-text pull-right">
                                                                    <p>Following:<span>{{ count($user->following) }}</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforeach
                                                @if($users_count > POST_DISPLAY_LIMIT)
                                                <div class="all_viewmore col-md-12">
                                                    <a href="javascript:void(0)" id="load_post" onclick="loadMorePost()" data-id="0">View More</a>
                                                </div>
                                                @endif

                                            @endif
                                        </ul>
                                        <input type="hidden" name="role_id" id="role_id" value="3">
                                        <!-- Employee GRID END -->
                                        <div class="hide-table none">
                                            <div tabindex="5000"
                                                 class="tab-pane active employee-tab" id="employee">
                                                <div class="container">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-info" style="overflow-x:auto;">
                                                                <div class="panel-heading trophy">
                                                                    <h4 class="icon">Users List</h4>
                                                                    <div class="pull-right">
                                                                        <a href="settings.php"><img
                                                                                    src="assets/img/settings-icon.png"
                                                                                    alt="settings"></a>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table" id="emp-table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>
                                                                                        <label class="check">User Name<input
                                                                                                    type="checkbox">
                                                                                            <span class="checkmark"></span>
                                                                                        </label>
                                                                                    </th>
                                                                                    <th><label>User Email Id</label></th>
                                                                                    <th><label>Role</label></th>
                                                                                    <th><label>Following</label></th>
                                                                                    <th><label>Followers</label></th>
                                                                                    <th><label>Points</label></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct">
                                                                                    <option>Edit</option>
                                                                                    <option value="Super User">Super
                                                                                        User
                                                                                    </option>
                                                                                    <option value="Employee">Employee
                                                                                    </option>
                                                                                    <option value="Admin">Admin</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
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
                                                                    <h4 class="icon">Users List</h4>
                                                                    <div class="pull-right">
                                                                        <a href="settings.php"><img
                                                                                    src="assets/img/settings-icon.png"
                                                                                    alt="settings"></a>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table" id="company-manager">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    <label class="check">Group Manager
                                                                                        Details<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </th>
                                                                                <th><label>Role</label></th>
                                                                                <th><label>Manager Of</label></th>
                                                                                <th><label>Groups</label></th>
                                                                                <th><label>Following</label></th>
                                                                                <th><label>Followers</label></th>
                                                                                <th><label>Points</label></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Jason
                                                                                        Durelo<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>Business Development Manager</p>
                                                                                </td>
                                                                                <td><p>Design &amp; Tech, Marketing,
                                                                                        Business Development</p></td>
                                                                                <td><p>Maekting, Customer Service, Lead
                                                                                        Generation</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>6854</p></td>

                                                                            </tr>
                                                                            </tbody>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Melllisa
                                                                                        McCarty<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>Business Development Manager</p>
                                                                                </td>
                                                                                <td><p>Design &amp; Tech, Marketing,
                                                                                        Business Development</p></td>
                                                                                <td><p>Maekting, Customer Service, Lead
                                                                                        Generation</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>6854</p></td>

                                                                            </tr>
                                                                            </tbody>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Vennesa
                                                                                        Jay<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>Business Development Manager</p>
                                                                                </td>
                                                                                <td><p>Design &amp; Tech, Marketing,
                                                                                        Business Development</p></td>
                                                                                <td><p>Maekting, Customer Service, Lead
                                                                                        Generation</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>6854</p></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct">
                                                                                    <option>Edit</option>
                                                                                    <option value="Super User">Super
                                                                                        User
                                                                                    </option>
                                                                                    <option value="Employee">Employee
                                                                                    </option>
                                                                                    <option value="Admin">Admin</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
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
                                                                    <h4 class="icon">Users List</h4>
                                                                    <div class="pull-right">
                                                                        <a href="settings.php"><img
                                                                                    src="assets/img/settings-icon.png"
                                                                                    alt="settings"></a>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table" id="other-managers-table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    <label class="check">User Name<input
                                                                                                type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </th>
                                                                                <th><label>User Email Id</label></th>
                                                                                <th><label>Role</label></th>
                                                                                <th><label>Position</label></th>
                                                                                <th><label>Following</label></th>
                                                                                <th><label>Followers</label></th>
                                                                                <th><label>Points</label></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
                                                                        <div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct">
                                                                                    <option>Edit</option>
                                                                                    <option value="Super User">Super
                                                                                        User
                                                                                    </option>
                                                                                    <option value="Employee">Employee
                                                                                    </option>
                                                                                    <option value="Admin">Admin</option>
                                                                                </select>
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
        function loadMorePost() {
            var offSet_selector = $('#offset');
            var tab_id = offSet_selector.data('tab');
            var id = offSet_selector.val();
            var offset = parseInt(id) + {{POST_DISPLAY_LIMIT}};
            var searchText = $('#search_text').val();
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            console.log(formData);
            var new_url = '/user/employeeGrid';

            if(tab_id == 2)
                new_url = '/user/adminGrid';
            else if(tab_id == 3)
                new_url = '/user/otherManagersGrid';

            $.ajax({
                url: SITE_URL+new_url,
                type: "POST",
                data: formData,
                async:true,
                success: function (response)
                {
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