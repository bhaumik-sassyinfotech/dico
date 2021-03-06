@extends('template.default')
<title>DICO - User</title>
@section('content')

    
    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('user.index') }}">User</a></li>
                    <li class="active">Update User</li>
                </ol>
                <h1>User</h1>
                <?php /*<div>
                <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('user.index') }}">User</a> > Update User</p></div>
            </div>*/?>
                <div class="options">
                    <div class="btn-toolbar">
                        <?php
                        if(!empty($user->following) && count($user->following) > 0) {
                        if($user->following[ 0 ]->status == 1) {
                        ?>
                        <a href="{{ url('/unfollow/'.$user->id) }}" class="btn btn-primary">Unfollow</a>
                        <?php
                        }else {
                        ?>
                        <a href="{{ url('/follow/'.$user->id) }}" class="btn btn-primary">Follow</a>
                        <?php
                        }
                        } else {
                        ?>
                            <a href="{{ url('/follow/'.$user->id) }}" class="btn btn-primary">Follow</a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="panel panel-default">
                    {!! Form::model($user, ['method' => 'PUT', 'route' => ['user.update', $user->id],'enctype'=>'multipart/form-data', 'id' => 'user_form']) !!}
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
                        <div class="row">
                            <div class="col-xs-6 form-group">
                                <label>Company Name<span>*</span></label>
                                <select id="company_id" name="company_id" class="form-control" readonly>
                                    <option value="">------ Select ------</option>
                                    <?php
                                    if (!empty($companies)) {
                                    foreach ($companies as $company) {
                                    ?>
                                    <option value="{{$company->id}}" <?php if ( $company->id == $user->company_id )
                                    {
                                        echo "selected";
                                    } ?>>{{$company->company_name}}</option>
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
                                       value="{{$user->name}}" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 form-group">
                                <label>User Email<span>*</span></label>
                                <input type="text" name="user_email" id="user_email" placeholder="User Email"
                                       value="{{$user->email}}" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 form-group">
                                <label>Role<span>*</span></label>
                                <select id="role_id" name="role_id" class="form-control">
                                    <option value="">------ Select ------</option>
                                    <?php
                                    if (!empty($roles)) {
                                    foreach ($roles as $role) {
                                    ?>
                                    <option value="{{$role->id}}" <?php if ( $user->role_id == $role->id )
                                    {
                                        echo "selected";
                                    } ?>>{{$role->role_name}}</option>
                                    <?php
                                    }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 form-group">
                                <label for="is_active"><input type="checkbox" name="is_active"
                                                              id="is_active" <?php if ( $user->is_active == 1 )
                                    {
                                        echo "checked";
                                    } ?>>Is Active</label><br/>
                                <p class="help-block">If user is inactive, than user will not be able to login into the
                                    system.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label for="is_suspended"><input type="checkbox" name="is_suspended"
                                                                          id="is_suspended" <?php if ( $user->is_suspended == 1 )
                                    {
                                        echo "checked";
                                    } ?>>Suspended</label><br/>
                                <p class="help-block">If user is suspended, than user can login but will not be able to
                                    create a new post.</p>
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
                    <!-- </form>     -->
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script type="text/javascript">
    </script>
@endsection