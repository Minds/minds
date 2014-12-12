<?php


$english = array(
	
	/* Minds renamings and overrides
	 */
	'more' => '&#57349;',
	
	'widgets:add' => 'Widgets',
	
	//login
	'login' => 'Enter',
	'logout' => 'Sign Out',
	'register' => 'Create a channel',
	'register:early' => 'Request Early Access',
	
	'post' => 'Post',
	
	//change friends to channels
	'access:friends:label' => "Channels",
	
	'friends' => "Network",
	'friends:yours' => "Channels you have subscribed to",
	'friends:owned' => "Channels %s's subscribed to",
	'friend:add' => "Subscribe",
	'friend:remove' => "Subscribed",

	'friends:add:successful' => "You have successfully subscribed to %s.",
	'friends:add:failure' => "We couldn't subscribe to %s.",

	'friends:remove:successful' => "You have successfully removed %s from your subscriptions.",
	'friends:remove:failure' => "We couldn't remove %s from your subscriptions.",

	'friends:none' => "No channels yet.",
	'friends:none:you' => "You are not subscribed to any channels yet.",

	'friends:none:found' => "No channels were found.",

	'friends:of:none' => "Nobody has subscribed to this channel yet.",
	'friends:of:none:you' => "Nobody has subscribed to you yet. Start adding content and fill in your profile to let people find you!",

	'friends:of:owned' => "People who have subscribed to %s",

	'friends:of' => "Subscribers",
	'friends:collections' => "Channel collections",
	'collections:add' => "New collection",
	'friends:collections:add' => "New channel collection",
	'friends:addfriends' => "Select channels",
	'friends:collectionname' => "Collection name",
	'friends:collectionfriends' => "Channels in collection",
	'friends:collectionedit' => "Edit this collection",
	'friends:nocollections' => "You do not have any collections yet.",
	'friends:collectiondeleted' => "Your collection has been deleted.",
	'friends:collectiondeletefailed' => "We were unable to delete the collection. Either you don't have permission, or some other problem has occurred.",
	'friends:collectionadded' => "Your collection was successfully created",
	'friends:nocollectionname' => "You need to give your collection a name before it can be created.",
	'friends:collections:members' => "Collection members",
	'friends:collections:edit' => "Edit collection",
	'friends:collections:edited' => "Saved collection",
	'friends:collection:edit_failed' => 'Could not save collection.',
	
	'river:friend:user:default' => "%s subscribed to %s",
	
	/**
 * Emails
 */
	'email:settings' => "Email settings",
	'email:address:label' => "Your email address",

	'email:save:success' => "New email address saved.",
	'email:save:fail' => "Your new email address could not be saved.",

	'friend:newfriend:subject' => "%s has subscribed to you!",
	'friend:newfriend:body' => "%s has subscribed to you on Minds!

To view their channel, click here:

%s

You cannot reply to this email.",



	'email:resetpassword:subject' => "Password reset!",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",


	'email:resetreq:subject' => "Request for new password.",
	'email:resetreq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a new password for their account.

If you requested this, click on the link below. Otherwise ignore this email.

%s
",
	
	//river menu
	'river:featured' => 'Featured',
	'river:trending' => 'Trending',
	'river:thumbs-up' => 'Thumbs up',
	'river:thumbs-down' => 'Thumbs down',
	
	//change activity to news
	'news' => 'News', 
	'minds:riverdashboard:addwire' => 'Share your thoughts',
	'minds:riverdashboard:annoucement' => 'Announcement',
	'minds:riverdashboard:changeannoucement' => 'Change the announcement',
	
	//Minds Specific
	'minds:register:terms:failed' => 'Please accept the terms and conditions in order to register',
	'minds:register:terms:read' => 'I accept the terms and conditions',
	'minds:regsiter:terms:link' => ' (read)',
	
	'minds:comments:commentcontent' => '%s: %s',
	'minds:comments:likebutton' => 'Like',
    'minds:comments:unlikebutton' => 'Unlike',
    'minds:comments:commentsbutton' => 'Comment',
    'minds:comments:sharebutton' => 'Share',
    'minds:comments:viewall' => 'View all %s comments',
    'minds:comments:remainder' => 'View remaining %s comments',
    'minds:comments:nocomments' => 'Be first to comment',
    'minds:commenton' => 'Comment on %s',
    'minds:comments:valuecantbeblank' => 'Comment can not be blank',
    'minds:remind' => 'ReMind (repost)',
    'minds:remind:success' => 'Successfully reMinded.',
    
	//river
	'river:remind:object:wall' => '%s reMinded %s\'s thought',
	'river:remind:object:kaltura' => '%s reMinded %s\'s media: %s',
	'river:remind:object:blog' => '%s reMinded %s\'s blog',
	'river:remind:api' => '%s reMinded %s',
	
	'river:feature:object:kaltura' => '%s\'s media %s was featured',
	'river:feature:object:blog' => '%s\'s blog was featured',
	'river:feature:object:album' => '%s\'s album %s was featured',
	'river:feature:object:image' => '%s\'s image %s was featured',
	'river:feature:object:tidypics_batch' => '%s\'s images %s were featured',
	
	/* Quota 
	 */
	'minds:quota:statisitcs:title' => 'Your usage',
	'minds:quota:statisitcs:storage' => 'Storage',
	'minds:quota:statisitcs:bandwidth' => 'Bandwidth',
	
	/**
	 * ONLINE USER STATUS
	 *
	 */
	'minds:online_status:online' => 'Online',
	
	/**
	 * Thoughts
	 */
	 'minds:thoughts' => 'Thoughts',
	
	
	'minds:embed' => 'Embed',
	
	/**
	 * Minds Universal upload form
	 */
	'minds:upload'=>'Upload',
	'minds:upload:file'=>'File',
	'minds:upload:nofile' => 'No file was uploaded.',
	
	/* Licenses
	 */
	'minds:license:all' => "All licenses",
	'minds:license:label' => 'License <a href="' . elgg_get_site_url() . 'licenses" target="_blank"> (?) </a>',
	'minds:license:not-selected' => '-- Please select a license --',
	'minds:license:attribution-cc' => 'Attribution CC BY',
	'minds:license:attribution-sharealike-cc' => 'Attribution-ShareAlike BY-SA',
	'minds:license:attribution-noderivs-cc' => 'Attribution-NoDerivs CC BY-ND',
	'minds:license:attribution-noncommerical-cc' => 'Attribution-NonCommerical CC BY-NC',
	'minds:license:attribution-noncommercial-sharealike-cc' => 'Attribution-NonCommerical-ShareAlike CC BY-NC-SA',
	'minds:license:attribution-noncommercial-noderivs-cc' => 'Attribution-NonCommerical-NoDerivs CC BY-NC-ND',
	'minds:license:publicdomaincco' => 'Public Domain CCO "No Rights Reserved"',
	'minds:license:gnuv3' => 'GNU v3 General Public License',
	'minds:license:gnuv1.3' => 'GNU v1.3 Free Documentation License',
	'minds:license:gnu-lgpl' => 'GNU Lesser General Public License',
	'minds:license:gnu-affero' => 'GNU Affero General Public License',
	'minds:license:apache-v1' => 'Apache License, Version 1.0',
	'minds:license:apache-v1.1' => 'Apache License, Version 1.1',
	'minds:license:apache-v2' => 'Apache License, Version 2.0',
	'minds:license:mozillapublic' => 'Mozilla Public License',
	'minds:license:bsd' => 'BSD License',
	'minds:license:allrightsreserved' => 'All Rights Reserved',
	
	'categories' => 'Category',
	
	'blog:owner_more_posts' => 'More blogs from %s',
	'blog:featured' => 'Featured blogs',
	'readmore' => '→ read more',
	'minds:embed:youtube' => 'Youtube',

    
    
        'register:node' => 'Launch a social network',
        "register:node:testping" => 'Multisite node DNS Test',
        'minds:tier:blurb' => 'Thank you for chosing Minds, please select one of the following payment plans...',
	
	'trending' => 'Trending',

	'admin:appearance:carousel' => 'Carousel',
	'admin:monitization:donations' => 'Donation buttons',
    
    'item:object:__base__' => 'Base objects',
    'item:object:deck_post' => 'Deck Post',
    'item:object:deck_column' => 'Deck Column',
);
		
add_translation("en", $english);
