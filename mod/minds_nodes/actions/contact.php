<?php
$email =get_input('email');
$message = get_input('message');

elgg_send_email($email, array('mark@minds.com', 'bill@minds.com'), 'New Enquiry', $message);
