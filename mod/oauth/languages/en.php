<?php

global $CONFIG;

$en = array(
	'oauth:menu' => 'Applications',
	'oauth:authorized' => 'Authorized Application Tokens',
	'admin:oauthregister' => 'OAuth Consumer Applications',
	
	'oauth:key' => 'Key',
	'oauth:secret' => 'Secret',
	'oauth:version' => 'Version',
	'oauth:callback' => 'Callback URL',

	'oauth:register:inbound' => 'Inbound Consumers',
	'oauth:register:inbound:desc' => 'The following applications have been authorized to request tokens to be authorized by users.',
	'oauth:register:inbound:none' => 'No applications have been registered on this site.',
	'oauth:register:outbound' => 'Outbound Consumers',
	'oauth:register:outbound:desc' => 'This site has been registered to have access to the following external applications.',
	'oauth:register:outbound:none' => 'This site is not a registered consumer of any external sites.',
	'oauth:register:title' => 'Register A New OAuth Application',
	'oauth:register:submit' => 'Register',
	'oauth:register:name:label' => 'Name',
	'oauth:register:name:desc' => 'Human-readable name to identify this application',
	'oauth:register:desc:label' => 'Description',
	'oauth:register:desc:desc' => 'User-readable description of this application',
	'oauth:register:callback:label' => 'Callback URL',
	'oauth:register:callback:desc' => 'Application callback URL (use "oob" for desktop clients)',
	'oauth:register:reva:label' => 'Use OAuth Rev A',
	'oauth:register:reva:desc' => 'Check this box to use OAuth 1.0a instead of OAuth 1.0',
	'oauth:register:outbound:label' => 'Outbound consumer',
	'oauth:register:outbound:desc' => 'Check this box to make this an outbound consumer instead of an inbound consumer (see documentation first before checking this)',
	'oauth:register:key:label' => 'Key',
	'oauth:register:key:desc' => '(leave this field blank to have your key auto generated)',
	'oauth:register:secret:label' => 'Secret',
	'oauth:register:secret:desc' => '(leave this field blank to have your secret auto generated)',
	'oauth:register:show' => 'Register New Application',

	'oauth:authorize:request' => 'The following application is asking for authorization to access your account:',
	'oauth:authorize:authorize' => 'Authorize this application',
	'oauth:authorize:new' => 'Authorize a new application',
	'oauth:authorize:success' => 'Authorization Successful',
	'oauth:authorize:continue' => 'You have successfully authorized %s on this site. Please return to your application and continue.',
	'oauth:authorize:verifier' => 'You will need to enter the following verification code into your application to complete the authorization process:',
	'oauth:authorize:inbound' => 'Inbound Tokens',
	'oauth:authorize:inbound:desc' => 'The following applications have been authorized to access your account. If you no longer wish these applications to have access to your account, you can revoke their access here.',
	'oauth:authorize:inbound:none' => 'No applications have been authorized to access your account.',
	'oauth:authorize:outbound' => 'Outbound Tokens',
	'oauth:authorize:outbound:desc' => 'Your account has authorized access to the following external applictions through this site. If you no longer wish to access these applications from this site you can revoke this access here.',
	'oauth:authorize:outbound:none' => 'Your account has not been set up to access any external applications.',

	'oauth:consumer:unregister' => 'Unregister',
	'oauth:consumer:registeredby' => 'Registered by',
	
	'oauth:consumer:edit:link' => 'Edit',
	'oauth:consumer:edit:title' => 'Edit a Registered OAuth Application',
	'oauth:consumer:edit:submit' => 'Save',
	'oauth:consumer:edit:cancel' => 'Cancel',

	'oauth:token:revoke' => 'Revoke',
	'oauth:token:request' => 'Request Token',
	'oauth:token:access' => 'Access Token',

	'oauth:success' => 'You have connected to %s.',
	'oauth:failure' => 'There was a problem connecting to %s. You will need to re-authenticate.',
	'oauth:tokenfail' => 'There was an error with the token.',

	'item:object:oauthconsumer' => 'OAuth Consumers',
	'item:object:oauthtoken' => 'OAuth Tokens'
	
	);


add_translation('en', $en);
	
