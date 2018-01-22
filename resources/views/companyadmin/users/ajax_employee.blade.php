@if(count($users) > 0)
    @foreach($users as $user)
    @php
        $class = 'industrial';
        if($loop->index % 3 == 1)
            $class = 'nature';
        else if($loop->index % 3 == 2)
            $class = 'architecture';
    @endphp
    <li data-name="{{ $user['name'] }}" class="mix {{ $class }} mix_all userList" style="display: inline-block;  opacity: 1;">
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
                            $profile_pic = asset('assets/img/super-user.PNG');
                            if($user['profile_image'] != "")
                                $profile_pic = asset(PROFILE_PATH.$user['profile_image']);
                        @endphp
                        <img src="{{ $profile_pic }}" alt="super-user">
                    </div>
                    <div class="grid-details">
                        <h4>{{ $user['name'] }}</h4>
                        <a href="mailto:{{ $user['email'] }}">{{ $user['email'] }}</a>
                        <h4>Employee</h4>
                    </div>

                </fieldset>
                <div class="btn-wrap">
                    <a href="{{ url('view_profile/'.$user['id']) }}">Follow</a>
                    <a href="#">Point:246</a>

                </div>
                <div class="panel-body-wrap">
                    <div class="follower-text pull-left">
                        <p>Followers:<span>{{ count($user['followers']) }}</span></p>
                    </div>
                    <div class="follower-text pull-right">
                        <p>Following:<span>{{ count($user['following']) }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </li>
    @endforeach
<div class="all_viewmore col-md-12">
    <a href="javascript:void(0)" id="load_post" onclick="loadMorePost()" data-id="0">View More</a>
</div>

@else
    <div class="col-md-12">
        <p>No Data found.</p>
    </div>
@endif