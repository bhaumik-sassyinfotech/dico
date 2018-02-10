@extends('template.default')
<title>DICO - Add FAQs </title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ route('adminfaq.index') }}">FAQs</a></li>
                <li class="active">Add FAQs </li>
            </ol>
            <h1 class="tp-bp-0">Add FAQs </h1>
            <hr class="border-out-hr">
            
        </div>
        <div class="container">
            <div class="row">
                <div id="create-user-from" style="width: 100%">
                    {{ Form::open([ 'name' => 'faqsAdd', 'class' => 'common-form' ,'route' => 'adminfaq.store' , 'id' => 'faqsAdd'] ) }}                   
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <div class="form-group">
                            <label class="text-15">Question<span>*</span></label>
                            <input type="text" name="question" id="question"
                                   placeholder="Question"
                                   class="form-control required">
                        </div>
                       
                       <div class="form-group">
                            <label class="text-15" style="width: 100%">Answer<span>*</span></label>
                            <textarea id="editor" name="answer"> </textarea>                           
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
<script type="text/javascript">
    initSample();  
</script>
@endsection