<?php
/**
 * This extends the login box to ask for a twofacor authentication code
 */
if (get_input('twofactor')) {
    echo elgg_view('input/text', array('name'=>'code', 'placeholder'=>'We just sent you an SMS with a code, please type it in here'));
}
