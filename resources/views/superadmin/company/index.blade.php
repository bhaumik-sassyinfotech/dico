@extends('template.default')
<title>DICO - Company</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">Company</li>
            </ol>
            <h1>Company</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <a class="btn btn-default" href="{{ route('company.create') }}">
                        <i aria-hidden="true" class="fa fa-pencil-square-o fa-6"></i>
                        <span class="hidden-xs hidden-sm">New Company</span>
                    </a>
                </div>
            </div>
        </div>    

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                        <div class="panel-heading">
                            <div class="btn-group top-set">
                                <form method="POST" id="search-form" role="form" class="search-form">
                                   <input type="text" class="form-control" name="company_name" id="company_name" placeholder="company name">
                                   <input class="search-icon" type="submit">
                                </form>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="panel panel-info " style="overflow-x:auto;">
                            <div class="panel-heading trophy">
                                <h4 class="icon">Company List</h4>
                            </div>
                            <div class="panel-body">
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
        </div>    
        </div>

    </div>
    @stop

    @section('javascript')
    <script type="text/javascript">
        
    </script>
    @endsection