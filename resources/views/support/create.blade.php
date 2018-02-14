@extends('template.default')
<title>DICO - Add Support </title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>                
                <li class="active">Add Support </li>
            </ol>
            <h1 class="tp-bp-0">Add Support </h1>
            <hr class="border-out-hr">

        </div>
        <div class="container">
            <div class="row">
                <div class="create-user-from">
                    {{ Form::open([ 'name' => 'supportAdd', 'class' => 'common-form' ,'route' => 'support.store' , 'id' => 'supportAdd'] ) }}                   
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="update-block-wrap">
                        <div class="form-group">
                            <label class="text-15">Issue<span>*</span></label>
                            <input type="text" name="issue" id="issue"
                                   placeholder="Issue"
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
    $("#supportAdd").validate({
    rules: {
        issue: {
            required: true,
        },
        description: {
            required: true,
        }
    },
    messages: {
        issue: {
            required: 'This field is required',
        },
        description: {
            required: 'This field is required',
        }
    }
});
</script>

@endsection