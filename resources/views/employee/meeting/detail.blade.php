@extends('template.default')
<title>DICO - Post</title>
@section('content')
    
    
    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('meeting.index') }}">Meeting</a></li>
                    <li class="active">View Meeting</li>
                </ol>
                <h1>Meeting</h1>
                <div class="option">
                    <div class="btn-toolbar">
                        @if($meeting->created_by == Auth::user()->id)
                            <a href="{{ route('meeting.edit' , Helpers::encode_url($meeting->id) ) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ route('deleteMeeting' , Helpers::encode_url($meeting->id) ) }}" class="btn btn-danger">Delete</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="panel panel-default">
                    <form name="post_comment_form" id="post_comment_form" method="post"
                          action="{{url('/meeting/saveComment',$meeting->id)}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="post_id" id="post_id" value="{{$meeting->id }}">
                        <div class="panel-body">
                            @include('template.notification')
                            <div class="row">
                                <div class="row col-xs-8">
                                    <div class="col-xs-12 form-group">
                                        <label><b>{{$meeting->meeting_title}}</b></label><br>
                                        <small>
                                            {{ date('d-M-Y',strtotime($meeting->created_at)) }}
                                        </small>
                                    </div>
                                    <div class="col-xs-12 form-group">
                                        <label>{{ $meeting->meeting_description }}</label>
                                    </div>
                                    <div class="col-xs-12 form-group">
                                        <textarea name="comment_text" id="comment_text" class="form-control autosize"
                                                  placeholder="Leave a comment here"
                                                  style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 71.9792px;"></textarea>
                                    </div>
                                    <div class="col-xs-6 form-group">
                                        <span class="btn btn-primary fileinput-button">
                                            <i class="fa fa-upload"></i>
                                            <span>upload</span>
                                            <input type="file" name="file_upload" id="file_upload"
                                                   class="file-upload__input">
                                        </span>
                                    </div>
                                    <div class="col-xs-6">
                                        <input type="submit" name="submit" id="submit" value="Submit"
                                               class="btn btn-primary">
                                    
                                    </div>
                                </div>
                                <!-- MEMBERS LISTING START-->
                                <div class="col-xs-4">
                                    <div class="panel panel-midnightblue">
                                        <div class="panel-heading">Members</div>
                                        <div class="panel-body">
                                            <ul class="panel-threads">
                                                @foreach($meeting_users as $user)
                                                    <li>
                                                        <?php $profile_picture = (!empty($user->profile_image)) ? asset('public/uploads/profile_pic/') . '/' . $user->profile_image : "http://via.placeholder.com/50x50?text=profile+pic"; ?>
                                                        <img src="{{ $profile_picture }}" alt="">
                                                        <div class="content">
                                                            <span class="thread">{{ $user->name }}</span>
                                                            <span class="thread">{{ $user->email }}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- MEMBERS LISTING END-->
                            </div>
                            <hr>
                            <!-- Comment Box start -->
                            <form class="form-horizontal row-border">
                                <div class="panel-body">
                                    <div class="row">
                                        @if (!empty($meeting->meetingComment))
                                            @foreach ($meeting->meetingComment as $comment)
                                                <div class="form-group" id="commentreply_{{$comment['id']}}">
                                                    @if (!empty($comment[ 'commentUser' ]))
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <?php
                                                                        $commentUser = $comment[ 'commentUser' ];
                                                                        $profile_image = (!empty($commentUser->profile_image)) ? 'public/uploads/profile_pic/' . $commentUser->profile_image : 'public/assets/demo/avatar/jackson.png';
                                                                        ?>
                                                                        
                                                                        <img src="{{asset($profile_image)}}"
                                                                             id="profile"
                                                                             alt="" class="pull-left" height="100px"
                                                                             width="100px"
                                                                             style="margin: 0 20px 20px 0"/>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <?php $comment_user_id = Helpers::encode_url($commentUser->id); ?>
                                                                    @if (!empty($commentUser[ 'following' ]) && count($commentUser[ 'following' ]) > 0 && $commentUser->id != Auth::user()->id)
                                                                        @if ($commentUser[ 'following' ][ 0 ]->status == 1)
                                                                            <a href="{{ url('/view_profile/'.$comment_user_id) }}"
                                                                               class="btn btn-primary">Unfollow</a>
                                                                        @else
                                                                            <a href="{{ url('/view_profile/'.$comment_user_id) }}"
                                                                               class="btn btn-primary">Follow</a>
                                                                        @endif
                                                                    @elseif ($commentUser->id != Auth::user()->id)
                                                                        <a href="{{ url('/view_profile/'.$comment_user_id) }}"
                                                                           class="btn btn-primary">Follow</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row col-md-10">
                                                                <div class="row">
                                                                    <div class="row col-md-4">
                                                                        <small>{{ " - on " . date('m/d/Y' , strtotime($commentUser[ 'created_at' ])) }}</small>
                                                                    </div>
                                                                    <div class="row col-md-4 pull-right">
                                                                        <div class="col-xs-2" style="visibility: hidden;">
                                                                            <a href="javascript:void(0);"
                                                                               data-comment-id="{{ $comment->id }}"
                                                                               data-toggle="modal"
                                                                               data-target="#commentReply"><i
                                                                                        class="fa fa-reply commentReply"
                                                                                        aria-hidden="true"></i>
                                                                            </a>
                                                                        </div>
                                                                        @if ($commentUser[ 'id' ] == Auth::user()->id)
                                                                            <div class="col-xs-2">
                                                                                <span>
                                                                                    <a href="javascript:void(0);">
                                                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                                    </a>
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-xs-2">
                                                                                <span>
                                                                                    <a href="{{ url('/meeting/deleteComment',$comment['id']) }}">
                                                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                                    </a>
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    {{ $comment[ 'comment_text' ] }}
                                                                </div>
                                                                
                                                                @if (!empty($comment[ 'commentAttachment' ]))
                                                                    <div class="row"><b>Attachment : </b>
                                                                        <a href="#">{{$comment['commentAttachment']['file_name']}}</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                <!-- comment reply box start -->
                                                    
                                                    @if (!empty($comment[ 'commentReply' ]))
                                                        <?php $srno = 0; ?>
                                                        @foreach ($comment[ 'commentReply' ] as $commentReply)
                                                            <?php $srno++; ?>
                                                            <div class="form-group row cmry" id="{{$srno}}">
                                                                <div class="col-md-12">
                                                                    {{ $commentReply[ 'comment_reply' ] }}
                                                                </div>
                                                                @if ($commentReply[ 'user_id' ] == Auth::user()->id)
                                                                    <span style="float:right;">
                                                                    <a href="{{url('/meeting/deletecommentReply',$commentReply['id'])}}"><i
                                                                                class="fa fa-trash-o"
                                                                                aria-hidden="true"></i></a>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                    @endforeach
                                                @endif
                                                <!-- comment reply box end -->
                                                </div>
                                                <hr>
                                            @endforeach
                                        @else
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        {{ "No comments have been posted." }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </form>
                            <!-- Comment Box end -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Comment Reply Modal START-->
    <div id="commentReply" class="modal fade"
         role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">Comment Here</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <textarea name="comment_text" id="comment_text" class="form-control autosize"
                                  placeholder="Leave a comment here"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-danger"
                            data-dismiss="modal">Close
                    </button>
                    <button type="button" id="reply_to_comment"
                            class="btn btn-success reply_to_comment"
                            data-dismiss="modal">
                        Submit
                    </button>
                
                </div>
            </div>
        </div>
    </div>
    <!-- Comment Reply Modal END-->
@stop

@push('javascripts')
    <script type="text/javascript">
        $('#commentReply').on('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = $(event.relatedTarget);
            // Extract info from data-* attributes
            var comment_id = button.data('comment-id');
            console.log("Comment ID: " + comment_id);

            var modal = $(this);
            // modal.find('.modal-title').text('New message to ' + recipient);
            // modal.find('.modal-body input').val(recipient);
            $('.reply_to_comment').unbind().click(function (e) {
                // console.log("Clicked 1"+Math.random()); 
                e.preventDefault();
                // console.log("Clicked 1");
                // $(this).data('bs.modal', null);
                replyToComment();
            });
        });

        function replyToComment() {
            $.ajax({
                type: "POST",
                url: SITE_URL + '/',
                data: {},
                success: function (response) {
                    console.log(response);
                }
            });
        }

        $("#post_comment_form").validate({
            rules: {
                'comment_text': {
                    required: true,

                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    </script>
@endpush
