<?php

    try {
	$value = get_input('value');
	$currency = get_input('currency', 'USD');
	$to_user = get_user(get_input('to_user'));

	gatekeeper();

	if (!$value) throw new \Exception ('No tip amount specified');
	if (!$currency) throw new \Exception ('No currency specified');
	if (!$to_user) throw new \Exception ('Receiving user not found!');

	if ($currency == 'BTC')
	    $bitcoin = $value; // We're talking bitcoins
	else {
	    $bitcoin = minds\plugin\bitcoin\bitcoin()->convertToBTC($value, $currency); // Not bitcoin, needs conversion
	    if (!$bitcoin) throw new \Exception("There was a problem converting $value $currency to bitcoin");
	}
	
	if (!minds\plugin\bitcoin_tipjar\tipjar()->tip($to_user, $bitcoin))
		throw new \Exception("Sorry, there was a problem sending your tip :(");
	
	system_message("Tip sent!");
	
	//forward(elgg_get_site_url() . 'bitcoin/send?address=' . $address . '&value=' . $bitcoin);

    } catch (\Exception $e) {
	register_error($e->getMessage());
	error_log('Bitcoin: ' . $e->getMessage());
    }