<?php

$ia = elgg_set_ignore_access();

// If We've previously selected a tier while logged out, and have now logged in, then we forward them.
if (elgg_is_logged_in() && $_SESSION['__tier_selected'])
{
    $tier = get_entity($_SESSION['__tier_selected']);
    $url = elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid;
	$url = elgg_add_action_tokens_to_url($url);

    unset ($_SESSION['__tier_selected']);

    forward($url);
}

// Get tiers
$tiers = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'minds_tier',
	'limit' => 4
));

//sort the tiers by price
usort($tiers, function($a, $b){
	if($a->price === 'Custom')
		return 1000;
	return $a->price - $b->price;
});

?>
<div id="tiers">
	<div class="row thead">
		<div class="cell feature">&nbsp;</div>
		<?php 
			foreach($tiers as $tier){
				if($tier->price === 'Custom')
					echo '<div class="cell">'. $tier->price . '</div>';
				else 
					echo '<div class="cell">$'. $tier->price . '/month</div>';
			}
		?>
	</div>
	<?php 
		foreach(minds\plugin\minds_nodes\start::tiersGetFeatures() as $feature){
			echo '<div class="row">';
				echo '<div class="cell feature">'.elgg_echo("minds_tiers:feature:$feature").'</div>';
				foreach($tiers as $tier)
					echo '<div class="cell">'. (isset($tier->$feature) ? $tier->$feature : 'X') . '</div>';
			echo '</div>';
		}
	?>
	<div class="row tfoot">
		<div class="cell feature">See the terms and conditions</div>
		<?php 
			foreach($tiers as $tier){
				$class = "tier-$tier->price";
				$button =  elgg_view('output/url', array(
					'is_action' => true, 
					'id' => 'tier-select-button', 
					'data-guid' => $tier->guid,
					'data-price' => $tier->price,
					'href' => '#', 
					//'text' => $tier->price > 0 ? 'Coming soon' : 'Select', 
					'text' => $tier->price === 'Custom' ? 'Contact Us' : 'Select',
					'class' => 'elgg-button elgg-button-action '.$class,
				));
				echo '<div class="cell">'. $button . '</div>';
			}
		?>
	</div>
</div>

<?php
/**
 * Section 2 
 * Select your domain...
 */
?>
<div class="nodes-table domain hide">
	<div class="row thead">
		<div class="cell feature">Select Domain</div>
		<div class="cell free">
			<input type="text" placeholder="eg. node" name="domain" disabled/> .minds.com
		</div>
	</div>
	<div class="row paid">
		<div class="cell feature">Own Domain?</div>
		<div class="cell paid">
			<input type="text" placeholder="eg. www.myawesomesite.com" name="domain" disabled/>
		</div>
	</div>
	<div class="row">
		<div class="cell"></div>
		<div class="cell availability">
		</div>
	</div>
</div>


<?php
/**
 * Step 3. Create a minds account
 * 
 */
 
if(!elgg_is_logged_in()){
?>
<div class="nodes-table account hide">
	<div class="row thead">
		<div class="cell feature">Create your account</div>
		<div class="cell"></div>
	</div>
	<div class="row input">
		<div class="cell custom">
			Username
		</div>
		<div class="cell">
			<input type="text" placeholder="eg. einstein" name="username" disabled/>
		</div>
	</div>
	<div class="row input">
		<div class="cell custom">
			Email
		</div>
		<div class="cell">
			<input type="text" placeholder="eg. you@email.com" name="email" disabled/>
		</div>
	</div>
	<div class="row input">
		<div class="cell custom">
			Password
		</div>
		<div class="cell">
			<input type="password" placeholder="something secure!" name="password" disabled/>
		</div>
	</div>
	<div class="row input">
		<div class="cell custom">
			<div class="elgg-button elgg-button-action create">Create!</div>
		</div>
	</div>
	<div class="row response">
		<div class="cell"></div>
		<div class="cell response">
		</div>
	</div>
</div>
<?php 
}

/**
 * Step 4. Payment
 * 
 */
 
?>
<div class="nodes-table payment hide">
	<div class="row thead">
		<div class="cell feature">Payment</div>
		<div class="cell"></div>
		<div class="cell"></div>
		<div class="cell"></div>
	</div>
	
	
	<div class="row response">
		<div class="cell reason"></div>
		<div class="cell reason2"></div>
	</div>
	
	<div class="row input plan">
		<div class="cell feature">
			Plan
		</div>
		<div class="cell radio">
			<input type="radio" name="plan" value="monthly"/>
			<span class="x-month">$X</span>/month
		</div>
		<div class="cell radio">
			<input type="radio" name="plan" value="yearly" checked/>
			<span class="x-year">$X</span>/year
			<span class="saving" style="margin-left:12px;vertical-align: middle; color:#888; font-size:11px;">
				Save <span class="x-save">$X</span>
			</span>
		</div>
		<div class="cell"></div>
	</div>
	
	<div class="row input">
		<div class="cell feature">
			First Name
		</div>
		<div class="cell">
			<input type="text" placeholder="First Name" name="name" disabled/>
		</div>
		<div class="cell feature">
			Last Name
		</div>
		<div class="cell">
			<input type="text" placeholder="Last Name" name="name2" disabled/>
		</div>
	</div>
	<div class="row input">
		<div class="cell feature">
			Card Type
		</div>
		<div class="cell type">
			<a class="visa">Visa</a>
			<a class="mastercard">MasterCard</a>
			<a class="amex">Amex</a>
		</div>
	</div>
	<div class="row input">
		<div class="cell feature">
			Card Number
		</div>
		<div class="cell">
			<input type="text" placeholder="Card Number" name="number" disabled/> 
		</div>
		
		<div class="cell feature">
			Expires
		</div>
		<div class="cell">
			<select name="month">
				<option value="" disabled selected>MM</option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
			</select>
			
			<select name="year">
				<option value="" disabled selected>YYYY</option>
				<option value="01">2014</option>
				<option value="02">2015</option>
				<option value="03">2016</option>
				<option value="04">2017</option>
				<option value="05">2018</option>
				<option value="06">2019</option>
				<option value="07">2020</option>
			</select>

		</div>
	</div>
	
	<div class="row input">
		<div class="cell feature">
			CVV
			<span class="helper">
				Last 3 or 4 digits found on the signature strip
			</span>
		</div>
		<div class="cell">
			<input type="text" placeholder="CVV" name="sec" disabled/> 
		</div>
		<div class="cell">
			<div class="elgg-button elgg-button-action create">Pay!</div>
		</div>
	</div>

</div>

<div class="nodes-table referrer">
	<div class="row thead">
		<div class="cell feature">Referrer</div>
		<div class="cell"></div>
	</div>
	
	<div class="row">
		<div class="cell">
			<?php echo elgg_view('input/autocomplete', array('data-type'=>'user', 'placeholder'=>'Enter the username of who referred you', 'class'=>'user-lookup', 'value'=>get_input('referrer'))); ?>
		</div>
	</div>

	<div class="row response">
		<div class="cell">
		</div>
	</div>

</div>



<div class="nodes-table launch hide">
	<div class="row thead">
		<div class="cell feature">Launch</div>
		<div class="cell"></div>
	</div>
	
	<div class="row response">
		<div class="cell">
			Your node is now complete. Please click launch in order to complete your transaction.
		</div>
		<div class="cell">
			<div class="elgg-button elgg-button-action create">Launch.</div>
		</div>
	</div>

</div>



<div class="nodes-table contact" style="display:none;">
	<div class="row thead">
		<div class="cell feature">Contact</div>
		<div class="cell"></div>
	</div>
	
	<div class="row input">
		<div class="cell">
			<input name="email" placeholder="Your Email Address" value="<?php echo elgg_is_logged_in() ? elgg_get_logged_in_user_entity()->email : '';?>"/> 
		</div>
		<div class="cell">
			<textarea placeholder="Tell us more about your site. " name="message"></textarea>
		</div>
	</div>
	
	<div class="row input">
		<div class="cell">
			Please leave your email address and a message and we will get back to your shortly. 
		</div>
		<div class="cell">
			<div class="elgg-button elgg-button-action send">Send.</div>
		</div>
	</div>
	
	<div class="row response" style="display:none;">
		<div class="cell">
			Thanks. We will be in touch soon.
		</div>
	</div>
</div>


