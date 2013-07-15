<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2>Create new product...</h2>
<p><label>Tier Title
        <?php echo elgg_view('input/text', array('name' => 'title', 'required' => 'required'));?>
    </label></p>
    
    <p><label>Tier ID
        <?php echo elgg_view('input/text', array('name' => 'product_id', 'required' => 'required'));?>
    </label></p>
    
    
<p><label>Description
        <?php echo elgg_view('input/longtext', array('name' => 'description'));?>
    </label></p>
    
<p><label>Currency
        <?php echo elgg_view('input/dropdown', array('name' => 'currency', 'options_values' => array(
            'GBP' => 'Pound Sterling',
            'EUR' => 'Euro',
            'USD' => 'US Dollar',
        )));?>
    </label></p>
<p><label>Price in currency
        <?php echo elgg_view('input/text', array('name' => 'price', 'required' => 'required'));?>
    </label></p>    

    <p><label>Expiry after purchase
        <?php echo elgg_view('input/dropdown', array('name' => 'expires', 'options_values' => array(
            MINDS_EXPIRES_NEVER => 'Never',
            MINDS_EXPIRES_DAY => 'Day',
            MINDS_EXPIRES_WEEK => 'Week',
            MINDS_EXPIRES_MONTH => 'Month (28 days)',
            MINDS_EXPIRES_YEAR => 'Year (365 days)',
        )));?>
    </label></p>    
    
    <?php echo elgg_view('forms/minds/tiers/extension', $vars); ?>
    <p>
        <?php echo elgg_view('input/submit', array('value' => 'Save')); ?>
    </p>