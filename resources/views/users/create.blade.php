@extends('template.default')
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
            <ol class="breadcrumb">
                <li class='active'><a href="{{ route('user.index') }}">User</a></li>
            </ol>
            <h1>User</h1>
            <div>
                <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('user.index') }}">User</a> > Create User</p></div>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <form name="user_form" id="user_form" method="post" action="{{route('user.store')}}">
                     {{ csrf_field() }}
                    <div class="new_button">
                        <div class="pull-right extra_button">
                            <input type="submit" name="save" id="save" class="btn btn-primary">
                        </div>
                        <div class="pull-right extra_button">
                                <a href="{{ route('user.index') }}" class="btn btn-default" >Back</a>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
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
                            <div class="col-xs-12 form-group">
                                <label>User Name<span>*</span></label>
                                <input type="text" name="user_name" id="user_name" placeholder="User Name" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>User Email<span>*</span></label>
                                <input type="text" name="user_email" id="user_email" placeholder="User Email" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
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
                                <label>Is Active</label><br/>
                                <input type="checkbox" name="is_active" id="is_active">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Suspended</label><br/>
                                <input type="checkbox" name="is_suspended" id="is_suspended">
                            </div>
                        </div>
                    </div>
                </form>     
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script type="text/javascript">
</script>
@endsection
