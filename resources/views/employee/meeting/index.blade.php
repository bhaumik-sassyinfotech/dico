@extends('template.default')
<title>@lang("label.DICOMeeting")</title>
@section('content')

    <div id="page-content" class="group-listing meetings" style="min-height: 650px;">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ route('/home') }}">@lang("label.adDashboard")</a></li>
                    <li class="active">@lang("label.adMeetings")</li>
                </ol>
                <h1 class="tp-bp-0">@lang("label.adMeetings")</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <a class="btn btn-default" href="{{ route('meeting.create') }}">
                            <i aria-hidden="true" class="fa fa-pencil-square-o fa-6"></i>
                            <span class="hidden-xs hidden-sm">@lang("label.NewMeeting")</span>
                        </a>
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
                                        <li class="active"><a href="#threads" data-toggle="tab"><i class="fa fa-list visible-xs icon-scale"></i><span class="hidden-xs">@lang("label.AllMeetings")</span></a></li>
                                        <li class=""><a  href="#users" data-toggle="tab"><i class="fa fa-group visible-xs icon-scale"></i><span class="hidden-xs">@lang("label.MyMeetings")</span></a></li>
                                    </ul>
                                </h4>
                                <div class="pull-right search-form">
                                    <!-- <form class="search-form" method="post">-->
                                    <input type="text" name="search_text" id="search_text" placeholder="Search Meeting">
                                    <input type="button" value="#" onclick="searchMeeting();" class="search-icon">
                                    <!-- </form>-->
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div tabindex="5000" class="tab-pane active" id="threads">
                                        <?php 
                                            if(!empty($allMeetings) && count($allMeetings) > 0) {
                                                foreach($allMeetings as $meeting) {
                                                    $class = '';
                                                    $type = '';
                                                    if ( $meeting->privacy == '1' ) { //private
                                                        $class = 'meetings-2';
                                                        $type = 'Private';
                                                    } else {
                                                        $class = 'meetings-1';
                                                        $type = 'Public';
                                                    }
                                        ?>
                                        <div class="col-md-4 allmeetinglist">
                                            <div class="{{ $class }} panel-primary">
                                                <div class="panel-heading">
                                                    <h4 class="icon">{{ $type }} @lang("label.Meeting")</h4>
                                                    <div class="pull-right">
                                                        <a href="#"> <i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                        <!-- <a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>-->
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <h4><a href="{{ route('meeting.show',[Helpers::encode_url($meeting->id)]) }}" class="profanity post-title">{{ $meeting->meeting_title }}</a></h4>
                                                    <p class="user-icon"> - <a href="{{route('view_profile', Helpers::encode_url($meeting->meetingCreator->id))}}" class="user-a-post">{{ $meeting->meetingCreator->name }}</a><span>@lang("label.on") {{ date(DATE_FORMAT,strtotime($meeting->created_at)) }}</span></p>
                                                    <fieldset>
                                                        <p class="text-12 desc-content profanity" id="desc_content_{{$meeting->id}}">{{ nl2br($meeting->meeting_description) }}</p>
                                                    </fieldset>
                                                    <?php
                                                        if(strlen($meeting->meeting_description) > POST_DESCRIPTION_LIMIT) {
                                                    ?>
                                                    <div class="btn-wrap" id="meetingread{{$meeting->id}}">
                                                        <a href="javascript:void(0)" onclick="ReadMore('desc_content_{{$meeting->id}}','meetingread{{$meeting->id}}')">@lang("label.ReadMore")</a>
                                                    </div>
                                                        <?php } ?>
                                                    <hr>
                                                    <div class="panel-body-wrap">
                                                        <div class="member pull-left">
                                                            <p>@lang("label.Members"):<span>{{ $meeting->meeting_users_count }}</span></p>
                                                        </div>
                                                        <div class="status pull-right">
                                                            <?php
                                                            if($meeting->is_finalized == '0')
                                                            {
                                                                $cls = 'active';
                                                                $txt = 'Active';
                                                            }else{
                                                                $cls = 'inactive error';
                                                                $txt = 'Finalized';
                                                            }
                                                            ?>
                                                            <p>@lang("label.Status"):<span class="{{ $cls }}">{{ $txt }}</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                                }
                                            } else {
                                                echo __("label.Nomeetingfound");
                                            }
                                        ?>
                                        <input type="hidden" id="count_allMeetings" value="{{$count_allMeetings}}">
                                        <?php
                                            if (!empty($count_allMeetings) && $count_allMeetings > POST_DISPLAY_LIMIT) {
                                        ?>
                                            <div class="all_viewmore col-md-12"><a href="javascript:void(0)" id="load_allmeeting" onclick="loadAllMeeting();" data-id="0">@lang("label.ViewMore")</a></div>
                                        <?php } ?>
                                    </div>
                            
                                    <div tabindex="5002" class="tab-pane" id="users">
                                        <?php
                                            if(!empty($myMeetings) && count($myMeetings) > 0) {
                                                foreach($myMeetings as $meeting) {
                                                    $class = '';
                                                    $type = '';
                                                    if ( $meeting->privacy == '1' ) { //private
                                                        $class = 'meetings-2';
                                                        $type = 'Private';
                                                    } else {
                                                        $class = 'meetings-1';
                                                        $type = 'Public';
                                                    }
                                            ?>
                                            <div class="col-md-4 mymeetinglist">
                                                <div class="{{ $class }} panel-primary">
                                                    <div class="panel-heading">
                    
                                                        <h4 class="icon">{{ $type }} @lang("label.Meeting")</h4>
                                                        <div class="pull-right">
                                                            <a href="#"> <i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <!-- <a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>-->
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4><a href="{{ route('meeting.show',[Helpers::encode_url($meeting->id)]) }}" class="profanity post-title">{{ $meeting->meeting_title }}</a></h4>
                                                        <p class="user-icon"> - <a href="{{url('view_profile', Helpers::encode_url($meeting->meetingCreator->id))}}" class="user-a-post">{{ $meeting->meetingCreator->name }}<span>@lang("label.on") {{ date(DATE_FORMAT,strtotime($meeting->created_at)) }}</span></a></p>
                                                        <fieldset>
                                                            <p class="text-12 profanity" id="desc_mycontent_{{$meeting->id}}">{{ nl2br($meeting->meeting_description) }}</p>
                                                        </fieldset>
                                                        <?php
                                                        if(strlen($meeting->meeting_description) > POST_DESCRIPTION_LIMIT) {
                                                        ?>
                                                        <div class="btn-wrap" id="meetingread{{$meeting->id}}">
                                                            <a href="javascript:void(0)" onclick="ReadMore('desc_mycontent_{{$meeting->id}}','meetingread{{$meeting->id}}')">@lang("label.ReadMore")</a>
                                                        </div>
                                                        <?php } ?>
                                                        <hr>
                                                        <div class="panel-body-wrap">
                                                            <div class="member pull-left">
                                                                <p>@lang("label.Members"):<span>{{ $meeting->meeting_users_count }}</span></p>
                                                            </div>
                                                            <div class="status pull-right">
                                                                <?php
                                                                if($meeting->is_finalized == '0')
                                                                {
                                                                    $cls = 'active';
                                                                    $txt = 'Active';
                                                                }else{
                                                                    $cls = 'inactive error';
                                                                    $txt = 'Finalized';
                                                                }
                                                                ?>
                                                                <p>@lang("label.Status"):<span class="{{ $cls }}">{{ $txt }}</span></p>
                                                            </div>
                                                        </div>
                
                                                    </div>
                                                </div>
                                            </div>
                                            <?php 
                                                    }
                                                } else {
                                                    echo __("label.Nomeetingfound");
                                                }
                                            ?>
                                        <input type="hidden" id="count_myMeetings" value="{{$count_myMeetings}}">
                                        <?php
                                        if (!empty($count_myMeetings) && $count_myMeetings > POST_DISPLAY_LIMIT) {
                                        ?>
                                            <div class="my_viewmore col-md-12"><a href="javascript:void(0)" id="load_mymeeting" onclick="loadMyMeeting();" data-id="0">@lang("label.ViewMore")</a></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
<script type="text/javascript">
    function loadAllMeeting() {
        var id = $('#load_allmeeting').attr('data-id');
        var offset = parseInt(id) + {{POST_DISPLAY_LIMIT}};
        //var count_post = $('#count_post').val();
        var searchText = $('#search_text').val();
        var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
        $("#spinner").show();
        $.ajax({
            url: SITE_URL+'/'+LANG+"/loadmoreallmeeting",
            type: "POST",
            data: formData,
            success: function (response) {
                $("#spinner").hide();
                if(response != "") {
                    if(response.html != "") {
                        $('#threads .allmeetinglist:last').after(response.html);
                        runProfanity();
                        $('#load_allmeeting').attr('data-id',offset);
                        if($('.allmeetinglist').length == response.count) {
                            $('#load_allmeeting').hide();
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
    function loadMyMeeting() {
        var id = $('#load_mymeeting').attr('data-id');
        var offset = parseInt(id) + {{POST_DISPLAY_LIMIT}};
        //var count_post = $('#count_post').val();
        var searchText = $('#search_text').val();
        var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
        $("#spinner").show();
        $.ajax({
            url: SITE_URL+'/'+LANG+"/loadmoremymeeting",
            type: "POST",
            data: formData,
            success: function (response) {
                $("#spinner").hide();
                if(response != "") {
                    if(response.html != "") {
                        $('#users .mymeetinglist:last').after(response.html);
                        runProfanity();
                        $('#load_mymeeting').attr('data-id',offset);
                        if($('.mymeetinglist').length == response.count) {
                            $('#load_mymeeting').hide();
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
    function searchMeeting() {
        if($('#threads').hasClass('active')) {
            var id = $('#load_allmeeting').attr('data-id');
            var offset = 0;
            var searchText = $('#search_text').val();
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL+'/'+LANG+"/loadmoreallmeeting",
                type: "POST",
                data: formData,
                success: function (response) {
                    //console.log(response.html);
                    $("#spinner").hide();
                    if(response != ""){
                        if(response.html != "") {
                            $('#threads .allmeetinglist').remove();
                            //$('#count_post').remove();
                            $('#threads #count_allMeetings').before(response.html);
                            runProfanity();
                            $('#load_allmeeting').attr('data-id',offset);
                            //console.log(response.count+":::"+{{POST_DISPLAY_LIMIT}});
                            if(response.count <= {{POST_DISPLAY_LIMIT}}) {
                                $('#load_allmeeting').hide();
                            } else {
                                $('#load_allmeeting').show();
                            }
                        } else {
                            $('#threads .allmeetinglist').remove();
                            $('#threads').append("<p class='allmeetinglist'>{{__('label.Nomeetingfound')}}</p>");
                            $('#load_allmeeting').hide();

                        }
                    }
                },
                error: function() {
                }
            });
        } else if($('#users').hasClass('active')) {
            var id = $('#load_mymeeting').attr('data-id');
            var offset = 0;
            var searchText = $('#search_text').val();
            var formData = {offset:offset,_token : CSRF_TOKEN,search_text:searchText};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL+'/'+LANG+"/loadmoremymeeting",
                type: "POST",
                data: formData,
                success: function (response) {
                    $("#spinner").hide();
                    if(response != '') {
                        if(response.html != "") {
                            $('#users .mymeetinglist').remove();
                            $('#users #count_myMeetings').before(response.html);
                            runProfanity();
                            $('#load_mymeeting').attr('data-id',offset);
                            if(response.count <= {{POST_DISPLAY_LIMIT}}) {
                                $('#load_mymeeting').hide();
                            } else {
                                $('#load_mymeeting').show();
                            }
                        } else {
                            $('#users .mymeetinglist').remove();
                            $('#users').append("<p class='mymeetinglist'>{{__('label.Nomeetingfound')}}</p>");
                            $('#load_mymeeting').hide();

                        }
                    }
                },
                error: function() {
                }
            });
        }
    }
</script>    