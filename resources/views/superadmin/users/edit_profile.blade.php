@extends('template.default')
<title>DICO - Profile</title>
@section('content')
    <div id="page-content" class="employe-profile">
        <div id='wrap'>
            <div class="container">
                @include('template.notification')
                <div class="row">
                    <form name="general_profile_form" id="upload_form" method="post" action=" {{ url('update_profile') }} " enctype="multipart/form-data">
                        <div class="follow_box form_box col-sm-12 col-md-2">
                            <input accept="image/x-png,image/gif,image/jpeg" type="file" class="fileinput" name="profile_image" id="image">
                            <label>Upload Photo</label>
                            <div class="preview_box">
                                <?php
                                $pic = '';
                                if($user->profile_image == "")
                                    $pic = asset('assets/img/emplye-image.png');
                                else
                                    $pic = asset('public/uploads/profile_pic/'.$user->profile_image);
                                ?>
                                <img src="{{ $pic }}" id="preview_img">
                            </div>
                        
                        </div>
                        <div class="follow-block col-sm-12 col-md-10">
                            <div class="group-box">
                                <div class="group-item one">
                                    <p>Name : <span>{{ $user->name }}</span></p>
                                    <p>Email Id : <span>{{ $user->email }}</span></p>
                                    @php
                                        $role = 'Super Admin';
                                        
                                    if($user->role_id == '2')
                                        $role = 'Company Manager';
                                    else
                                        $role = 'Employee';
                                    @endphp
                                    <p>Role : <span><a href="#">{{ $role }}</a></span></p>
                                </div>
                                <div class="group-item two">
                                    <h2>{{ count($user->followers) }}</h2>
                                    <p>Followers</p>
                                </div>
                                <div class="group-item three">
                                    <h2>{{ count($user->followings  ) }}</h2>
                                    <p>Following</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="employ-box col-sm-12 col-md-6">
                        <ul class="ul-emp-detail">
                            <li><a href="javascript:;" data-toggle="modal" data-target="#changeEmail">Change Email Id <span><i aria-hidden="true"
                                                                                                                               class="fa fa-angle-right fa-6"></i></span></a></li>
                            <div id="changeEmail" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Change Details</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form name="general_profile_form" id="general_profile_form" method="post" action="{{ url('update_profile') }}" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Name</label>
                                                        <input type="text" name="name" id="name" placeholder="Name" value="{{$user->name}}" class="form-control required">
                                                    </div>
                                                    
                                                    <div class="col-xs-12">
                                                        <label>Email</label>
                                                        <input type="text" name="email" id="email" placeholder="Email" value="{{$user->email}}" class="form-control required">
                                                    </div>
                                                    <input type="submit" class="st-btn" value="Submit">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            <li><a href="javascript:;" data-toggle="modal" data-target="#changePassword">Change Password <span><i aria-hidden="true"
                                                                                                                                  class="fa fa-angle-right fa-6"></i></span></a></li>
                            <div id="changePassword" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Change Password</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form name="general_profile_form" id="general_profile_form" method="post" action="{{ url('update_profile') }}" enctype="multipart/form-data">
                                                <div class="row">
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
                                                        <input type="submit" name="save" id="save" class="btn btn-primary">
                                                    </form>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            @if ($user->role_id != 1)
                                <li><a href="javascript:;" data-toggle="modal" data-target="#updateSecurityQuestion">Update
                                        Security Question <span><i aria-hidden="true"
                                                                   class="fa fa-angle-right fa-6"></i></span></a></li>
                                
                                <div id="updateSecurityQuestion" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">Change Password</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="general_profile_form" id="general_profile_form"
                                                      method="post" action="{{ url('update_profile') }}"
                                                      enctype="multipart/form-data">
                                                    <div class="row">
                                                        <form name="security_profile_form" id="security_profile_form"
                                                              method="post" action="{{url('security_update_profile')}}"
                                                              enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <div class="row">
                                                                <div class="col-xs-8 form-group">
                                                                    <label>Question 1<span>*</span></label>
                                                                    <select name="question_1" id="question_1"
                                                                            class="form-control required sec_question">
                                                                        <option value="">Security Question 1</option>
                                                                        @if($questions->count() > 0)
                                                                            @foreach($questions as $question)
                                                                                <?php
                                                                                if ( @$userquestions[ 1 ]->question_id == $question->id || @$userquestions[ 2 ]->question_id == $question->id )
                                                                                {
                                                                                    $style = "style='display:none;'";
                                                                                } else
                                                                                {
                                                                                    $style = "style='display:block;'";
                                                                                }
                                                                                ?>
                                                                                <option value="{{ $question->id }}" <?= $style; ?> <?php
                                                                                    if ( @$userquestions[ 0 ]->question_id == $question->id )
                                                                                    {
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
                                                                    <input type="text" name="answer_1" id="answer_1"
                                                                           placeholder="Answer 1"
                                                                           value="{{@$userquestions[0]->answer}}"
                                                                           class="form-control required">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-8 form-group">
                                                                    <label>Question 2<span>*</span></label>
                                                                    <select name="question_2" id="question_2"
                                                                            class="form-control required sec_question">
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
                                                                    <input type="text" name="answer_2" id="answer_2"
                                                                           value="{{@$userquestions[1]->answer}}"
                                                                           placeholder="Answer 2"
                                                                           class="form-control required">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-8 form-group">
                                                                    <label>Question 3<span>*</span></label>
                                                                    <select name="question_3" id="question_3"
                                                                            class="form-control required sec_question">
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
                                                                                <option
                                                                                    <?= $style; ?> value="{{ $question->id }}" <?php
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
                                                                    <input type="text" name="answer_3" id="answer_3"
                                                                           value="{{@$userquestions[2]->answer}}"
                                                                           placeholder="Answer 3"
                                                                           class="form-control required">
                                                                </div>
                                                            </div>
                                                            <div class="new_button">
                                                                <div class="pull-right extra_button">
                                                                    <input type="submit" name="save" id="save"
                                                                           class="btn btn-primary">
                                                                </div>
                                                                <div style="clear: both;"></div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            @endif
                        </ul>
                    </div>
                    <div class="employ-box col-sm-12 col-md-6">
                        <h2>Notification</h2>
                        <ul class="ul-checkbox">
                            
                            <li>
                                <label class="check">
                                    <p>In Mail </p>
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                            <li>
                                <label class="check">
                                    <p>Instant Mail</p>
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                            <li>
                                <label class="check">
                                    <p>Every 15 Mins</p>
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                            
                            <li>
                                <label class="check">
                                    <p>Once Per Day</p>
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                        
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container">
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-info ">
                            <div class="panel-heading trophy">
                                <h4 class="icon">My Points</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Idea</th>
                                            <th>Questions</th>
                                            <th>Approved</th>
                                            <th>Answers</th>
                                            <th>Solutions</th>
                                            <th>Comments</th>
                                            <th>Likes</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>234</td>
                                            <td>150</td>
                                            <td>68</td>
                                            <td>148</td>
                                            <td>258</td>
                                            <td>48</td>
                                            <td>25</td>
                                            <td>685</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
@endsection
