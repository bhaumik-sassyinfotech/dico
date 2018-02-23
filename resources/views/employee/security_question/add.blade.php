@extends('template.default')
<title>@lang("label.DICO - SecurityQuestion")</title>
@section('content')
    
    @include('template.notification')
    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('security_question.index') }}">@lang('label.Login')</a></li>
                    <li class="active">@lang("label.adCreate Security Question")</li>
                </ol>
                <h1 class="tp-bp-0">@lang("label.adSecurity Question")</h1>
                <hr class="border-out-hr">
            
            </div>
            <div class="container">
                <div class="row">
                    <div id="create-user-from">
                        <form name="security_question_form" id="security_question_form" method="post"
                              action="{{route('security.firstLogin')}}" class="common-form" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>@lang("label.adQuestion 1")<span>*</span></label>
                                <div class="select">
                                    <select name="question_1" id="question_1"
                                            class="form-control required sec_question">
                                        <option value="">@lang('label.adSecurity Question 1')</option>
                                        @if($questions->count() > 0)
                                            @foreach($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->question }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-15">@lang('label.adAnswer 1')<span>*</span></label>
                                <input type="text" name="answer_1" id="answer_1" placeholder="@lang('label.adAnswer 1')"
                                       class="form-control required">
                            </div>
                            
                            <div class="form-group">
                                <label>@lang("label.adQuestion 2")<span>*</span></label>
                                <div class="select">
                                    <select name="question_2" id="question_2"
                                            class="form-control required sec_question">
                                        <option value="">@lang('label.adSecurity Question 2')</option>
                                        @if($questions->count() > 0)
                                            @foreach($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->question }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-15">@lang('label.adAnswer 2')<span>*</span></label>
                                <input type="text" name="answer_2" id="answer_2" placeholder="@lang('label.adAnswer 2')"
                                       class="form-control required">
                            </div>
                            
                            
                            <div class="form-group">
                                <label>@lang("label.adQuestion 3")<span>*</span></label>
                                <div class="select">
                                    <select name="question_3" id="question_3"
                                            class="form-control required sec_question">
                                        <option value="">@lang('label.adSecurity Question 3')</option>
                                        @if($questions->count() > 0)
                                            @foreach($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->question }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-15">@lang('label.adAnswer 3')<span>*</span></label>
                                <input type="text" name="answer_3" id="answer_3" placeholder="@lang('label.adAnswer 3')"
                                       class="form-control required">
                            </div>
                            <div class="form-group">
                                <div id="upload_form" class="btn-wrap-div">
                                    <input type="file" class="fileinput" name="profile_picture" id="image">
                                    <label style="left: 139px;">@lang('label.adUpload Photo')</label>
                                    <div class="preview_box">
                                        <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}" id="preview_img">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="btn-wrap-div" style="width: 100%">
                                    <input type="submit" class="st-btn" value="@lang('label.adSubmit')">
                                    <a href="{{ url()->previous() }}" class="st-btn">@lang('label.adBack')</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@stop