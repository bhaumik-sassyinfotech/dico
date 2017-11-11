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
            <ol class="breadcrumb">
                <li class='active'><a href="index.htm">Company</a></li>
            </ol>
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
                        <table class="table table-bordered table-striped" id="company_table">
                            <thead>
                                <tr>
                <!--                        <th style="text-align:center;">
                                        <input type="checkbox"  id="master"  value="all" />
                                    </th>-->
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