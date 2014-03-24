<?php

if ($guid = get_input('guid')) {
	$obj = get_entity($guid);
}
    
if ($obj) { 
    echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $obj->guid));
    ?>
<h2>Edit product...</h2>
<?php } else { ?>
	<h2>Create new product...</h2>
<?php } ?>
<p>
	<label>Tier Title <?php echo elgg_view('input/text', array('name' => 'title', 'required' => 'required', 'value' => $obj->title));?>
    </label>
</p>
    
<p>
	<label>Tier ID <?php echo elgg_view('input/text', array('name' => 'product_id', 'required' => 'required', 'value' => $obj->product_id));?>
</label>
</p>
     
<p><label>Features</label></p>
<?php foreach(minds_tiers_get_features() as $feature){
	echo "<p><label>$feature</label>" . elgg_view('input/text', array('name'=>$feature, 'value'=>$obj->$feature)) . "</p>";
}?>
   
<p><label>Currency
        <?php echo elgg_view('input/dropdown', array('name' => 'currency', 'options_values' => array(
            'GBP' => 'Pound Sterling',
            'EUR' => 'Euro',
            'USD' => 'US Dollar',
        ), 'value' => $obj->currency));?>
    </label></p>
<p><label>Price in currency
        <?php echo elgg_view('input/text', array('name' => 'price', 'required' => 'required', 'value' => $obj->price));?>
    </label></p>    

    <p><label>Expiry after purchase
        <?php echo elgg_view('input/dropdown', array('name' => 'expires', 'options_values' => array(
            MINDS_EXPIRES_NEVER => 'Never',
            MINDS_EXPIRES_DAY => 'Day',
            MINDS_EXPIRES_WEEK => 'Week',
            MINDS_EXPIRES_MONTH => 'Month (28 days)',
            MINDS_EXPIRES_YEAR => 'Year (365 days)',
        ), 'value' => $obj->expires));?>
    </label></p>    

<p><label>Allowed a domain?
        <?php echo elgg_view('input/dropdown', array('name' => 'allowed_domain', 'options_values' => array(
            'yes' => 'Yes',
		'no' => 'No'
        ), 'value' => $obj->allowed_domain));?>
    </label></p>
    
    <?php echo elgg_view('forms/minds/tiers/extension', $vars + array('obj' => $obj)); ?>
    <p>
        <?php echo elgg_view('input/submit', array('value' => 'Save')); ?>
    </p>
