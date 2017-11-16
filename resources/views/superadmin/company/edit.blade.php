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
                <li class="active">Update Company</li>
            </ol>
            <h1>Company</h1>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <!-- <form name="company_form" id="company_form" method="post" action="{{route('company.update',$company->company_id)}}">-->
                {!! Form::model($company, ['method' => 'PUT', 'route' => ['company.update', $company->id],'enctype'=>'multipart/form-data', 'id' => 'company_form']) !!}
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
                                <label>Company Name</label>
                                <input type="text" name="company_name" id="company_name" value="{{$company->company_name}}" placeholder="Company Name" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Company Description</label>
                                <textarea name="company_description" id="company_description" placeholder="Company Description" class="form-control">{{$company->description}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Allow Anonymous</label><br/>
                                <input type="checkbox" name="allow_anonymous" id="allow_anonymous" <?php if($company->allow_anonymous == 1) { echo "checked"; } ?>>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Allow Admin</label><br/>
                                <input type="checkbox" name="allow_add_admin" id="allow_add_admin" <?php if($company->allow_add_admin == 1) { echo "checked"; } ?>>
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