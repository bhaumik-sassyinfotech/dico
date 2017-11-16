@extends('template.default')
<title>DICO - SecurityQuestion</title>
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
                <li><a href="{{ route('security_question.index') }}">Security Question</a></li>
                <li class="active">Create Security Question</li>
            </ol>
            <h1>Security Question</h1>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <form name="security_question_form" id="security_question_form" method="post" action="{{route('security_question.store')}}">
                     {{ csrf_field() }}
                    <div class="new_button">
                        <div class="pull-right extra_button">
                            <input type="submit" name="save" id="save" class="btn btn-primary">
                        </div>
                        <div class="pull-right extra_button">
                                <a href="{{ route('security_question.index') }}" class="btn btn-default" >Back</a>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Question<span>*</span></label>
                                <input type="text" name="question" id="question" placeholder="Security Question" class="form-control required">
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
