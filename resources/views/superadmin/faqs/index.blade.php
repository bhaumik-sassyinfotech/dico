@extends('template.default')
<title>DICO - FAQs</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">FAQs List</li>
            </ol>
            <h1>FAQs List</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <a class="btn btn-default" href="{{ route('adminfaq.create') }}">
                        <i aria-hidden="true" class="fa fa-pencil-square-o fa-6"></i>
                        <span class="hidden-xs hidden-sm">Create FAQs</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="panel-body">
                        <div class="panel panel-info " style="overflow-x:auto;">
                            <div class="panel-heading trophy">
                                <h4 class="icon">FAQs List</h4>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-striped" id="adminfaqList">
                                    <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>Question</th>
                                            <th>Answer</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                </table>
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6">
                                    <div class="col-lg-6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteFaqs(id)
    {
        swal({
            title: 'Are you sure?',
            text: 'Are you sure you want to delete this faqs',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }, function (result) {
            if (result) {
                var url = "{{ URL::to('deleteFaqs')}}";
                var _token = CSRF_TOKEN;
                $('#loader').show();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {faqs_id: id,_token:_token},
                    success: function (data) {
                        obj = jQuery.parseJSON(data);
                        if (obj.msg)
                        {
                            $('#loader').hide();
                            $('#adminfaqList').DataTable().row('.selected').remove().draw(false);
                            swal("Deleted", obj.msg, "success");
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#loader').hide();
                        swal("warning", "Please try again", "error");
                    }
                });
            }
        })
    }
</script>
@stop
