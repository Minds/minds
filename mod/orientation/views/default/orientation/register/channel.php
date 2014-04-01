<?php
$user = elgg_get_logged_in_user_entity();
?>

<div class="blurb">
	Tell the network more about you. 
</div>
<div class="orientation-block channel-info">
	<span style="clear:both;">
		<label>Name</label>
		<?php echo elgg_view('input/text', array('name'=>'name', 'value'=>$user->name, 'placeholder'=> 'eg. Your name...')); ?>
	</span>
	<span style="clear:both;">
		<label>Website</label>
		<?php echo elgg_view('input/text', array('name'=>'website', 'value'=>$user->website, 'placeholder'=> 'eg. www.minds.com')); ?>
	</span>
	<span style="clear:both;">
		<label>Gender</label>
		<?php echo elgg_view('input/dropdown', array('name'=>'gender', 'options_values'=>array('--'=>'-- Select --', 'male'=>'Male', 'female'=> 'Female'))); ?>
	</span>
	<span>
		<label>Location</label>
		<?php echo elgg_view('input/location', array('name'=>'location', 'value'=>$user->location, 'placeholder'=> 'eg. Zip, City')); ?>
	</span>
	<span>
	<label>Birthday</label>
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
	</span>
</div>
