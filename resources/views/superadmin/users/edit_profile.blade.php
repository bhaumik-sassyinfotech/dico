@extends('template.default')
<title>DICO - Profile</title>
@section('content')

@if(session()->has('success'))
<div class="alert alert-success">
    {{ session()->get('success') }}
</div>
@endif
@if(session()->has('err_msg'))
<div class="alert alert-danger">
    {{ session()->get('err_msg') }}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <h1>Update Profile</h1>
        </div>    
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="domtabs">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="tab-container tab-danger">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#general_tab" data-toggle="tab">General</a></li>
                                            <?php if ($user->role_id != 1) { ?>
                                                <li><a href="#security_tab" data-toggle="tab">Security</a></li>
                                            <?php } ?>
                                            <li><a href="#changepassword_tab" data-toggle="tab">Change Password</a></li>
                                            <?php if ($user->role_id != 1) { ?>
                                            <li><a href="#notification_tab" data-toggle="tab">Notification</a></li><?php }  ?>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="general_tab">
                                                <form name="general_profile_form" id="general_profile_form" method="post" action="{{url('update_profile')}}" enctype="multipart/form-data">
                                                    {{ csrf_field() }}
                                                    <?php
                                                    if (!empty($user->profile_image)) {
                                                        $profile_image = 'public/uploads/profile_pic/' . $user->profile_image;
                                                    } else {
                                                        $profile_image = 'public/assets/demo/avatar/jackson.png';
                                                    }
                                                    ?>
                                                    <img src="{{asset($profile_image)}}" id="profile" alt="" class="pull-left" height="100px" width="100px" style="margin: 0 20px 20px 0">
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <span class="btn btn-primary fileinput-button">
                                                                <i class="fa fa-upload"></i>
                                                                <span>upload</span>
                                                                <input type="file" name="profile_image" id="profile_image" accept="image/x-png,image/gif,image/jpeg" class="file-upload__input" onchange="javascript:uploadimage(this, 'profile');">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label>Name<span>*</span></label>
                                                            <input type="text" name="name" id="name" placeholder="Name" value="{{$user->name}}" class="form-control required">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label>Email<span>*</span></label>
                                                            <input type="text" name="email" id="email" placeholder="Email" value="{{$user->email}}" class="form-control required">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label>Google Link</label>
                                                            <input type="url" name="google_link" id="google_link" placeholder="http://www.google.com" value="{{$user->google_id}}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label>LinkedIn Link</label>
                                                            <input type="url" name="linkedin_link" id="linkedin_link" placeholder="http://www.linkedin.com" value="{{$user->linkedin_id}}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="new_button">
                                                        <div class="pull-right extra_button">
                                                            <input type="submit" name="save" id="save" class="btn btn-primary">
                                                        </div>
                                                        <div style="clear: both;"></div>
                                                    </div>
                                                </form>
                                            </div>
                                            <?php if ($user->role_id != 1) { ?>
                                                <div class="tab-pane" id="security_tab">
                                                    <form name="security_profile_form" id="security_profile_form" method="post" action="{{url('security_update_profile')}}" enctype="multipart/form-data">
                                                        {{ csrf_field() }}
                                                        <div class="row">
                                                            <div class="col-xs-8 form-group">
                                                                <label>Question 1<span>*</span></label>
                                                                <select name="question_1" id="question_1" class="form-control required sec_question">
                                                                    <option value="">Security Question 1</option>
                                                                    @if($questions->count() > 0)
                                                                    @foreach($questions as $question)
                                                                    <?php
                                                                    if (@$userquestions[1]->question_id == $question->id || @$userquestions[2]->question_id == $question->id) {
                                                                        $style = "style='display:none;'";
                                                                    } else {
                                                                        $style = "style='display:block;'";
                                                                    }
                                                                    ?>
                                                                    <option value="{{ $question->id }}" <?= $style; ?> <?php
                                                                    if (@$userquestions[0]->question_id == $question->id) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>>{{ $question->question }}</option>
    <?php //}   ?>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-4 form-group">
                                                                <label>Answer 1<span>*</span></label>
                                                                <input type="text" name="answer_1" id="answer_1" placeholder="Answer 1" value="{{@$userquestions[0]->answer}}" class="form-control required">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-8 form-group">
                                                                <label>Question 2<span>*</span></label>
                                                                <select name="question_2" id="question_2" class="form-control required sec_question">
                                                                    <option value="">Security Question 2</option>
                                                                    @if($questions->count() > 0)
                                                                    @foreach($questions as $question)
                                                                    <?php
                                                                    if (@$userquestions[0]->question_id == $question->id || @$userquestions[2]->question_id == $question->id) {
                                                                        $style = "style='display:none;'";
                                                                    } else {
                                                                        $style = "style='display:block;'";
                                                                    }
                                                                    ?>
                                                                    <option value="{{ $question->id }}" <?= $style ?> <?php
                                                                            if (@$userquestions[1]->question_id == $question->id) {
                                                                                echo "selected";
                                                                            }
                                                                            ?>>{{ $question->question }}</option>
    <?php //}   ?>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-4 form-group">
                                                                <label>Answer 2<span>*</span></label>
                                                                <input type="text" name="answer_2" id="answer_2" value="{{@$userquestions[1]->answer}}" placeholder="Answer 2" class="form-control required">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-8 form-group">
                                                                <label>Question 3<span>*</span></label>
                                                                <select name="question_3" id="question_3" class="form-control required sec_question">
                                                                    <option value="">Security Question 3</option>
                                                                    @if($questions->count() > 0)
                                                                    @foreach($questions as $question)
                                                                    <?php
                                                                    if (@$userquestions[0]->question_id == $question->id || @$userquestions[1]->question_id == $question->id) {
                                                                        $style = "style='display:none;'";
                                                                    } else {
                                                                        $style = "style='display:block;'";
                                                                    }
                                                                    ?>
                                                                    <option <?= $style; ?> value="{{ $question->id }}" <?php
                                                                                       if (@$userquestions[2]->question_id == $question->id) {
                                                                                           echo "selected";
                                                                                       }
                                                                                       ?>>{{ $question->question }}</option>
    <?php //}   ?>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-4 form-group">
                                                                <label>Answer 3<span>*</span></label>
                                                                <input type="text" name="answer_3" id="answer_3" value="{{@$userquestions[2]->answer}}" placeholder="Answer 3" class="form-control required">
                                                            </div>
                                                        </div>
                                                        <div class="new_button">
                                                            <div class="pull-right extra_button">
                                                                <input type="submit" name="save" id="save" class="btn btn-primary">
                                                            </div>
                                                            <div style="clear: both;"></div>
                                                        </div>
                                                    </form>
                                                </div>
<?php } ?>
                                            <div class="tab-pane" id="changepassword_tab">
                                                <form name="changepassword_form" id="changepassword_form" method="post" action="{{url('changepassword_update_profile')}}">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label>Old Password</label>
                                                            <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control required">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label>New Password</label>
                                                            <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control required">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label>Confirm Password</label>
                                                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control required">
                                                        </div>
                                                    </div>
                                                    <div class="new_button">
                                                        <div class="pull-right extra_button">
                                                            <input type="submit" name="save" id="save" class="btn btn-primary">
                                                        </div>
                                                        <div style="clear: both;"></div>
                                                    </div>
                                                </form>
                                            </div>    

                                            <div class="tab-pane" id="notification_tab">
                                                <form name="notification_form" id="notification_form" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading"><h4>Email Notification</h4></div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="no_email" id="no_email">&nbsp;<label>No Email</label>
                                                                </div>
                                                            
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="instant_email" id="instant_email">&nbsp;<label>Instant Email</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="every_15_minutes" id="every_15_minutes">&nbsp;<label>Every 15 minutes</label>
                                                                </div>
                                                            
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="once_per_day" id="once_per_day">&nbsp;<label>Once per day</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-heading"><h4>Post Notification</h4></div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="post_created" id="post_created">&nbsp;<label>When a post is created in my group</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="vote_idea_post_created" id="vote_idea_post_created">&nbsp;<label>When someone votes on an idea post I created</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="dicision_idea_post_created" id="dicision_idea_post_created">&nbsp;<label> When a decision is made on an idea post I created</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="dicision_idea_post_voted" id="dicision_idea_post_voted">&nbsp;<label>When a decision is made on an idea post I voted</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="like_post_created" id="like_post_created">&nbsp;<label>When someone likes a post I created</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="like_comment_created" id="like_comment_created">&nbsp;<label>When someone likes a comment I created</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="reply_comment_created" id="reply_comment_created">&nbsp;<label>When someone replies to a comment I created</label>
                                                                </div>
                                                               <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="reports_post_created" id="reports_post_created">&nbsp;<label>When someone reports a post I created</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="reposts_comment_created" id="reposts_comment_created">&nbsp;<label>When someone reposts a comment I created</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="follow_me" id="follow_me">&nbsp;<label>When someone follows me</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="comment_marked_correct" id="comment_marked_correct">&nbsp;<label>When my comment is marked as the correct answer</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="comment_marked_correct_participated" id="comment_marked_correct_participated">&nbsp;<label>When a comment is marked correct on a post I participated</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="comment_as_solution" id="comment_as_solution">&nbsp;<label>When my comment is marked as the solution</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="comment_as_solution_participated" id="comment_as_solution_participated">&nbsp;<label>When a comment is marked as solution on a post I participated</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="post_participated_edit_delete" id="post_participated_edit_delete">&nbsp;<label>When a post I participated on is edited or deleted</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="comment_participated_edit_delete" id="comment_participated_edit_delete">&nbsp;<label>When a comment I participated on is edited or deleted</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="invited_meeting" id="invited_meeting">&nbsp;<label>When I'm invited to a meeting</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="member_finalized" id="member_finalized">&nbsp;<label>When a meeting I'm a member of is finalized</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="comment_meeting" id="comment_meeting">&nbsp;<label>When a new comment is added to a meeting</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="file_add_meeting" id="file_add_meeting">&nbsp;<label>When a file is added to a meeting</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="new_member_meeting" id="new_member_meeting">&nbsp;<label>When a new member is added to a meeting</label>
                                                                </div>
                                                                <div class="col-xs-6 form-group">
                                                                    <input type="checkbox" name="remove_member_meeting" id="remove_member_meeting">&nbsp;<label>When a member is removed or leaves a meeting</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="new_button">
                                                            <div class="pull-right extra_button">
                                                                <input type="submit" name="save" id="save" class="btn btn-primary">
                                                            </div>
                                                            <div style="clear: both;"></div>
                                                        </div>
                                                        
                                                    </div>
                                                </form>
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
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
//        alert($('.sec_question').length);
        //$('.sec_question').trigger('change');
    });
</script>
@endpush