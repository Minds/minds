<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

$translations = array(
	'minds:archive' => 'Archive',

	'photos' => 'Albums',
	
	'river:remind:object:album' => '%s reminded %s',
	
	/**
	 * Navigation
	 */
    'minds:archive:upload' => 'Upload',
    'minds:archive:angularUploader' => 'New angularUploader',
	'minds:archive:all' => 'All content',
	'minds:archive:featured' => 'Featured content',
	'minds:archive:top' => 'Top content',
	'minds:archive:mine' => 'My content',
	'minds:archive:network' => 'My network content',
	'minds:archive:owner' => '%s\'s content',
	'minds:archive:owner:network' => '%s\'s network content',
	
	'minds:archive:upload:videoaudio' => 'Video & Audio',
	
	'minds:archive:file:replace' => 'Replace file',
	
	'minds:archive:download' => 'Download',
	
	'minds:archive:upload:videoaudio' => 'Upload Video/Audio',
	'minds:archive:album:create' => 'Create an album',
	'minds:archive:upload:others' => 'Upload images, files + more',

	'minds:archive:delete:success' => 'The file has been removed from your archive',
	'minds:archive:delete:error' => 'There was a problem. We can not delete this file at the moment',
    'minds:archive:delete:notexists' => 'The file already been deleted or not exists',

	'kaltura_video' => 'Video/Audio',
	'kalturavideo:label:partner_id' => "Partner ID",
	'kalturavideo:error:misconfigured' => "Misconfigured plugin or auth error with Kaltura!",
	'kalturavideo:error:notconfigured' => "The plugin is not configured!",
	'kalturavideo:error:missingks' => 'Probably you have a mistake in the "Administrator Secret" or "Web Service Secret" configuration.',
	'kalturavideo:error:partnerid' => "This error normaly appears if you are not a partner of Kaltura. Please read the README file and configure this plugin!",
	'kalturavideo:error:readme' => "Please read the README file and configure this plugin!",
	'kalturavideo:label:closewindow' => "Close window",
	'kalturavideo:label:select_size' => "Select player size",
	'kalturavideo:label:large' => "Large",
	'kalturavideo:label:small' => "Small",
	'kalturavideo:label:insert' => "Insert media",
	'kalturavideo:label:edit' => "Edit media",
	'kalturavideo:label:edittitle' => "Edit media title",
	'kalturavideo:label:miniinsert' => "Insert",
	'kalturavideo:label:miniedit' => "Edit",
	'kalturavideo:label:cancel' => "Cancel",
	'kalturavideo:label:gallery' => "Gallery",
	'kalturavideo:label:next' => "Next",
	'kalturavideo:label:prev' => "Previous",
	'kalturavideo:label:start' => "Start",
	'kalturavideo:label:newvideo' => "Upload new media",
	'kalturavideo:label:upload' => "Upload",
	'kalturavideo:label:newvideocam' => "Record from webcam",
	'kalturavideo:label:newsimplevideo' => "Create a media",
	'kalturavideo:label:gotoconfig' => "Please configure properly the Kaltura under ",

	//title of the menu, put whatever you want, for example 'Kaltura videos'
	'archive' => "Archive",
	'kalturavideo:label:adminvideos' => "Archive",
	'kalturavideo:label:myvideos' => "My media",
	'kalturavideo:label:length' => "Length:",
	'kalturavideo:label:plays' => "Plays:",
	'kalturavideo:label:created' => "Created:",
	'kalturavideo:label:details' => "View details",
	'kalturavideo:label:view' => "View video",
	'kalturavideo:label:delete' => "Delete media",
	'kalturavideo:prompt:delete' => "Are you sure to permanently delete this?",
	'kalturavideo:action:deleteok' => "Media with id %ID% was deleted.",
	'kalturavideo:action:deleteko' => "Media with id %ID% cannot be deleted!",
	'kalturavideo:action:updatedok' => "Media with id %ID% was updated.",
	'kalturavideo:action:updatedko' => "Media with id %ID% cannot be updated!",
	'kalturavideo:label:thumbnail' => "Thumbnail url:",
	'kalturavideo:label:sharel' => "HTML share code (big applet):",
	'kalturavideo:label:sharem' => "HTML share code (little applet):",
	'kalturavideo:text:statusnotchanged' => "The privacy status cannot be changed!",
	'kalturavideo:text:novideos' => "Sorry, you don't have and media yet!",
	'kalturavideo:text:nopublicvideos' => "Sorry, there is not any public media yet!",
	'kalturavideo:label:author' => "Author:",
	'kalturavideo:text:nofriendsvideos' => "Your fchannels do not have any media yet",
	'kalturavideo:text:nouservideos' => "This user does not have any media yet",
	'kalturavideo:label:showvideo' => "Show the media",
	'kalturavideo:show:advoptions' => "Show sharing",
	'kalturavideo:hide:advoptions' => "Hide sharing",
	'kalturavideo:label:trendingvideos' => "Top media",
	'archive:popular' => 'Popular',
	'archive:mostviewed:title' => 'Most viewed',

	'kalturavideo:text:widgetdesc' => "This widget allows you to show automatically your latest media.",
	'kalturavideo:error:edittitle' => "Error! This title can not be changed!",
	'kalturavideo:error:objectnotavailable' => "Object not available. Please reload the page.",
	'kalturavideo:label:recreateobjects' => "Recreate all media objects",
	'kalturavideo:edit:notallowed' => "You can not edit this!",
	'kalturavideo:river:created' => "%s created",
	'kalturavideo:river:annotate' => "%s commented on",
	'kalturavideo:river:item' => "an video",
	'kalturavideo:river:shared' => "Collaborative video",
	'river:update:object:kaltura_video' => '%s uploaded new media titled %s',
	'river:create:object:kaltura_video' => '%s uploaded new media titled %s',
	'kalturavideo:label:videosfrom' => "Media by %s",
	'kalturavideo:user:showallvideos' => "Show all media from this user",
	'kalturavideo:strapline' => "%s",

	 /**
     * kaltura_video rating system
	 **/
	'kalturavideo:rating' => "Rating",
	'kalturavideo:yourrate' => "Your rating:",
	'kalturavideo:rate' => "Vote!",
	'kalturavideo:votes' => "votes",
	'kalturavideo:ratesucces' => "Your rating has been succesfully saved.",
	'kalturavideo:rateempty' => "Please select a value before rating!",
	'kalturavideo:notrated' => "You have already rated this item!",

	/**
	 * Groups
	 **/
	'kalturavideo:groupprofile' => "Collaborative media",
	'kalturavideo:text:nogroupvideos' => "This group does not have any media yet",
	'kalturavideo:label:collaborative' => "Collaborative",
	'kalturavideo:text:collaborative' => "This allows other members of this group to edit this media too!",
	'kalturavideo:text:collaborativechanged' => "Collaborative status for the media %1% changed!",
	'kalturavideo:text:collaborativenotchanged' => "Collaborative status for the media %1% cannot be changed!",
	'kalturavideo:text:iscollaborative' => "This is a collaborative video, you can edit it!",
	'kalturavideo:userprofile' => "Collaborative videos",

	//New after version 1.0

	//default title for a new created video, you can put 'New video' for example
	'kalturavideo:title:video' => "New Collaborative Media",
	//elgg notification
	'kalturavideo:newvideo' => "New collaborative media",

	'kalturavideo:label:friendsvideos' => "My Network media",
	'kalturavideo:label:allgroupvideos' => "Groups' media",
	'kalturavideo:label:allvideos' => "All site media",
	'kalturavideo:clickifpartner' => "Click here if you already have a Partner ID",
	'kalturavideo:clickifnewpartner' => "Click here if you don't have a Partner ID",
	'kalturavideo:notpartner' => "Not a Kaltura user?",
	'kalturavideo:forgotpassword' => "forgot password?",
	'kalturavideo:enterkmcdata' => "Please enter your Kaltura Management Console (KMC) Email & password",
	'kalturavideo:label:sitename' => "Elgg Site Name",
	'kalturavideo:label:name' => "Enter Name",
	'kalturavideo:label:email' => "Enter Email",
	'kalturavideo:label:websiteurl' => "Website URL",
	'kalturavideo:label:description' => "Description",
	'kalturavideo:label:phonenumber' => "Phone Number",
	'kalturavideo:label:contenttype' => "Content Type",
	'kalturavideo:label:adultcontent' => "Do you plan to display adult content?",
	'kalturavideo:label:iagree' => "I Accept %s",
	'kalturavideo:label:termsofuse' => "Terms of Use",
	'kalturavideo:wizard:description' => "Please describe how you plan to use Kaltura's video platform",
	'kalturavideo:wizard:phonenumber' => "Enter phone number for contact",
	'kalturavideo:mustaggreeterms' => "You must agree to the Kaltura Terms of Use",
	'kalturavideo:mustenterfields' => "You must fill all fields!",
	'kalturavideo:registeredok' => "Kaltura server are contacted and registered successfully!",
	'kalturavideo:error:noid' => "The object requested is not available!",
	'kalturavideo:logintokaltura' => "%s to the Kaltura Management Console (KMC) for advanced media management",
	'kalturavideo:login' => "Login",
	'kalturavideo:text:nogroupsvideos' => "Sorry, there are not videos from groups yet!",
	'kalturavideo:label:defaultplayer' => "Default media player",
	'kalturavideo:editpassword' => "Click here to change this data",
	'kalturavideo:text:recreateobjects' => "Try to do this if you are upgrading the Kaltura plugin from any older version prior 1.0 or some videos are deleted outside this Elgg installation.
All Elgg video objects will be checked and recreated, this can be a slow process.

Note that the metadata stored in Kaltura servers will be updated in order to match the Elgg data.
Objects not created by the Kaltura Elgg Plugin will not be touched.",
	'kalturavideo:text:statuschanged' => 'Access status for media %2% changed to "%1%"',
	'kalturavideo:howtoimportkaltura' => 'You can import any media from Kaltura CMS created outside Elgg, to do that login into your Kaltura CMS acount (%URLCMS%) and put the admin tags to "<b>%TAG%</b> <em>elgg_username_to_import</em>". Then run "Recreate all video objects" again.',
	'kalturavideo:num_display' => "Number of media items to display",
	'kalturavideo:start_display' => "Start with the media number",
	'kalturavideo:label:addvideo' => "Embed media",
	'kalturavideo:label:addbuttonlongtext' => "Append the button %s to textareas",
	'kalturavideo:option:simple' => "Simple (a button on top of the textareas)",
	'kalturavideo:option:tinymce' => "Try to integrate into tinyMCE",
	'kalturavideo:note:addbuttonlongtext' => "If you choose to add this button, then users can put &lt;object&gt; html tags into text boxes. Even if htmlawed is enabled.",
	'kalturavideo:enablevideo' => "Enable collaborative media for this group",
	'kalturavideo:label:groupvideos' => "Group media",
	'kalturavideo:user' => "%s's media",
	'kalturavideo:user:friends' => "%s's network media",
	'kalturavideo:notfound' => "Sorry; we could not find the specified media post. ",
	'kalturavideo:posttitle' => "%s's video: %s",
	'kalturavideo:label:editdetails' => "Edit information",
	'ingroup' => "in group",

	//new from 10-12-2009
	'item:object:kaltura_video' => "Kaltura Videos",
	'kalturavideo:thumbnail' => "Thumbnail",
	'kalturavideo:comments:allow' => "Allow comments",
	'kalturavideo:rating:allow' => "Allow rating",
	//these get inserted into the river links to take the user to the entity
	'kalturavideo:river:updated' => "%s updated",
	'kalturavideo:river:create' => "new media titled",
	'kalturavideo:river:update' => "media titled",
	//the river search the translation with the object label (kaltura_video)
	'kaltura_video:river:annotate' => "a comment on this media",
	'kalturavideo:river:rates' => "%s rates this media",
	//widget title label
	'kalturavideo:label:latest' => "Media",
	'kalturavideo:label:videoaudio' => "Video & Audio",
	//widget options
	'kalturavideo:showmode' => "List mode",
	'kalturavideo:showmode:thumbnail' => "Thumbnails list",
	'kalturavideo:showmode:embed' => "Embeded mini-players",
	'kalturavideo:label:morevideos' => "More media",
	'kalturavideo:more' => "More",
	//donate button in tools administrations
	'kalturavideo:note:donate' => "Do you like this plugin? Please consider donating:",

	//new from  11-21-2009
	'kalturavideo:error:curl' => "Extension CURL not loaded!\nPlease be sure to enable this extension in order to use this plugin!\nPlease read the README file for more information.",

	//new from version 1.1
	'kalturavideo:menu:server' => "Server",
	'kalturavideo:menu:custom' => "Player &amp; Editor",
	'kalturavideo:menu:behavior' => "Plugin behavior",
	'kalturavideo:menu:advanced' => "Advanced",
	'kalturavideo:menu:credits' => "Credits",
	'kalturavideo:admin' => "Kaltura Video Admin",
	'kalturavideo:admin:serverpart' => "Streaming Server",
	'kalturavideo:admin:partnerpart' => "Partner ID Configuration",
	'kalturavideo:admin:wizardpart' => "Kaltura Online Video Platform Registration",
	'kalturavideo:admin:player' => "Video Player Options",
	'kalturavideo:admin:editor' => "Online Video Editor Options",
	'kalturavideo:admin:textareas' => "Textareas Integration",
	'kalturavideo:admin:credits' => "Credits",
	'kalturavideo:admin:suport' => "Suport",

	'kalturavideo:server:info' => "To use the Kaltura Platform features, you need to have a valid Partner ID with the Kaltura Server.",
	'kalturavideo:server:type' => "Choose your Kaltura Server",
	'kalturavideo:server:kalturacorp' => "Kaltura.com hosted edition",
	'kalturavideo:server:kalturace' => "Kaltura Community Edition (CE)",
	'kalturavideo:server:corpinfo' => "Signing up with the Kaltura.com hosted edition, provide you with a free trial account.
Your trial account includes 10GB of free hosting and streaming.",
	'kalturavideo:server:ceinfo' => "Kaltura Community Edition is a self-hosted, community supported version of Kaltura's Open Source Online Video Platform.",
	'kalturavideo:server:moreinfo' => "Find more information about",
	
	'kalturavideo:server:ceurl' => "Kaltura CE Server URL",
	'kalturavideo:server:ceurl:api' => "Kaltura CE API Server URL",
	
	'kalturavideo:server:alertchange' => "WARNING: By changing this data will lose your existing videos!
Are you sure?
Probably you want to recreate objects after this action.",
	'kalturavideo:wizard:cannot' => "You cannot use this page with your current configuration!",
	'kalturavideo:advanced:recreateobjects' => "I agree, please recreate all video objects now!",
	'kalturavideo:recreate:initiating' => "Retrieving information from Kaltura server...",
	'kalturavideo:recreate:stepof' => "Retrieving videos (step %NUM% of %TOTAL%)...",
	'kalturavideo:recreate:processedvideos' => "Processed videos %NUMRANGE% of %TOTAL%...",
	'kalturavideo:recreate:done' => "All videos successfully processed!",
	'kalturavideo:recreate:donewitherrors' => "Videos processed with errors!",
	'kalturavideo:changeplayer' => "Here you can change the default player for the new created videos (old videos will not be affected).",
	'kalturavideo:generic' => "Generic",
	'kalturavideo:customplayer' => "My Own Customized Player",
	'kalturavideo:customkcw' => "My Own Contribution Wizard",
	'kalturavideo:customeditor' => "My Own Editor",
	'kalturavideo:uiconf1' => "Kaltura's Application Studio Player ID",
	'kalturavideo:text:uiconf1' => '%s to the Kaltura Management Console (KMC) to create your own players.<br />
With your own custom player, you can change the default size of the player besides of many more features.<br />
Custom players are defined in "Application Studio" sub menu in KMC',
	'kalturavideo:uiconf2' => "Kaltura's Contribution Wizard (KCW) ID",
	'kalturavideo:uiconf3' => "Kaltura's Editor (KSE) ID",
	'kalturavideo:error:uiconf1' => "Error! Wrong Player ID.",
	'kalturavideo:error:uiconf2' => "Error! Wrong KCW ID.",
	'kalturavideo:error:uiconf3' => "Error! Wrong KSE ID.",
	'kalturavideo:uiconf:getlist' => "Get a list of them",
	'kalturavideo:uiconf1:notfound' => "No custom players found!",
	'kalturavideo:uiconf2:notfound' => "No custom KCW found!",
	'kalturavideo:uiconf3:notfound' => "No custom KSE found!",
	'kalturavideo:playerupdated' => "Player &amp; editor options successfully updated.",
	'kalturavideo:label:defaulteditor' => "Default video editor",
	'kalturavideo:editor:light' => "Editor in light color schemes",
	'kalturavideo:editor:dark' => "Editor in dark color schemes",
	'kalturavideo:label:defaultkcw' => "Default uploader (Contribution Wizard)",
	'kalturavideo:kcw:light' => "Uploader in light color schemes",
	'kalturavideo:kcw:dark' => "Uploader in dark color schemes",

	'kalturavideo:admin:videoeditor' => "Video Editor",
	'kalturavideo:admin:rating' => "Video Rating",

	'kalturavideo:behavior:alloweditor' => "Allow users to edit his videos",
	'kalturavideo:alloweditor:full' => "Yes, show the uploader and then the editor when creating a video",
	'kalturavideo:alloweditor:simple' => "Yes, but do not show the editor when creating a video (users can edit it after)",
	'kalturavideo:alloweditor:no' => "No, do not allow video editing at all",
	'kalturavideo:alloweditor:notallowed' => "Video editing is not allowed!",

	'kalturavideo:behavior:enablerating' => "Enable the built-in video rating",

	//new from 1.2
	'kalturavideo:admin:others' => "Other options",
	'kalturavideo:behavior:widget' => "Include videos widget on index page (custom_index must be enabled)",
	'kalturavideo:behavior:numvideos' => "Number of videos to display",
	'kalturavideo:option:single' => "Yes, a simple list (like latest blogs)",
	'kalturavideo:option:multi' => "Yes, a tab list with Latest, Top played, Top Commented, Top rated (like latest iZAP Videos)",

	'kalturavideo:index:toplatest' => "Top collaborative media",
	'kalturavideo:index:latest' => "Latest",
	'kalturavideo:index:played' => "Played",
	'kalturavideo:index:commented' => "Commented",
	'kalturavideo:index:rated' => "Rated",
	
	/* Fallback for early videos */
	'minds:license:cca' => 'Creative Commons Attribution',
	'minds:license:ccs' => 'Creative Commons Sharealike',
	'minds:license:gpl' => 'GPL',
	
	'kalturavideo:notconverted' => 'This media is still being converted.',
	
	/*
	 * Archive Menus
	 */
	'archive:all' => 'Archive: All',
	'archive:owner' => 'Archive: %s',
	'archive:top' => 'Archive: Top',
	'archive:network' => 'Archive: Network',
	
	'archive:upload:videoaudio' => 'Video & Audio',
	'archive:upload:others' => 'Images & Files',
	
	/*
	 * Archive featured, sponsored& trending/popular
	 */
	'archive:popular:title' => 'Popular',
	'archive:featured:title' => 'Featured',
	'archive:featured:action' => 'Feature',
	'archive:featured:un-action' => 'Un-feature',
	'archive:morefromuser:title' => 'More from %s',
	
	'archive:monetized:action' => 'Monetize',
	'archive:monetized:un-action' => 'Un-monetize',
	
	'archive:owner_tag' => 'By ',

	/*
	 * Other strings
	 */
	'archive:close' => 'Close',
	'archive:invalid_image' => 'Invalid Image',
	'archive:no_description' => 'No Description',
	'archive:no_tags' => 'No Tags',
	'file' => 'File'
);

add_translation("en", $translations);

?>
