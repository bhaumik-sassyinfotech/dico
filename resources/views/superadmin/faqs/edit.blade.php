@extends('template.default')
<title>@lang('label.adDICO - FAQs')</title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ route('index') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('adminfaq.index') }}">@lang("label.adFAQs")</a></li>
                <li class="active">@lang("label.adUpdate FAQs")</li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.adUpdate FAQs")</h1>
            <hr class="border-out-hr">
            <?php /* <div>
              <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('security_question.index') }}">Security Question</a> > Update Security Question</p></div>
              </div> */ ?>
        </div>
        <div class="container">
            <div class="row">
                <div class="create-user-from">
                    <form name="faqsEdit" id="faqsEdit" method="POST"
                          action="{{ route('adminfaq.update',[$faqs->id]) }}" class="common-form" >
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="update-block-wrap">

                            <div class="form-group">
                                <label class="text-15">Question(English)<span>*</span></label>
                                <input type="text" name="question" id="question"
                                       placeholder="Question"
                                       class="form-control required" value="{{$faqs->question}}">
                            </div>
                        </div>
                    <div class="update-block-wrap">
                        <div class="form-group">
                            <label class="text-15">Question(Spanish)<span>*</span></label>
                            <input type="text" name="spanish_question" id="spanish_question"
                                   placeholder="pregunta"
                                   class="form-control required" value="{{$faqs->spquestion}}">
                        </div>
                    </div>
                        <div class="form-group editor-files">
                            <label class="text-15" >Answer(English)<span>*</span></label>
                            <textarea id="editor" name="answer">{{$faqs->answer}}</textarea>                           
                        </div>
                          <div class="form-group editor-files">
                        <label class="text-15" >Answer(Spanish)<span>*</span></label>
                        <textarea id="editor1" name="spanish_answer">{{$faqs->spanswer}}</textarea>                           
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

@endsection