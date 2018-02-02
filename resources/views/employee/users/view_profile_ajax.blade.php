@if( count($groupDetails) > 0 && !empty($groupDetails))
    @foreach($groupDetails as $group)
        <div class="item">
            <div class="list-block">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <fieldset>
                        <div class="grid-image">
                            @php
                                if(empty($group->group_image))
                                    $group_img = asset('assets/img/business-development.png');
                                else
                                    $group_img = asset('assets/img/'.$group->group_image);
                            @endphp
                            <img alt="super-user" src="{{ asset('assets/img/custome-service.png') }}">
                        </div>
                        <div class="grid-details">
                            <h4>{{ $group->group_name }}</h4>
                        </div>
                    </fieldset>
                    <p class="profanity">
                        {{ $group->description }}
                    </p>
                    <div class="panel-body-wrap">
                        <div class="follower-text pull-left">
                            <p>Total Posts:<span>{{ $group->total_posts }}</span></p>
                        </div>
                        <div class="follower-text pull-right">
                            <p>Total Members:<span>{{ $group->total_members }}</span></p>
                        </div>
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