<?php
    /**
     * English-language strings
     * @package Persona
     */

	$english = array(
	    'persona:login'	=>  'Log in with your email address',
	    'persona:welcome'	=>  'You\'re almost there! We just need you to enter a few details to get started.',
	    
	    'persona:details'	=>  'You\'re almost there!',
	    'persona:details:explanation'	=>  
				    "To get started, we need you to tell us a couple of things about you. These will help you get the most out of the site.",
	    
	    'persona:username'	=>  'Your user identifier',
	    'persona:username:explanation'
				=>  'This is the identifier you use around the site. It needs to be at least four characters long, with no spaces, punctuation or special characters. If your name was John Smith, you might pick JohnSmith. Once you\'ve set this, it can\'t be changed.',
	
	    'persona:name'	=>  'Your name',
	    'persona:name:explanation'
				=>  'This is the name that will be attached to your profile, on every piece of content you post, and on all of your comments. You can change this later.',
	    
	    'persona:settings:instructions' =>
				    'Persona is an easy-to-use authentication standard created by Mozilla. This plugin allows your users to optionally use Persona to log into your Elgg site. All they need is an email address - it\'s arguably easier than Elgg\'s built-in authentication!',
	    'persona:login:use'	=>  'Enable Persona logins on the front page?',
	    
	    'username:invalid'	=>  'The username is invalid. Please enter another one.',
	    'name:invalid'	=>  'You must enter a name!',
	    'persona:registered'
				=>  "You're ready to go! Why not fill in your profile to tell other users a little about you?",
		
	);

	add_translation('en', $english);
