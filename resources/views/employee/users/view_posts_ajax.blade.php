@if(count($userPosts))
                                                @foreach($userPosts as $post)
                                                    <div class="item">
                                                        @php
                                                            $panelClass = '';
                                                            $question_type = $post->post_type;
                                                            if($question_type == 'idea')
                                                                $panelClass = 'panel-1';
                                                            else if($question_type == 'question')
                                                                $panelClass = 'panel-2';
                                                            else
                                                                $panelClass = 'panel-3';
                                                        @endphp

                                                        <div class="{{ $panelClass }} panel-primary">
                                                            <div class="panel-heading">
                                                                <h4 class="icon">{{ ucfirst($question_type) }}</h4>
                                                                <div class="pull-right">
                                                                    <form action="{{ route('deletePost') }}" method="post" id="delete_post_form">
                                                                        <input type="hidden" value="{{ $post->id }}">
                                                                        <a href="#"> <i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                                        @if($post->user_id == Auth::user()->id)
                                                                            <a href="{{ route('post.edit',[$post->id])  }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                            <a href="javascript:;" class="delete_post_btn">
                                                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                            </a>
                                                                        @endif
                                                                    </form>
                                                                </div>

                                                            </div>
                                                            <div class="panel-body">
                                                                <h4><a class="profanity" href="{{ url('viewpost/'.Helpers::encode_url($post->id)) }}">{{ $post->post_title }}</a></h4>
                                                                <p class="user-icon">-{{ $post->postUser->name }}<span>on {{ date('Y-m-d H:i' , strtotime($post->created_at)) }}</span></p>
                                                                <fieldset>
                                                                    <p class="profanity">{{ $post->post_description }}</p>
                                                                </fieldset>
                                                                <div class="btn-wrap">
                                                                    <a href="#">Read More</a>
                                                                </div>
                                                                <div class="panel-body-wrap">
                                                                    <div class="wrap-social pull-left">
                                                                        <div class="wrap-inner-icon"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span>{{ (isset($post->postLike) && count($post->postLike) != 0) ? count($post->postLike) : 0  }}</span></div>
                                                                        <div class="wrap-inner-icon"><i class="fa fa-eye" aria-hidden="true"></i> <span>{{ isset(Helpers::postViews($post->id)['views']) ? Helpers::postViews($post->id)['views'] : 0 }}</span></div>
                                                                        <div class="wrap-inner-icon"><i class="fa fa-comment-o" aria-hidden="true"></i><span>{{ (isset($post->postComment) && count($post->postComment) != 0) ? count($post->postComment) : 0  }}</span></div>
                                                                    </div>
                                                                    <div class="status pull-right">
                                                                        <p>Status:<span>{{ $post->status == '1' ? 'Active' : 'Inactive' }}</span></p>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="post-circle">
                                                                    @if(isset($post->postTag))
                                                                        @foreach($post->postTag as $tag)
                                                                            <a href="{{ url('tag/'.Helpers::encode_url($tag->tag->id)) }}"> {{ $tag->tag->tag_name }}</a>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <p>No data found.</p>
                                                    </div>
                                                </div>
                                            @endif