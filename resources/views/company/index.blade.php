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
            <h1>Company</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs">
                        <a href="{{ route('company.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
        </div>    

        <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form method="POST" id="search-form" class="form-inline" role="form">
                            <div class="form-group">
                                <label for="name">Company Name</label>
                                <input type="text" class="form-control" name="company_name" id="company_name" placeholder="company name">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        <table class="table table-bordered table-striped" id="company_table">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Company Name</th>
                                    <th>Company Descripton</th>
                                    <th>Allow Anonymous</th>
                                    <th>Allow Add Admin</th>
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