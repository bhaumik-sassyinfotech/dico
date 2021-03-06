@extends('template.default')
<title>@lang("label.DICOGroup")</title>
@section('content')
<div id="page-content" class="main-user-profile user-profile point-page all-group-list  super-user-employee">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ route('/home') }}">@lang("label.adDashboard")</a></li>
                    <li class="active">@lang("label.adGroup")</li>
                </ol>
                <h1 class="tp-bp-0">@lang("label.adGroup")</h1>
                <div class="options" style="display: none;">
                    <div class="btn-toolbar">
                        <div class="btn-group hidden-xs">
                            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i
                                        class="fa fa-filter fa-6" aria-hidden="true"></i><span
                                        class="hidden-xs hidden-sm hidden-md">Filter</span> </a>
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
                                                    <li style="display: none;" class=""><a href="#employee" onclick="groupGrid(1)" data-toggle="tab"
                                                                          data-order="desc1" data-sort="default"
                                                                          class="btn btn-default sort active "><i
                                                                    class="fa fa-list visible-xs icon-scale"></i><span
                                                                    class=" hidden-xs">@lang("label.AllGroups")</span></a></li>
                                                    <li class="active"><a href="#users" onclick="groupGrid(2)" data-toggle="tab" data-order="desc2"
                                                                    data-sort="data-name"
                                                                    class="btn btn-default sort "><span class=""><i
                                                                        class="fa fa-user fa-6 visible-xs"
                                                                        aria-hidden="true"></i><span class=" hidden-xs">@lang("label.Mygroups")</span></a>
                                                    </li>
                                                </ul>

                                                <div class="btn-group top-set">
                                                    <div class="search-form">
                                                        <input id="search_query" type="text" placeholder="@lang('label.SearchGroup')"/>
                                                        <input type="button" value="#" class="search-icon searchBtn searchBt"/>
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
                                            @if(count($groups) > 0)
                                            @foreach($groups as $group)
                                            @php
                                                $class = 'industrial';
                                                if($loop->index % 3 == 1)
                                                    $class = 'nature';
                                                else if($loop->index % 3 == 2)
                                                    $class = 'architecture';
                                            @endphp
    <li data-name="{{ $group['group_name'] }}" class="mix {{ $class }} mix_all userList" style="display: inline-block;  opacity: 1;">
        <div class="list-block super-user">
            <div class="panel-heading">
                <div class="pull-right">
                    <?php $route = route('group.edit',Helpers::encode_url($group['id']));//route('group.edit', [Helpers::encode_url($group['id'])]);?>
                    <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                    <?php
                        if ($group['group_owner'] == Auth::user()->id || Auth::user()->role_id == 1) {
                    ?>
                    <a onclick='window.open("{{ $route }}","_self")' href="{{ $route }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    <?php } ?>
                </div>

            </div>
            <div class="panel-body">
                <fieldset>
                    <div class="grid-image">
                        @php
                            $profile_pic = asset(DEFAULT_GROUP_IMAGE);
                            if( $group['group_image'] != "" )
                                $profile_pic = asset(GROUP_PATH.$group['group_image']);
                        @endphp
                        <img src="{{ $profile_pic }}" alt="super-user">
                    </div>
                    <div class="grid-details">
                        <h4 class="profanity" > <a onclick='window.open("{{ $route }}","_self")' href="{{ route('group.edit',[Helpers::encode_url($group['id'])]) }}">{{ $group['group_name'] }}</a> </h4>
                        <h4 class="profanity" > {{ $group['description']}}</h4>
                    </div>
                </fieldset>
                <hr>
                <div class="panel-body-wrap">
                    <div class="follower-text pull-left">
                        <p>@lang("label.Members"):<span>{{ $group['group_users_count'] }}</span></p>
                    </div>
                    <div class="follower-text pull-right">
                        <p>@lang("label.Posts"):<span>{{ $group['group_posts_count'] }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </li>
    @endforeach
    @if($groups_count > POST_DISPLAY_LIMIT)
    <div class="all_viewmore col-md-12">
        <a href="javascript:void(0)" id="load_post" onclick="loadMorePost()" data-id="0">@lang('label.ViewMore')</a>
    </div>
    @endif
@else
    <div class="col-md-12">
        <p>@lang('label.NoDatafound')</p>
    </div>
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
                                                            <div class="panel panel-info">
                                                                <div class="panel-heading trophy">
                                                                    <h4 class="icon">@lang("label.GroupList")</h4>
                                                                    <div class="pull-right">
                                                                       
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                            <div class="row">
                                                                <table class="table" id="group_table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th><label>
                                                                              <p>@lang("label.GroupName")</p>
                                                                           </label></th>
                                                                        <th>@lang("label.GroupDescription")</th>
                                                                        <th>@lang("label.TotalPosts")</th>
                                                                        <th>@lang("label.TotalMembers")</th>
                                                                        <th>@lang("label.Actions")</th>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                                <?php /*<div class="notice">
                                                            <div class="action notice-left"><p>Action</p></div>
                                                            <div class="select notice-left">
                                                                <select name="slct" id="slct">
                                                                    <option value="delete">Delete</option>
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
                                            <div tabindex="5002" class="tab-pane" id="users">
                                        <div class="container">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-info ">
                                                        <div class="panel-heading trophy">
                                                            <h4 class="icon">@lang("label.GroupList")</h4>
                                                            <div class="pull-right">
                                                                <a href="#"><img src="{{ asset('assets/img/settings-icon.png') }}" alt="settings"></a>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <table class="table" id="group_table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th><label class="check checkAll">
                                                                              <p>@lang("label.GroupName")</p>
                                                                               <input type="checkbox" class="checkAllBox">
                                                                                <span class="checkmark"></span>
                                                                           </label></th>
                                                                        <th>@lang("label.GroupDescription")</th>
                                                                        <th>@lang("label.TotalPosts")</th>
                                                                        <th>@lang("label.TotalMembers")</th>
                                                                        <th>@lang("label.Actions")</th>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                                <div class="notice">
                                                            <div class="action notice-left"><p>@lang("label.adAction")</p></div>
                                                            <div class="select notice-left">
                                                                <select name="slct" id="slct">
                                                                  <option>@lang("label.Delete")</option>
                                                                  <option value="Super User">@lang("label.SuperUser")</option>
                                                                   <option value="Employee">@lang("label.Employee")</option>
                                                                   <option value="Admin">@lang("label.Admin")</option>
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
            var searchText = $.trim($('#search_query').val());
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            // console.log(formData);
            var new_url = '/group/mygroups';


            $.ajax({
                url: SITE_URL+'/'+LANG+new_url,
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

        function search() {
            // alert("hiii");
            var offSet_selector = $('#offset');
            var id = offSet_selector.val();
            var offset = 0;
            var searchText = $.trim($('#search_query').val());
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            // console.log(formData);
            var new_url = '/group/mygroups';


            $.ajax({
                url: SITE_URL+'/'+LANG+new_url,
                type: "POST",
                data: formData,
                async:true,
                success: function (response)
                {
                    if(response != "")
                    {
                        if(response.html != "")
                        {
                            $("#display-grid").empty();
                            $("#display-grid").append(response.html);
                            offSet_selector.attr('value',0);
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
         $(".searchBt").click(function(){
                groupTable.draw();
                search();
            });
    </script>
@endpush