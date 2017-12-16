@extends('template.default')
<title>DICO - Company</title>
@section('content')


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
                                <input type="text" name="company_name" id="company_name" value="{{$company->company_name}}" placeholder="Company Name" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 form-group">
                                <label>Company Description</label>
                                <textarea name="company_description" id="company_description" placeholder="Company Description" class="form-control">{{$company->description}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label class="checkbox-inline"><input type="checkbox" name="allow_anonymous" id="allow_anonymous" <?php if($company->allow_anonymous == 1) { echo "checked"; } ?>>Allow Anonymous</label><br/>
                                <label class="checkbox-inline"><input type="checkbox" name="allow_add_admin" id="allow_add_admin" <?php if($company->allow_add_admin == 1) { echo "checked"; } ?>>Allow Admin</label><br/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                            
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="options">
                            <div class="btn-toolbar">
                                <a href="{{ route('company.index') }}" class="btn btn-default" >Back</a>
                                <input type="submit" name="save" id="save" class="btn btn-primary">
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