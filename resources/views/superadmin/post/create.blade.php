@extends('template.default')
<title>DICO - Meeting</title>
@section('content')
    <div id="page-content" class="new-meeting-details" style="min-height: 931px;">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('meeting.index') }}">Meeting</a></li>
                    <li class="active">New Meeting</li>
                </ol>
                <h1 class="tp-bp-0">New Meeting</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                    <form class="common-form" method="POST" name="createMeeting" action="{{ route('meeting.store') }}" id="createMeeting" enctype="multipart/form-data">
                    <div class="col-sm-8" id="post-detail-left">
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Type*</label>
                                <div class="check-wrap">
                                    <div class="check">Private
                                        <input type="checkbox" name="privacy" id="private" value="private" class="privacy_type check post_type error">
                                        <label for="private" class="checkmark"></label>      
                                    </div>
                                    <div class="check">Public
                                        <input type="checkbox" name="privacy" id="public" value="public" class="privacy_type check post_type error">
                                        <label for="public" class="checkmark"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meeting_title" >Meeting title*</label>
                                <input type="text" name="meeting_title" id="meeting_title" class="form-control required" placeholder="Meeting title"/>
                            </div>
                    
                            <div class="form-group">
                                <label  for="meeting_description">Meeting description</label>
                                <textarea name="meeting_description" id="meeting_description" class="form-control" placeholder="Meeting description"></textarea>
                            </div>
                            
                            <div class="form-group calendar">
                                <label>Date Of Meet:</label>  
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="text" name="date_of_meet" id="date_of_meet" placeholder="12-09-2017 | (9:30)" class="form-control">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" name="company_id" value="{{ $company_id }}">
                            <div class="btn-wrap-div">
                                <input type="submit" class="st-btn" value="Submit">
                                <div class="upload-btn-wrapper">
                                    <button class="upload-btn">Upload Files</button>
                                    <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                </div>
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
                                            <div class="category-tab tp-bp-0"> 
                                                <label class="check">Groups<input type="checkbox" name="user_groups_all" id="checkAll">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="category-detials">
                                                @foreach($groups as $group)
                                                <label class="check" style="display: block !important;">{{ $group->group_name }}
                                                        <input name="group[]" type="checkbox" id="group_{{$group->id}}" value="{{ $group->id }}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div id="profile1" class="tab-pane">
                                        <div class="category-tab">
                                            {{--<input type="text" placeholder="Member Name" />--}}
                                            <select name="employees[]" id="employees_listing" class="form-control" style="width: 84%">
                                                <option value="-1">---Select---</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="submit" name="add_employee" id="add_employee" value="ADD" class="st-btn">
                                        </div>    
                                        <div class="post-category" id="meeting_users_list">
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
@push('javascripts')
<script type="text/javascript">
$('#datetimepicker1').datetimepicker();    
$("#checkAll").click(function () {
    $("input[name*='group[]']").not(this).prop('checked', this.checked);
});
</script>
@endpush        