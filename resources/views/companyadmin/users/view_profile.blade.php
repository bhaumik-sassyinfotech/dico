@extends('template.default')
<title>DICO - Profile</title>
@section('content')


<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <h1>Profile</h1>
        </div>    
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-body">
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
                    <div class="tab-content">
                        <div class="tab-pane active" id="domtabs">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="tab-container tab-danger">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#general_tab" data-toggle="tab">General</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="general_tab">
                                                <form name="view_profile_form" id="view_profile_form" method="post">
                                                    <div class="pull-right extra_button">
                                                    <?php
                                                        if(!empty($user->following) && count($user->following) > 0) {
                                                            if($user->following[0]->status == 1) {
                                                    ?>
                                                        <a href="{{ url('/unfollow/'.$user->id) }}" class="btn btn-primary" >Unfollow</a>
                                                    <?php 
                                                            }else {
                                                            ?>
                                                            <a href="{{ url('/follow/'.$user->id) }}" class="btn btn-primary" >Follow</a>
                                                            <?php
                                                            }
                                                    } else {
                                                    ?>
                                                        <a href="{{ url('/follow/'.$user->id) }}" class="btn btn-primary" >Follow</a>
                                                    <?php } ?>
                                                    </div>
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
                                                            <label><b>Name:</b></label>
                                                            <label>{{$user->name}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label><b>Email:</b></label>
                                                            <label>{{$user->email}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label><b>Google Link:</b></label>
                                                            <?php if(!empty($user->google_id)) { $google_link = $user->google_id;} else { $google_link = ' - ';}?>
                                                            <label>{{$google_link}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 form-group">
                                                            <label><b>LinkedIn Link:</b></label>
                                                            <?php if(!empty($user->linkedin_id)) { $linkedin_link = $user->linkedin_id;} else { $linkedin_link = ' - ';}?>
                                                            <label>{{$linkedin_link}}</label>
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