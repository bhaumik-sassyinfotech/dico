@extends('template.default')
<title>DICO - Company</title>
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
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('company.index') }}">Company</a></li>
                    <li class="active">Create Company</li>
                </ol>
                <h1>Company</h1>
            </div>
            <div class="container">
                <div class="panel panel-default">
                    <form name="company_form" id="company_form" method="post" action="{{route('company.store')}}">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label>Company Name<span>*</span></label>
                                        <input type="text" name="company_name" id="company_name"
                                               placeholder="Company Name" class="form-control required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label>Company Description</label>
                                        <textarea name="company_description" id="company_description"
                                                  placeholder="Company Description" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-2 form-group">
                                        <label><input type="checkbox" name="allow_anonymous" id="allow_anonymous">Allow Anonymous</label><br/>
                                    </div>
                                    <div class="col-xs-2 form-group">
                                        <label for="allow_add_admin"><input type="checkbox" name="allow_add_admin" id="allow_add_admin">Allow Admin</label><br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-toolbar">
                                        <a href="{{ route('company.index') }}" class="btn btn-default">Back</a>
                                        <input type="submit" name="save" id="save" class="btn btn-primary">
                                    </div>
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
