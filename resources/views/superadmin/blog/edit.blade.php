@extends('template.default')
<title>DICO - Blog </title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('blog.index') }}">@lang("label.adBlog")</a></li>
                <li class="active">@lang("label.adUpdate Blog")</li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.adUpdate Blog")</h1>
            <hr class="border-out-hr">
            <?php /* <div>
              <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('security_question.index') }}">Security Question</a> > Update Security Question</p></div>
              </div> */ ?>
        </div>
        <div class="container">
            <div class="row">
                <div class="create-user-from" >
                    <form name="blockEdit" id="blockEdit" method="POST"
                          action="{{ route('blog.update',[$block->id]) }}" class="common-form" >
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="update-block-wrap">
                        <div class="form-group">
                            <label class="text-15">Slug Name<span>*</span></label>
                            <input type="text" name="slug_name" id="slug_name"
                                   placeholder="slug_name"
                                   class="form-control required" value="{{$block->slug_name}}" readonly="">
                        </div>
                        <div class="form-group">
                            <label class="text-15">Title<span>*</span></label>
                            <input type="text" name="title" id="question"
                                   placeholder="Title"
                                   class="form-control required" value="{{$block->title}}">
                        </div>
                         <div class="form-group">
                            <label class="text-15">Titulo<span>*</span></label>
                            <input type="text" name="spanish_title" id="question"
                                   placeholder="Titulo"
                                   class="form-control required" value="{{$block->sptitle}}">
                        </div>
                        </div>
                        <div class="form-group editor-files">
                            <label class="text-15" >Description<span>*</span></label>
                            <textarea id="editor1" name="description">{{$block->description}}</textarea>                           
                        </div>
                       
                         <div class="form-group editor-files">
                            <label class="text-15" >Descripcion<span>*</span></label>
                            <textarea id="editor" name="spanish_description">{{$block->spdescription}}</textarea>                           
                        </div>
                        <div class="form-group">
                            <div class="btn-wrap-div">
                                <input type="submit" class="st-btn" value="@lang('label.adSubmit')">
                                <a href="{{ url()->previous() }}" class="st-btn">@lang("label.adBack")</a>
                            </div>
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