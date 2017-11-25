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
    <form name="security_question_form" id="security_question_form" method="post"
          action="{{route('security_question.store')}}">
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
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Question<span>*</span></label>
                                    <input type="text" name="question" id="question" placeholder="Security Question"
                                           class="form-control required">
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="options">
                                <div class="btn-toolbar">
                                    <a href="{{ route('security_question.index') }}" class="btn btn-default">Back</a>
                                    <input type="submit" name="save" id="save" class="btn btn-primary">
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
@section('javascript')
    <script type="text/javascript">
    </script>
@endsection
