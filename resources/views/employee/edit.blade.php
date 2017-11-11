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
                <li class='active'><a href="index.htm">Employee</a></li>
            </ol>
            <h1>Employee</h1>
            <div>
                <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('employee.index') }}">Employee</a> > Update Employee</p></div>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default">
                {!! Form::model($employee, ['method' => 'PUT', 'route' => ['employee.update', $employee->id],'enctype'=>'multipart/form-data', 'id' => 'employee_form']) !!}
                <div class="new_button">
                    <div class="pull-right extra_button">
                        <input type="submit" name="save" id="save" class="btn btn-primary">
                    </div>
                    <div class="pull-right extra_button">
                        <a href="{{ route('employee.index') }}" class="btn btn-default" >Back</a>
                    </div>
                    <div style="clear: both;"></div>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="company_id" id="company_id" value="{{$company->id}}">
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label>Company Name<span>*</span></label>
                            <input type="text" name="company_name" id="company_name" placeholder="Company Name" readonly="" value="{{$company->company_name}}" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label>Employee Name<span>*</span></label>
                            <input type="text" name="employee_name" id="employee_name" placeholder="Employee Name" value="{{$employee->name}}" class="form-control required">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label>Employee Email<span>*</span></label>
                            <input type="text" name="employee_email" id="employee_email" placeholder="Employee Email" value="{{$employee->email}}" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label>Role<span>*</span></label>
                            <select id="role_id" name="role_id" class="form-control">
                                <option value="">------ Select ------</option>
                                <?php
                                if (!empty($roles)) {
                                    foreach ($roles as $role) {
                                        ?>
                                        <option value="{{$role->id}}" <?php if($employee->role_id == $role->id) { echo "selected"; }?>>{{$role->role_name}}</option>
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
                            <input type="checkbox" name="is_active" id="is_active" <?php if($employee->is_active == 1) { echo "checked";}?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label>Suspended</label><br/>
                            <input type="checkbox" name="is_suspended" id="is_suspended" <?php if($employee->is_suspended == 1) { echo "checked";}?>>
                        </div>
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