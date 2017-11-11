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
                <li class='active'><a href="index.htm">Company</a></li>
            </ol>
            <h1>Company</h1>
            <div>
                <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('company.index') }}">Company</a> > Create Company</p></div>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <form name="company_form" id="company_form" method="post" action="{{route('company.store')}}">
                     {{ csrf_field() }}
                    <div class="new_button">
                        <div class="pull-right extra_button">
                            <input type="submit" name="save" id="save" class="btn btn-primary">
                        </div>
                        <div class="pull-right extra_button">
                                <a href="{{ route('company.index') }}" class="btn btn-default" >Back</a>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Company Name<span>*</span></label>
                                <input type="text" name="company_name" id="company_name" placeholder="Company Name" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Company Description</label>
                                <textarea name="company_description" id="company_description" placeholder="Company Description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Allow Anonymous</label><br/>
                                <input type="checkbox" name="allow_anonymous" id="allow_anonymous">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Allow Admin</label><br/>
                                <input type="checkbox" name="allow_add_admin" id="allow_add_admin">
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
