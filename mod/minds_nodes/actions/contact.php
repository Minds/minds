<?php
$email =get_input('email');
$message = get_input('message');

elgg_send_email($email, 'mark@minds.com', 'New Enquiry', $message);