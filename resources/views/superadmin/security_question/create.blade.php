@extends('template.default')
<title>@lang("label.DICO - SecurityQuestion")</title>
@section('content')
    
    @include('template.notification')
    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ route('index') }}">@lang("label.adDashboard")</a></li>
                    <li><a href="{{ route('security_question.index') }}">@lang("label.adSecurity Question")</a></li>
                    <li class="active">@lang("label.adCreate Security Question")</li>
                </ol>
                <h1 class="tp-bp-0">@lang("label.adSecurity Question")</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                    <div id="create-user-from">
                        <form name="security_question_form" id="security_question_form" method="post"
                              action="{{ route('security_question.store') }}" class="common-form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="text-15">@lang("label.adQuestion")<span>*</span></label>
                                <input type="text" name="question" id="question"
                                       placeholder="@lang('label.adSecurity Question')"
                                       class="form-control required">
                            </div>
                            <div class="form-group">
                                <div class="btn-wrap-div">
                                    <input type="submit" class="st-btn" value="@lang('label.adSubmit')">
                                    <a href="{{ url()->previous() }}" class="st-btn">@lang("label.adBack")</a>
                                </div>
                            </div>
                        </form>
                    
                    
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
