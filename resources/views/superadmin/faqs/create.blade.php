@extends('template.default')
<title>@lang('label.adDICO - FAQs')</title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('adminfaq.index') }}">@lang("label.adFAQs")</a></li>
                <li class="active">@lang("label.adAdd FAQs") </li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.adAdd FAQs") </h1>
            <hr class="border-out-hr">

        </div>
        <div class="container">
            <div class="row">
                <div class="create-user-from">
                    {{ Form::open([ 'name' => 'faqsAdd', 'class' => 'common-form' ,'route' => 'adminfaq.store' , 'id' => 'faqsAdd'] ) }}                   
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="update-block-wrap">
                        <div class="form-group">
                            <label class="text-15">Question(English)<span>*</span></label>
                            <input type="text" name="question" id="question"
                                   placeholder="Question(English)"
                                   class="form-control required">
                        </div>
                    </div>
                     <div class="update-block-wrap">
                        <div class="form-group">
                            <label class="text-15">Question(Spanish)<span>*</span></label>
                            <input type="text" name="spanish_question" id="spanish_question"
                                   placeholder="Question(Spanish)"
                                   class="form-control required">
                        </div>
                    </div>
                    <div class="form-group editor-files">
                        <label class="text-15" >Answer(English)<span>*</span></label>
                        <textarea id="editor" name="answer"> </textarea>                           
                    </div>
                     <div class="form-group editor-files">
                        <label class="text-15" >Answer(Spanish)<span>*</span></label>
                        <textarea id="editor1" name="spanish_answer"> </textarea>                           
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
    //initSample();
</script>
@endsection