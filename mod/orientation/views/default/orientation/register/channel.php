<?php
$user = elgg_get_logged_in_user_entity();
?>

<div class="blurb">
	Tell the network more about you. 
</div>
<div class="orientation-table orientation-channel">
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Name
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'name', 'value'=>$user->name, 'placeholder'=> 'eg. Your name...')); ?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Website
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'website', 'value'=>$user->website, 'placeholder'=> 'eg. www.minds.com')); ?>
		</div>
	</div>
</div>
<div class="orientation-table orientation-channel">
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Gender
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/dropdown', array('name'=>'gender', 'options_values'=>array('--'=>'   ----- Click to select ------   ', 'male'=>'Male', 'female'=> 'Female'))); ?>
		</div>

		<div class="orientation-table-cell label">
			Location
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/location', array('name'=>'location', 'value'=>$user->location, 'placeholder'=> 'eg. Zip, City')); ?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Birthday 
		</div>
		<div class="orientation-table-cell">
			<?php 
			//echo elgg_view('input/date', array('placeholder'=>'YYYY-MM-DD')); 
				//days
				$day = array('--');
				for($d=1;$d<32;$d++) {
					$day[$d] = $d;
				}
				//months
				$month = array('--');
				for($m=1;$m<13;$m++) {
					$mnth = date('F', mktime(0, 0, 0, $m, 10));
					$month[$m] = $mnth;
				}
				//years
				$year = array('----');
				for($y=1900;$y<2014;$y++) {
					$year[$y] = $y;
				}
				echo elgg_view('input/dropdown', array(
					'name'=>'birthday-day', 
					'options_values'=> $day,
					'value' => $user->birthday_day
				)); 
				echo elgg_view('input/dropdown', array(
					'name'=>'birthday-month', 
					'options_values'=> $month,
					'value' => $user->birthday_month
				)); 
				echo elgg_view('input/dropdown', array(
					'name'=>'birthday-year', 
					'options_values'=> $year,
					'value' => $user->birthday_year
				)); 
			?>
		</div>
	</div>
</div>

<!-- Social inputs -->
<div class="orientation-table channel">
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			<span class="entypo">&#62221;</span> Facebook
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'social_link_facebook', 'value'=>$user->social_link_facebook, 'placeholder'=> 'eg. facebook.com/mindsdotcom')); ?>
		</div>
		<div class="orientation-table-cell label">
			<span class="entypo">&#62218;</span> Twitter
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'social_link_twitter', 'value'=>$user->social_link_twiiter, 'placeholder'=> 'eg. twiiter.com/mindsdotcom')); ?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			<span class="entypo">&#62230;</span>Tumblr
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'social_link_tubmlr', 'value'=>$user->social_link_tumblr, 'placeholder'=> 'eg. tumblr.com/mindsdotcom')); ?>
		</div>
		<div class="orientation-table-cell label">
			 <span class="entypo">&#62233;</span> Linkedin
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'social_link_linkedin', 'value'=>$user->social_link_linkedin, 'placeholder'=> 'eg. linkedin.com/mindsdotcom')); ?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			<span class="entypo">&#62208;</span>Github
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'social_link_github', 'value'=>$user->social_link_github, 'placeholder'=> 'eg. github.com/minds')); ?>
		</div>
	</div>
</div>
