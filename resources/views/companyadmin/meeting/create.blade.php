@extends('template.default')
<title>DICO - Meeting</title>
@section('content')
    <div id="page-content" class="new-meeting-details" style="min-height: 943px;">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('meeting.index') }}">Meeting</a></li>
                    <li class="active">Create Meeting</li>
                </ol>
                <h1 class="tp-bp-0">Create Meeting</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                    <form class="common-form" method="POST" name="createMeeting" action="{{ route('meeting.store') }}" id="createMeeting">
                    <div class="col-sm-8" id="post-detail-left">
                        @include('template.notification')
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Type:</label>
                                <div class="check-wrap">
                                    <label class="check">Private
                                        <input class="privacy_type" type="checkbox" name="privacy[]" id="private" value="private">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="check">Public
                                        <input class="privacy_type" type="checkbox" name="privacy[]" id="public" value="public">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meeting_title" >Meeting title:*</label>
                                <input type="text" name="meeting_title" id="meeting_title" class="form-control required"/>
                            </div>
                    
                            <div class="form-group">
                                <label  for="meeting_description"> Meeting description: </label>
                                <textarea name="meeting_description" id="meeting_description"
                                          class="form-control"></textarea>
                            </div>
                            <input type="hidden" name="company_id" value="{{ $company_id }}">
                            <div class="btn-wrap-div">
                                <input type="submit" class="st-btn" value="Submit">
                            </div>
                    </div>
                   
                    <div class="col-sm-4" id="post-detail-right">
                        <h3 class="heading-title">INVITE BY:</h3>
                        <div class="category-meeting">
                            <div class="tab-container tab-success new-meeting-tab">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#home1">Groups </a></li>
                                    <li class=""><a data-toggle="tab" href="#profile1">Members</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane clearfix active" id="home1">
                                        <div class="main-group-wrap">
                                            <div class="category-tab"> 
                                                <label class="check">Groups<input type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="category-detials">
                                                @foreach($groups as $group)
                                                    <label class="check">{{ $group->group_name }}
                                                        <input name="group[]" type="checkbox" value="{{ $group->id }}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                @endforeach
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div id="profile1" class="tab-pane">
                                
                                        <div class="category-tab">
                                                {{--<input type="text" placeholder="Member Name" />--}}
                                                <select name="employees[]" id="employees_listing" class="form-control" multiple="multiple" style="width: 84%">
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                    @endforeach
                                                </select>
                                                <a href="javascript:;" class="st-btn ">ADD</a>
                                            <div class="post-category" id="meeting_users_list">
                                            </div>
                                        </div>
                                        
                                        @php
                                        /*
                                        <div class="post-category" id="meeting_users_list">
                                            <div class="member-wrap">
                                                <div class="member-img">
                                                    <img alt="no" src="assets/img/member1.PNG">
                                                </div>
                                                <div class="member-details">
                                                    <h3 class="text-12">Richardo Ranchet</h3>
                                                    <a href="mailto:ricardo_ranchet@gmail.com">ricardo_ranchet@gmail.com</a>
                                                </div>
                                            </div>
                                        </div>
                                        */
                                        @endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- container -->
@stop