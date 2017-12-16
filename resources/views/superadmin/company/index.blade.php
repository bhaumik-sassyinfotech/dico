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
                    <div class="btn-group hidden-xs">
                        <a href="{{ route('company.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
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