@extends('template.default')
<title>@lang("label.DICO - SecurityQuestion")</title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('security_question.index') }}">@lang("label.adSecurity Question")</a></li>
                <li class="active">@lang("label.adUpdate Security Question")</li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.adSecurity Question")</h1>
            <hr class="border-out-hr">
            <?php /*<div>
                <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('security_question.index') }}">Security Question</a> > Update Security Question</p></div>
            </div>*/?>
        </div>
        <div class="container">
            <div class="row">
                <div id="create-user-from">
                    <form name="security_question_form" id="security_question_form" method="POST"
                          action="{{ route('security_question.update',[$security_question->id]) }}" class="common-form" >
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label class="text-15">@lang("label.adQuestion")<span>*</span></label>
                            <input type="text" name="question" id="question"
                                   placeholder="@lang('label.adSecurity Question')"
                                   class="form-control required" value="{{$security_question->question}}">
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