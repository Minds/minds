<?php
$cards = elgg_get_entities(array('subtype'=>'card', 'owner_guid'=>elgg_get_logged_in_user_guid()));
foreach($cards as $c){
	echo elgg_view('output/url', array('href'=>'', 'text'=>elgg_view_entity($c)));
}
		?>
-- or new card --
<div class="table card-input">
	<div class="row input">
			<div class="cell feature">
				First Name
			</div>
			<div class="cell">
				<input type="text" placeholder="First Name" name="name" />
			</div>
			<div class="cell feature">
				Last Name
			</div>
			<div class="cell">
				<input type="text" placeholder="Last Name" name="name2" />
			</div>
		</div>
		<div class="row input">
			<div class="cell feature">
				Card Type
			</div>
			<div class="cell type">
				<input type="radio" name="card_type" value="visa" id="visa"/> <label for="visa">Visa</label>
				<input type="radio" name="card_type" value="mastercard" id="mastercard"/> <label for="mastercard">Mastercard</label>
				<input type="radio" name="card_type" value="amex" id="amex"/> <label for="amex">Amex</label>
			</div>
		</div>
		<div class="row input">
			<div class="cell feature">
				Card Number
			</div>
			<div class="cell">
				<input type="text" placeholder="Card Number" name="number" /> 
			</div>
			
			<div class="cell feature">
				Expires
			</div>
			<div class="cell">
				<select name="month">
					<option value=""  selected>MM</option>
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
					<option value=""  selected>YYYY</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
					<option value="2016">2016</option>
					<option value="2017">2017</option>
					<option value="2018">2018</option>
					<option value="2019">2019</option>
					<option value="2020">2020</option>
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
				<input type="text" placeholder="CVV" name="sec" /> 
			</div>
			<div class="cell">
				<input type="submit" value="Pay!" class="elgg-button elgg-button-action create"/>
			</div>
		</div>
</div>