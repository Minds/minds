<?php

echo '<p>In order to create your wallet, you need to enter a password. We recommend that you use a <b>different</b> password to your Minds one</p>';
echo elgg_view('input/password', array('name'=>'password', 'placeholder'=>'enter a password of more than 10 characters'));
echo elgg_view('input/submit', array('value'=>'Create my wallet'));
