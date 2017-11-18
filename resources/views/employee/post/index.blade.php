@extends('template.default')
<title>DICO - Post</title>
@section('content')

@if(session()->has('success'))
<div class="alert alert-success">
    {{ session()->get('success') }}
</div>
@endif
@if(session()->has('err_msg'))
<div class="alert alert-danger">
    {{ session()->get('err_msg') }}
</div>
@endif
<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <h1>Post</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs">
                        <a href="{{ route('post.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
        </div>    
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" id="post-search-form" class="form-inline" role="form">
                    </form>
                    <?php
                        if(!empty($posts)) {
                            foreach(array_chunk($posts,3) as $p) {
                                //dd($post['post_title']);
                    ?>
                    <div class="row">
                    <?php
                        foreach($p as $post) {
                    ?>
                        <div class="col-md-4">
                            <div class="info-tiles tiles-info">
                                <div class="tiles-heading">
                                    <div class="pull-left"><a href="{{route('post.edit',$post['id'])}}">{{$post['post_title']}}</a></div>
                                </div>
                                <div class="tiles-body">
                                    <div class="row-md-4">
                                        {{$post['post_description']}}
                                    </div>
                                    <div class="row-md-4">
                                        <div class="col-md-2"><a href="{{url('like_post',$post['id'])}}">
                                                <?php
                                                    if(!empty($post['post_user_like'])) {
                                                ?>
                                                <i class="fa fa-thumbs-up"></i>
                                                <?php } else { ?>
                                                <i class="fa fa-thumbs-o-up"></i>
                                                <?php } ?>
                                            </a>
                                                <span><?php echo count($post['post_like']);?></span>
                                        </div>
                                        <div class="col-md-2"><a href=""><i class="fa fa-comments-o"></i></a></div>
                                    </div>
                                </div>
                                <div class="tiles-footer">
                                    Author : {{$post['post_user']['name']}} <br/>
                                    Date : {{date('d/m/Y',strtotime($post['created_at']))}}
                                </div>
                            </div>
                        </div>
                        
                    <?php
                        }
                    ?>
                    </div>    
                    <?php
                            }
                        }
                    ?>
                    <?php /*<table class="table table-striped" id="post_table">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>*/?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    function deletepost(id) {
        swal({
            title: "Are you sure?",
            text: "you will not able to recover this post.",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          }, function () {
              var token = '<?php echo csrf_token() ?>';
              var formData = {post_id : id, _token : token};
              $.ajax({
                url: "{{ route('post.destroy',"+id+") }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    var res = JSON.parse(response);
                    if(res.status == 1) {
                        swal("Success", res.msg, "success");
                        location.reload();
                    }
                    else {
                        swal("Error", res.msg, "error");
                    }
                }
            });
        });
    }
</script>
@endpush
