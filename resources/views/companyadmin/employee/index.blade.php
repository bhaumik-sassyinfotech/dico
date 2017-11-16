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
<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <h1>Employee</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs">
                        <a href="{{ route('employee.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
        </div>    

        <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form method="POST" id="employee-search-form" class="form-inline" role="form">
                            <div class="form-group">
                                <label for="name">Employee Name</label>
                                <input type="text" class="form-control" name="employee_name" id="employee_name" placeholder="employee name">
                            </div>
                            <div class="form-group">
                                <label for="email">Employee Email</label>
                                <input type="text" class="form-control" name="employee_email" id="employee_email" placeholder="employee email">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        <table class="table table-bordered table-striped" id="employee_table">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Employee Name</th>
                                    <th>Employee Email</th>
                                    <th>Active</th>
                                    <th>Suspended</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="col-lg-6">

                            </div>
                        </div>            
                    </div>
                </div>
            </div>    
        </div>

    </div>
    @stop

    @section('javascript')
    <script type="text/javascript">
        
    </script>
    @endsection