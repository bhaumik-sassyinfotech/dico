
<?php
if (count($users) > 0) {
	$index = 0;
	foreach ($users as $user) {
		// var_dump($user['name']);die;
		$index += 1;
		$class = 'industrial';
		if ($index % 3 == 1) {
			$class = 'nature';
		} else if ($index % 3 == 2) {
			$class = 'architecture';
		}

		?>
        <li data-name="{{ $user['name'] }}" class="mix {{ $class }} mix_all userList" style="display: inline-block;  opacity: 1;">
            <div class="list-block super-user">
                <div class="panel-heading">
                    <div class="pull-right">
                        <?php
                        $edit_url = route('user.edit', Helpers::encode_url($user['id']));
                        ?>
                        <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                        <a href="{{$edit_url}}" onclick="window.open('<?=$edit_url?>','_self')"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <fieldset>
                        <div class="grid-image">
                            <?php
                                $profile_pic = asset(DEFAULT_PROFILE_IMAGE);
                                if ($user['profile_image'] != "") {
                                        $profile_pic = asset(PROFILE_PATH . $user['profile_image']);
                                }

                            ?>
                            <img src="{{ $profile_pic }}" alt="super-user">
                        </div>
                        <div class="grid-details">
                            <h4><a onclick="window.open('<?= route('view_profile', Helpers::encode_url($user['id']))?>','_self')" href="{{route('view_profile', Helpers::encode_url($user['id']))}}"><?=$user['name'];?></a></h4>
                            <a href="mailto:{{ $user['email'] }}">{{ $user['email'] }}</a>
                            <h4>@lang("label.CompanyManager")</h4>
                        </div>

                    </fieldset>
                    <div class="btn-wrap">
                    @php
                        $uid = $user['id'];
                        $text = "";
                        if(!empty($user['followers']) && count($user['followers']) > 0)
                        {
                            $text = __('label.Following');
                        } else {
                            $text = __('label.Follow');
                        }
                        $url = route('view_profile',Helpers::encode_url($uid));
                    @endphp
                    <a onclick="window.open('{{ $url }}' ,'_self')" href="{{ $url }}">{{ $text }}</a>
                    <?php $pts = Helpers::user_points($user['id']);?>
                    <a href="#">@lang("label.Point"):{{ $pts['points'] }}</a>
                    </div>
                    <div class="panel-body-wrap">
                        <div class="follower-text pull-left">
                            <p>@lang("label.Followers"):<span><?=$user['followers_count'];?></span></p>
                        </div>
                        <div class="follower-text pull-right">
                            <p>@lang("label.Following"):<span><?=count($user['following']);?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    <?php
} //end-foreach
	?>
<div class="all_viewmore col-md-12">
    <a href="javascript:void(0)" id="load_post" onclick="loadMoreUser()" data-id="0">@lang("label.ViewMore")</a>
</div>
<?php
} //if-end
else {
	?>
    <div class="row">
        <div class="col-xs-12">
            <p>@lang("label.NoDatafound")</p>
        </div>
    </div>
<?php
}
?>