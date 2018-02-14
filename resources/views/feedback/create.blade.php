@extends('template.default')
<title>DICO - Add Feedback </title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">Add Feedback </li>
            </ol>
            <h1 class="tp-bp-0">Add Feedback </h1>
            <hr class="border-out-hr">

        </div>
        <div class="container">
            <div class="row">
                <div class="create-user-from">
                    {{ Form::open([ 'name' => 'feedbackAdd', 'class' => 'common-form' ,'route' => 'feedback.store' , 'id' => 'feedbackAdd'] ) }}                   
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="update-block-wrap">
                        <div class="form-group">
                            <label class="text-15">Subject<span>*</span></label>
                            <input type="text" name="subject" id="subject"
                                   placeholder="Subject"
                                   class="form-control required">
                        </div>
                    </div>

                    <div class="form-group editor-files">
                        <label class="text-15" >Description<span>*</span></label>
                        <textarea id="editor" name="description"> </textarea>                           
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
    $("#feedbackAdd").validate({
    rules: {
        subject: {
            required: true,
        },
        description: {
            required: true,
        }
    },
    messages: {
        subject: {
            required: 'This field is required',
        },
        description: {
            required: 'This field is required',
        }
    }
});
</script>

@endsection