@extends('template.default')
@section('content')
    
    <div id="page-content" class="group-listing">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('group.index') }}">Group</a></li>
                    <li class="active">Update Group</li>
                </ol>
                <h1 class="tp-bp-0">Update Group</h1>
            </div>
            
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-2">
                        <form id="upload_form" method="post" action="{{ url('group/uploadGroupPicture') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $groupData->id }}" name="group_id">
                            <div class="update-wrap">
                                <input type="file" id="image" name="group_picture" class="fileinput">
                                <label>Upload New Photo</label>
                                <div class="preview_box">
                                    @php
                                        $img = asset('assets/img/upload-image.png');
                                        if($groupData->group_image != "")
                                            $img = asset('public/uploads/groups/'.$groupData->group_image);
                                    @endphp
                                    <img id="preview_img" src="{{ $img }}">
                                </div>
                            </div>
                            <input class="update-button st-btn" style="position:relative;display: block; width: 100%;" type="submit" value="Submit" name="">
    
                        </form>
                        
                    </div>
                    <div class="group-left-list col-sm-12 col-md-7">
                        <div class="panel panel-midnightblue">
                            <div class="panel-heading">
                                <h4>Group Description:</h4>
                                <div class="pull-right">
                                    <a href="#"><img  src="{{ asset('assets/img/notification.png') }}" alt="notification"></a>
                                    <a href="#"><img  src="{{ asset('assets/img/add-agent.png') }}" alt="add user"></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <p>{{ nl2br($groupData->description) }}</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="group_id" value="{{ $groupId }}">
                    <input type="hidden" id="company_id" value="{{ $groupData->company_id }}">
                    <div class="group-right-list col-sm-12 col-md-3">
                        
                        <div class="category">
                            <h2>Group Admins</h2>
                            <div class="post-category">
                                @foreach($groupData->groupUsers as $user)
                                    @if($user->user_id == $groupData->group_owner || $user->is_admin == '1')
                                        <div class="member-wrap">
                                            <div class="member-img">
                                                <img src="{{ asset('assets/img/member1.PNG') }}" alt="no">
                                            </div>
                                            <div class="member-details">
                                                <h3 class="text-12">Richardo Ranchet</h3>
                                                <a href="mailto:ricardo_ranchet@gmail.com">ricardo_ranchet@gmail.com</a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    
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
                                        <li class=""><a href="#threads" data-toggle="tab"><i class="fa fa-list visible-xs icon-scale"></i><span class="hidden-xs">Posts</span></a></li>
                                        <li class="active"><a href="#users" data-toggle="tab"><i class="fa fa-comments visible-xs icon-scale"></i><span class="hidden-xs">Group Members</span></a></li>
                                    </ul>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div tabindex="5000" style="overflow-y: hidden;" class="tab-pane" id="threads">
                                        <div  class="post-slider owl-carousel">
                                            <div class="item">
                                                <div class="panel-1 panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 class="icon">Ideas</h4>
                                                        <div class="pull-right">
                                                            <a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-pencil"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4>lorem lpsum is dummy text</h4>
                                                        <p class="user-icon">-Ricardo Ranchet<span>on 24th sep 2017</span></p>
                                                        <fieldset>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                                                        </fieldset>
                                                        <div class="btn-wrap">
                                                            <a href="#">Read More</a>
                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="wrap-social pull-left">
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-thumbs-o-up"></i><span>106</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>19</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-comment-o"></i><span>06</span></div>
                                                            </div>
                                                            <div class="status pull-right">
                                                                <p>Status:<span>Active</span></p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="post-circle">
                                                            <a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">cuckoo's</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">Jane waterman</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="panel-2 panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 class="icon">Questions</h4>
                                                        <div class="pull-right">
                                                            <a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-pencil"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4>lorem lpsum is dummy text</h4>
                                                        <p class="user-icon">-Ricardo Ranchet<span>on 24th sep 2017</span></p>
                                                        <fieldset>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                                                        </fieldset>
                                                        <div class="btn-wrap">
                                                            <a href="#">Read More</a>
                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="wrap-social pull-left">
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-thumbs-o-up"></i><span>106</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>19</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-comment-o"></i><span>06</span></div>
                                                            </div>
                                                            <div class="status pull-right">
                                                                <p>Status:<span>Active</span></p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="post-circle">
                                                            <a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">cuckoo's</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">Jane waterman</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="panel-3 panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 class="icon">Challenges</h4>
                                                        <div class="pull-right">
                                                            <a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-pencil"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4>lorem lpsum is dummy text</h4>
                                                        <p class="user-icon">-Ricardo Ranchet<span>on 24th sep 2017</span></p>
                                                        <fieldset>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                                                        </fieldset>
                                                        <div class="btn-wrap">
                                                            <a href="#">Read More</a>
                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="wrap-social pull-left">
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-thumbs-o-up"></i><span>106</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>19</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-comment-o"></i><span>06</span></div>
                                                            </div>
                                                            <div class="status pull-right">
                                                                <p>Status:<span>Active</span></p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="post-circle">
                                                            <a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">cuckoo's</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">Jane waterman</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <div class="item">
                                                <div class="panel-1 panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 class="icon">Ideas</h4>
                                                        <div class="pull-right">
                                                            <a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-pencil"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4>lorem lpsum is dummy text</h4>
                                                        <p class="user-icon">-Ricardo Ranchet<span>on 24th sep 2017</span></p>
                                                        <fieldset>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                                                        </fieldset>
                                                        <div class="btn-wrap">
                                                            <a href="#">Read More</a>
                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="wrap-social pull-left">
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-thumbs-o-up"></i><span>106</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>19</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-comment-o"></i><span>06</span></div>
                                                            </div>
                                                            <div class="status pull-right">
                                                                <p>Status:<span>Active</span></p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="post-circle">
                                                            <a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">cuckoo's</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">Jane waterman</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="panel-2 panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 class="icon">Questions</h4>
                                                        <div class="pull-right">
                                                            <a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-pencil"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4>lorem lpsum is dummy text</h4>
                                                        <p class="user-icon">-Ricardo Ranchet<span>on 24th sep 2017</span></p>
                                                        <fieldset>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                                                        </fieldset>
                                                        <div class="btn-wrap">
                                                            <a href="#">Read More</a>
                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="wrap-social pull-left">
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-thumbs-o-up"></i><span>106</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>19</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-comment-o"></i><span>06</span></div>
                                                            </div>
                                                            <div class="status pull-right">
                                                                <p>Status:<span>Active</span></p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="post-circle">
                                                            <a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">cuckoo's</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">Jane waterman</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="panel-3 panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 class="icon">Challenges</h4>
                                                        <div class="pull-right">
                                                            <a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-pencil"></i></a>
                                                            <a href="#"><i aria-hidden="true" class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h4>lorem lpsum is dummy text</h4>
                                                        <p class="user-icon">-Ricardo Ranchet<span>on 24th sep 2017</span></p>
                                                        <fieldset>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                                                        </fieldset>
                                                        <div class="btn-wrap">
                                                            <a href="#">Read More</a>
                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="wrap-social pull-left">
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-thumbs-o-up"></i><span>106</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-eye"></i> <span>19</span></div>
                                                                <div class="wrap-inner-icon"><i aria-hidden="true" class="fa fa-comment-o"></i><span>06</span></div>
                                                            </div>
                                                            <div class="status pull-right">
                                                                <p>Status:<span>Active</span></p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="post-circle">
                                                            <a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">cuckoo's</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">Jane waterman</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div tabindex="5002" style="overflow-y: hidden;" class="tab-pane tab-border active" id="users">
                                        <table class="table table-responsive">
                                            <thead>
                                            <tr>
                                                <th>Group Admin Details</th>
                                                <th>Followings</th>
                                                <th>Followers</th>
                                                <th>Points</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($groupData->groupUsers as $user)
                                            <tr>
                                                <td><p class="blue">{{ $user->userDetail->name }}<span>{{ $user->userDetail->email }}</span></p></td>
                                                <td><p>{{ count($user->following) }}<span></span></p></td>
                                                <td><p>{{ count($user->followers) }}<span></span></p></td>
                                                <td><p>02<span></span></p></td>
                                                <td><p><a class="promoteToAdmin {{ ( $groupData->group_owner == $user->user_id OR $user->is_admin == '1') ? 'active-admin' : 'deactive-admin' }}">Make Group Admin<span></span></a></p></td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            
            </div>
        </div><!--wrap -->
    </div><!-- page-content -->
@endsection