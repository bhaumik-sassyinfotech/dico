@extends('template.default')
<title>@lang("label.adDICO - Feedback")</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ route('index') }}">@lang("label.adDashboard")</a></li>
                <li class="active">@lang("label.adFeedback")</li>
            </ol>
            <h1>@lang("label.adFeedback")</h1>
            <div class="options">
                <div class="btn-toolbar">

                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="panel-body">
                        <div class="panel panel-info " style="overflow-x:auto;">
                            <div class="panel-heading trophy">
                                <h4 class="icon">@lang("label.adFeedback")</h4>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-striped" id="feedbackList">
                                    <thead>
                                        <tr>
                                            <th>#@lang('label.adID')</th>
                                            <th>@lang('label.adSubject')</th>
                                            <th>@lang('label.adDescription')</th>
                                            <th>@lang('label.adAction')</th>
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
    function deleteFeedback(id)
    {
        swal({
            title: '@lang("label.adAre you sure?")',
            text: '@lang("label.adAre you sure you want to delete this feedback")',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '@lang("label.adYes, delete it")'
        }, function (result) {
            if (result) {
                var url = "{{ route('deleteFeedback')}}";
                var _token = CSRF_TOKEN;
                $('#loader').show();
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {feed_id: id,_token:_token},
                    success: function (data) {
                        obj = jQuery.parseJSON(data);
                        if (obj.msg)
                        {
                            $('#loader').hide();
                            $('#feedbackList').DataTable().row('.selected').remove().draw(false);
                            swal("@lang('label.adDeleted')", obj.msg, "success");
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#loader').hide();
                        swal("warning", "@lang('label.adPlease try again')", "error");
                    }
                });
            }
        })
    }
</script>
@stop
