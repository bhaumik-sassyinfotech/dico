@extends('admin.layouts.app')

@section('content')
    <div>
		<div class="col-md-6 nopadding"><h3 class="page-title">Designs</h3></div>
		<div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/admin/home') }}">Dashboard</a> > <a href="{{ route('design.index') }}">Designs</a> > View Designs</p></div>
	</div>
    {!! Form::model($design, ['method' => 'PUT', 'route' => ['design.update', $design->id],'enctype'=>'multipart/form-data']) !!}

    <div class="new_button">
		<div class="pull-right extra_button">
			<a href="{{ route('design.index') }}" class="btn btn-success extra_button" >Back</a>
		</div>
		<div style="clear: both;"></div>
    </div>
	
	<div class="panel panel-default">
        <div class="panel-heading">
            View Design
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <table class="table table-bordered table-striped">
                        
						<tr><th>Design Image</th><td><img id="gift_pic" src="{!! url('/').'/public'.$design->design_image !!}" height="100" width="100"/></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
	{!! Form::close() !!}
@stop

@section('javascript')
    <script>
        $('#designImage').change( function(event){
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('design_pic');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
            
        });
        
    </script>
@endsection

