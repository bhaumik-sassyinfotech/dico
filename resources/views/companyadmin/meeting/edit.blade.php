@extends('template.default')
<title>DICO - Meeting</title>
@section('content')
<div id="page-content" class="new-meeting-details">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ route('meeting.index') }}">Meeting</a></li>
                <li class="active">Edit Meeting</li>
            </ol>
            <h1 class="tp-bp-0">Edit Meeting</h1>
            <hr class="border-out-hr">
        </div>
        <div class="container">
            <div class="row">
               <!-- <div id="company-from">-->
                {!! Form::open(['method' => 'PUT', 'route' => ['meeting.update', Helpers::encode_url($meeting->id)],'enctype'=>'multipart/form-data', 'id' => 'meeting_edit_form' , 'class' => 'common-form']) !!}
                <div class="col-sm-8" id="post-detail-left">    
                    <div class="form-group">
                        <label>Type*</label>
                        <div class="check-wrap">
                            <label class="check">Private
                                <input class="privacy_type post_type" type="checkbox" {{ ($meeting->privacy == '1') ? 'checked':'' }} name="privacy" id="private" value="private">
                                <span class="checkmark"></span>
                            </label>
                            <label class="check">Public
                                <input class="privacy_type post_type" type="checkbox" {{ ($meeting->privacy == '0') ? 'checked':'' }} name="privacy" id="public" value="public">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-15">Meeting Title<span>*</span></label>
                        <input type="text" name="meeting_title" id="meeting_title" value="{{$meeting->meeting_title}}" placeholder="Meeting title" class="form-control required">
                    </div>

                    <div class="form-group">
                        <label class="text-15">Meeting Description</label>
                        <textarea name="meeting_description" id="meeting_description" placeholder="Meeting Description" class="form-control">{{ nl2br($meeting->meeting_description) }}</textarea>
                    </div>
                
                    <div class="form-group calendar">
                        <label>Date Of Meet:</label>   
                        <div class='input-group date' id='datetimepicker1'>
                            <?php
                                $date_of_meet = !empty($meeting->date_of_meeting) ? date(DATETIME_FORMAT,strtotime($meeting->date_of_meeting)) : '';
                            ?>
                            <input type="text" name="date_of_meet" id="date_of_meet" value="<?=$date_of_meet?>" placeholder="<?=$date_of_meet?>" style="pointer-events: none;" class="form-control">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>

                    <div class="btn-wrap-div">
                    <input type="submit" class="st-btn" value="Submit">
                    <?php /*<div class="upload-btn-wrapper">
                        <button class="upload-btn">Upload Files</button>
                        <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                    </div>*/?>
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
                                                <?php
                                                    if(!empty($meeting->group_id)) {
                                                    $meeting_group = explode(",",$meeting->group_id);
                                                    } else {
                                                        $meeting_group = array();
                                                    }
                                                ?>
                                            @foreach($groups as $group)
                                            <label class="check" style="display: block !important;">{{ $group->group_name }}
                                                    <input name="group[]" type="checkbox" <?php if(in_array($group->id,$meeting_group)) { echo "checked";}?> id="group_{{$group->id}}" value="{{ $group->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            @endforeach   
                                            </div>   
                                    </div>   
                                    </div>        
                                    <div id="profile1" class="tab-pane">

                                        <div class="category-tab">   
                                            <?php
                                                if(!empty($meeting->meetingUsers)) {
                                                    $meetingUsers = array_pluck($meeting->meetingUsers,'user_id');
                                                } else {
                                                    $meetingUsers = array();
                                                }
                                            ?>
                                                <select name="employees[]" id="employees_listing" class="form-control" style="width: 84%">
                                                <option value="-1">---Select---</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}" <?php if(in_array($employee->id,$meetingUsers)) { echo "disabled";} ?>>{{ $employee->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="submit" name="add_employee" id="add_employee" value="ADD" class="st-btn">
                                                <input type="hidden" id="selected_members" name="selected_members">
                                          </div> 
                                        <div class="post-category" id="meeting_users_list">
                                            <?php
                                                //dd($meeting->meetingUsers);
                                                if(!empty($meeting->meetingUsers) && count($meeting->meetingUsers) > 0) {
                                                    foreach ($meeting->meetingUsers as $meetingUsers) {
                                                        $userDetail = $meetingUsers->UserDetail;
                                                        if($meetingUsers->group_id != 0) {
                                            ?>
                                            <div class="member-wrap" id="user_<?php echo $userDetail->id;?>">
                                                <div class="member-img">
                                                    <?php
                                                        if (!empty($userDetail->profile_image)) {
                                                            $profile_image = PROFILE_PATH . $userDetail->profile_image;
                                                        } else {
                                                            //$profile_image = 'public/assets/demo/avatar/jackson.png';
                                                            $profile_image = DEFAULT_PROFILE_IMAGE;
                                                        }
                                                    ?>
                                                    <img alt="no" src="{{asset($profile_image)}}">
                                                </div>
                                                <div class="member-details">
                                                    <h3 class="text-12">{{$userDetail->name}}</h3>
                                                    <a href="mailto:{{$userDetail->email}}">{{$userDetail->email}}</a>
                                                </div>
                                                <a href="javascript:void(0)" onclick="removeMember({{$userDetail->id}})" class="close-button-small"></a>
                                            </div>
                                            <?php } } } ?> 
                                        </div> 

                                    </div>
                                </div>
                                </div>
                                </div>  
                                <div class="category files">
                  <h2>Uploaded Files</h2>
                  <div class="wrap-name-upload">
                        <div class="select">
                            <select id="slct" name="slct">
                                    <option>Name</option>
                                    <option>Admin</option>
                                    <option value="Super User">Super User</option>
                                    <option value="Employee">Employee</option>
                            </select>
                        </div>
                        <input type="hidden" name="company_id" value="{{ $company_id }}">
                        <div class="upload-btn-wrapper">
                                    <form name="uploadfile" id="uploadfile" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="meetingId" id="meetingId" value="{{$meeting['id']}}">
                                        <button class="btn" id="uploadBtn">Upload File</button>
                                        <input name="file_upload" id="file_upload" type="file" onchange="uploadFileMeeting();">
                                    </form>
                                </div>
                            </div>
                            <div class="idea-grp post-category" id="meetingAttachment">
                        <?php
                            if(!empty($meeting->meetingAttachment) && count($meeting->meetingAttachment) > 0) {
                            foreach($meeting->meetingAttachment as $attachment) {
                        ?>
                        <div class="member-wrap files-upload">

                            <div class="member-img">
                                <img src="{{asset(DEFAULT_ATTACHMENT_IMAGE)}}" alt="no">
                            </div>
                            <div class="member-details">
                                <h3 class="text-10">{{$attachment->file_name}}</h3>
                                <p>Uploaded By:<a href="#">{{$attachment->attachmentUser->name}}</a></p>
                            </div>
                        </div>    
                            <?php } }
                                else {
                                    echo "<p class='text-12'>No files uploaded.</p>";
                                }
                            ?>
                    </div>  
                </div> 
                </div>
                {!! Form::close() !!}
                
               <!-- </div>-->
            </div>
        </div>
    </div>
</div>
@stop
@push('javascripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var selected_members = [];
            $('#meeting_users_list div.member-wrap').each(function() {
                var userid = $(this).attr('id');
                var user = userid.split("_").slice(-1);
                selected_members.push(user[0]);
            });
            $('#selected_members').val(selected_members);
        });
        $('#datetimepicker1').datetimepicker({
           // minDate : new Date(),
        });
        $("#meeting_edit_form").validate({
            rules:{
                'meeting_title':{
                    required: true,
                }
            },
            submitHandler: function (form) {
                $('.save').prop('disabled',true);
                form.submit();
            }
        });
    </script>
@endpush