@if(count($groups) > 0)
    @foreach($groups as $group)
    @php
        $class = 'industrial';
        if($loop->index % 3 == 1)
            $class = 'nature';
        else if($loop->index % 3 == 2)
            $class = 'architecture';
    @endphp
    <li data-name="{{ $group['group_name'] }}" class="mix {{ $class }} mix_all userList" style="display: inline-block;  opacity: 1;">
        <div class="list-block super-user">
            <div class="panel-heading">
                <div class="pull-right">
                    <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                    <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>

            </div>
            <div class="panel-body">
                <fieldset>
                    <div class="grid-image">
                        @php
                            $profile_pic = asset(DEFAULT_GROUP_IMAGE);
                            if( $group['group_image'] != "" )
                                $profile_pic = asset(GROUP_PATH.$group['group_image']);
                        @endphp
                        <img src="{{ $profile_pic }}" alt="super-user">
                    </div>
                    <div class="grid-details">
                        <h4><a class="profanity" href="{{ route('group.edit',[Helpers::encode_url($group['id'])]) }}"> {{ $group['group_name'] }} </a></h4>
                        <h4 class="profanity"> {{ $group['description'] }} </h4>
                    </div>
                </fieldset>
                <hr>
                <div class="panel-body-wrap">
                    <div class="follower-text pull-left">
                        <p>@lang("label.Members"):<span>{{ $group['group_users_count'] }}</span></p>
                    </div>
                    <div class="follower-text pull-right">
                        <p>@lang("label.Posts"):<span>{{ $group['group_posts_count'] }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </li>
    @endforeach

    <div class="all_viewmore col-md-12">
        <a href="javascript:void(0)" id="load_post" onclick="loadMorePost()" data-id="0">@lang("label.ViewMore")</a>
    </div>

@else
    <div class="col-md-12">
        <p>@lang("label.NoDatafound")</p>
    </div>
@endif