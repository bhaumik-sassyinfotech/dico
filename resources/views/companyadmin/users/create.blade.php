@extends('template.default')
<title>DICO - User</title>
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
    <form name="user_form" id="user_form" method="post" action="{{route('user.store')}}">
        <div id="page-content">
            <div id='wrap'>
                <div id="page-heading">
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/home') }}">Dashboard</a></li>
                        <li><a href="{{ route('user.index') }}">User</a></li>
                        <li class="active">Create User</li>
                    </ol>
                    <h1>User</h1>
                    <?php /*<div>
                <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('user.index') }}">User</a> > Create User</p></div>
            </div>*/?>
                </div>
                <div class="container">
                    <div class="panel panel-default">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label>Company Name<span>*</span></label>
                                    <select id="company_id" name="company_id" class="form-control">
                                        <option value="">------ Select ------</option>
                                        <?php
                                        if(!empty($companies)) {
                                        foreach($companies as $company) {
                                        ?>
                                        <option value="{{$company->id}}">{{$company->company_name}}</option>
                                        <?php
                                        }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label>Full Name<span>*</span></label>
                                    <input type="text" name="user_name" id="user_name" placeholder="Full Name"
                                           class="form-control required">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label>User Email<span>*</span></label>
                                    <input type="text" name="user_email" id="user_email" placeholder="User Email"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label>Role<span>*</span></label>
                                    <select id="role_id" name="role_id" class="form-control">
                                        <option value="">------ Select ------</option>
                                        <?php
                                        if(!empty($roles)) {
                                        foreach($roles as $role) {
                                        ?>
                                        <option value="{{$role->id}}">{{$role->role_name}}</option>
                                        <?php
                                        }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label><input type="checkbox" name="is_active" id="is_active">Is Active</label><br/>
                                    <p class="help-block">If user is inactive, than user will not be able to login into the system.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label><input type="checkbox" name="is_suspended"
                                                  id="is_suspended">Suspended</label><br/>
                                    <p class="help-block">If user is suspended, than user can login but will not be able to create a new post.</p>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label" for="user_groups">Group:</label>
                                    <select name="user_groups[]" id="user_groups" class="form-control"
                                            multiple="multiple">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row col-xs-12">
                                <div class="btn-toolbar">
                                    <a href="{{ route('user.index') }}" class="btn btn-default">Back</a>
                                    <input type="submit" name="save" id="save" class="btn btn-primary">
                                </div>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
@section('javascript')
    <script type="text/javascript">
    </script>
@endsection
