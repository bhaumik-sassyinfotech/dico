@extends('template.default')
@section('content')

    <div id="page-content" class="group-listing meetings" style="min-height: 650px;">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="active">Meetings</li>
                </ol>
                <h1 class="tp-bp-0">Meetings</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <a class="btn btn-default" href="{{ route('meeting.create') }}">
                            <i aria-hidden="true" class="fa fa-pencil-square-o fa-6"></i>
                            <span class="hidden-xs hidden-sm">New Meeting</span>
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
                                        <li class="active"><a href="#users" data-toggle="tab"><i class="fa fa-list visible-xs icon-scale"></i><span class="hidden-xs">All Meetings</span></a></li>
                                        <li class=""><a  href="#threads" data-toggle="tab"><i class="fa fa-group visible-xs icon-scale"></i><span class="hidden-xs">My Meetings</span></a></li>
                                    </ul>
                                </h4>
                                <div class="pull-right">
                                    <form class="search-form" method="post">
                                        <input type="text" placeholder="Search Meeting">
                                        <input type="button" class="search-icon" value="#">
                                    </form>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div tabindex="5000" class="tab-pane active" id="threads">
                                        @foreach($myMeetings as $meeting)
                                            <?php
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
                                            <div class="col-md-4">
                                                <div class="{{ $class }} panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 class="icon">{{ $type }} Meeting</h4>
                                                        <div class="pull-right">
                                                            <a href="#"> <i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4><a href="{{ route('meeting.show',[Helpers::encode_url($meeting->id)]) }}">{{ $meeting->meeting_title }}</a></h4>
                                                        <p class="user-icon"> - {{ $meeting->meetingCreator->name }}<span>on {{ date(DATE_FORMAT,strtotime($meeting->created_at)) }}</span></p>
                                                        <fieldset>
                                                            <p class="text-12 desc-content">{{ nl2br($meeting->meeting_description) }}</p>
                                                        </fieldset>
                                                        <div class="btn-wrap">
                                                            <a href="#">Read More</a>
                                                        </div>
                                                        <hr>
                                                        <div class="panel-body-wrap">
                                                            <div class="member pull-left">
                                                                <p>Members:<span>{{ $meeting->meeting_users_count }}</span></p>
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
                                                                <p>Status:<span class="{{ $cls }}">{{ $txt }}</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                            
                                    <div tabindex="5002" style="overflow-y: hidden;" class="tab-pane" id="users">
                                        @foreach($allMeetings as $meeting)
                                            <?php
                                            $class = '';
                                            $type = '';
                                            if ( $meeting->privacy == '1' ) { //private
                                                $class = 'meeting-2';
                                                $type = 'Private';
                                            } else {
                                                $class = 'meetings-1';
                                                $type = 'Public';
                                            }
                                            ?>
                                            <div class="col-md-4">
                                                <div class="{{ $class }} panel-primary">
                                                    <div class="panel-heading">
                    
                                                        <h4 class="icon">{{ $type }} Meeting</h4>
                                                        <div class="pull-right">
                                                            <a href="#"> <i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-exclamation-triangle"
                                                                           aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4><a href="{{ route('meeting.show',[Helpers::encode_url($meeting->id)]) }}">{{ $meeting->meeting_title }}</a></h4>
                                                        <p class="user-icon"> - {{ $meeting->meetingCreator->name }}<span>on {{ date(DATE_FORMAT,strtotime($meeting->created_at)) }}</span></p>
                                                        <fieldset>
                                                            <p class="text-12">{{ nl2br($meeting->meeting_description) }}</p>
                                                        </fieldset>
                                                        <div class="btn-wrap">
                                                            <a href="#">Read More</a>
                                                        </div>
                                                        <hr>
                                                        <div class="panel-body-wrap">
                                                            <div class="member pull-left">
                                                                <p>Members:<span>{{ $meeting->meeting_users_count }}</span></p>
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
                                                                <p>Status:<span class="{{ $cls }}">{{ $txt }}</span></p>
                                                            </div>
                                                        </div>
                
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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