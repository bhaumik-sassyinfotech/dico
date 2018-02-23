@extends('template.default')
<title>@lang("label.DICO - SecurityQuestion")</title>
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
                    <li class='active'><a href="index.htm">@lang("label.adSecurity Question")</a></li>
                </ol>
                <h1>@lang("label.adSecurity Question")</h1>
                <div>
                    <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ route('index') }}">@lang("label.adDashboard")</a> > <a href="{{ route('security_question.index') }}">@lang("label.adSecurity Question")</a> > @lang("label.adCreate Security Question")</p></div>
                </div>
            </div>
            <div class="container">
                <div class="panel panel-default">
                    <form name="security_question_form" id="security_question_form" method="post" action="{{route('security.firstLogin')}}">
                        {{ csrf_field() }}
                        <div class="new_button">
                            <div class="pull-right extra_button">
                                <input type="submit" name="save" id="save" class="btn btn-primary">
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>@lang("label.adQuestion 1")<span>*</span></label>
                                    <select name="question_1" id="question_1" class="form-control required sec_question">
                                        <option value="">@lang('label.adSecurity Question 1')</option>
                                        @if($questions->count() > 0)
                                            @foreach($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->question }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>@lang('label.adAnswer 1')<span>*</span></label>
                                    <input type="text" name="answer_1" id="answer_1" placeholder="@lang('label.adAnswer 1')" class="form-control required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>@lang("label.adQuestion 2")<span>*</span></label>
                                    <select name="question_2" id="question_2" class="form-control required sec_question">
                                        <option value="">@lang('label.adSecurity Question 2')</option>
                                        @if($questions->count() > 0)
                                            @foreach($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->question }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>@lang('label.adAnswer 2')<span>*</span></label>
                                    <input type="text" name="answer_2" id="answer_2" placeholder="@lang('label.adAnswer 2')" class="form-control required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>@lang("label.adQuestion 3")<span>*</span></label>
                                    <select name="question_3" id="question_3" class="form-control required sec_question">
                                        <option value="">@lang('label.adSecurity Question 3')</option>
                                        @if($questions->count() > 0)
                                            @foreach($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->question }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>@lang('label.adAnswer 3')<span>*</span></label>
                                    <input type="text" name="answer_3" id="answer_3" placeholder="@lang('label.adAnswer 3')" class="form-control required">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop