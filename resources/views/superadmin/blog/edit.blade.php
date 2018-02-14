@extends('template.default')
<title>DICO - Update Blog </title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                <li class="active">Update Blog</li>
            </ol>
            <h1 class="tp-bp-0">Update Blog </h1>
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
                        
                        </div>
                        <div class="form-group editor-files">
                            <label class="text-15" >Description<span>*</span></label>
                            <textarea id="editor" name="description">{{$block->description}}</textarea>                           
                        </div>
                        <div class="form-group">
                            <div class="btn-wrap-div">
                                <input type="submit" class="st-btn" value="Submit">
                                <a href="{{ url()->previous() }}" class="st-btn">Back</a>
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