<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$english = array(
	
	// Menu items and titles	
	'market' => "Market post",
	'market:posts' => "Market Posts",
	'market:title' => "Market",
	'market:user:title' => "%s's posts on The Market",
	'market:user' => "%s's Market",
	'market:user:friends' => "%s's friends Market",
	'market:user:friends:title' => "%s's friends posts on The Market",
	'market:mine' => "My Market",
	'market:mine:title' => "My posts on The Market",
	'market:posttitle' => "%s's Market item: %s",
	'market:friends' => "Friends Market",
	'market:friends:title' => "My friends posts on The Market",
	'market:everyone:title' => "Everything on The Market",
	'market:everyone' => "All Market Posts",
	'market:read' => "View post",
	'market:add' => "Create New Ad",
	'market:add:title' => "Create a new post on The Market",
	'market:edit' => "Edit Ad",
	'market:imagelimitation' => "Must be JPG, GIF or PNG.",
	'market:text' => "Give a brief description about the item",
	'market:uploadimages' => "Would you like to upload an image for your item?",
	'market:image' => "Item image",
	'market:imagelater' => "",
	'market:strapline' => "Created",
	'item:object:market' => 'Market posts',
	'market:none:found' => 'No market post found',
	'market:pmbuttontext' => "Send Private Message",
	'market:price' => "Price",
	'market:price:help' => "(in %s)",
	'market:text:help' => "(No HTML and max. 250 characters)",
	'market:title:help' => "(1-3 words)",
	'market:tags' => "Tags",
	'market:tags:help' => "(Separate with commas)",
	'market:access:help' => "(Who can see this market post)",
	'market:replies' => "Replies",
	'market:created:gallery' => "Created by %s <br>at %s",
	'market:created:listing' => "Created by %s at %s",
	'market:showbig' => "Show larger picture",
	'market:type' => "Type",
	'market:charleft' => "characters left",
	'market:accept:terms' => "I have read and accepted the %s of use.",
	'market:terms' => "terms",
	'market:terms:title' => "Terms of use",
	'market:terms' => "<li class='elgg-divide-bottom'>The Market is for buying or selling used itemts among members.</li>
			<li class='elgg-divide-bottom'>No more than %s Market posts are allowed pr. user at the same time.</li>
			<li class='elgg-divide-bottom'>Only one Market post is allowed pr. item.</li>

			<li class='elgg-divide-bottom'>A Market post may only contain one item, unless it's part of a matching set.</li>
			<li class='elgg-divide-bottom'>The Market is for used/home made items only.</li>
			<li class='elgg-divide-bottom'>The Market post must be deleted when it's no longer relevant.</li>
			<li class='elgg-divide-bottom'>Commercial advertising is limited to those who have signed a promotional agreement with us.</li>
			<li class='elgg-divide-bottom'>We reserve the right to delete any Market posts violating our terms of use.</li>
			<li class='elgg-divide-bottom'>Terms are subject to change over time.</li>
			",

	// market widget
	'market:widget' => "My Market",
	'market:widget:description' => "Showcase your posts on The Market",
	'market:widget:viewall' => "View all my posts on The Market",
	'market:num_display' => "Number of posts to display",
	'market:icon_size' => "Icon size",
	'market:small' => "small",
	'market:tiny' => "tiny",
		
	// market river
	'river:create:object:market' => '%s posted a new ad in the Market %s',
	'river:update:object:market' => '%s updated the ad %s in the Market',
	'river:comment:object:market' => '%s commented on the Market ad %s',

	// Status messages
	'market:posted' => "Your Market post was successfully posted.",
	'market:deleted' => "Your Market post was successfully deleted.",
	'market:uploaded' => "Your image was succesfully added.",

	// Error messages	
	'market:save:failure' => "Your Market post could not be saved. Please try again.",
	'market:blank' => "Sorry; you need to fill in both the title and body before you can make a post.",
	'market:tobig' => "Sorry; your file is bigger then 1MB, please upload a smaller file.",
	'market:notjpg' => "Please make sure the picture inculed is a .jpg, .png or .gif file.",
	'market:notuploaded' => "Sorry; your file doesn't apear to be uploaded.",
	'market:notfound' => "Sorry; we could not find the specified Market post.",
	'market:notdeleted' => "Sorry; we could not delete this Market post.",
	'market:tomany' => "Error: Too many Market posts",
	'market:tomany:text' => "You have reached the maximum number of Market posts pr. user. Please delete some first!",
	'market:accept:terms:error' => "You must accept the terms of use!",

	// Settings
	'market:settings:status' => "Status",
	'market:settings:desc' => "Description",
	'market:max:posts' => "Max. number of market posts pr. user",
	'market:unlimited' => "Unlimited",
	'market:currency' => "Currency ($, €, DKK or something)",
	'market:allowhtml' => "Allow HTML in market posts",
	'market:numchars' => "Max. number of characters in market post (only valid without HTML)",
	'market:pmbutton' => "Enable private message button",
	'market:adminonly' => "Only admin can create market posts",
	'market:comments' => "Allow comments",
	'market:custom' => "Custom field",

	// market categories
	'market:categories' => 'Market categories',
	'market:categories:choose' => 'Choose type',
	'market:categories:settings' => 'Market Categories:',	
	'market:categories:explanation' => 'Set some predefined categories for posting to the market.<br>Categories could be "clothes, footwear or buy,sell etc...", seperate each category with commas - remember not to use special characters in categories and put them in your language files as market:<i>categoryname</i>',	
	'market:categories:save:success' => 'Site market categories were successfully saved.',
	'market:categories:settings:categories' => 'Market Categories',
	'market:all' => "All",
	'market:category' => "Category",
	'market:category:title' => "Category: %s",

	// Categories
	'market:buy' => "Buying",
	'market:sell' => "Selling",
	'market:swap' => "Swap",
	'market:free' => "Free",

	// Custom select
	'market:custom:select' => "Item condition",
	'market:custom:text' => "Condition",
	'market:custom:activate' => "Enable Custom Select:",
	'market:custom:settings' => "Custom Select Choices",
	'market:custom:choices' => "Set some predefined choices for the custom select dropdown box.<br>Choices could be \"market:new,market:used...etc\", seperate each choice with commas - remember to put them in your language files",

	// Custom choises
	 'market:na' => "No information",
	 'market:new' => "New",
	 'market:unused' => "Unused",
	 'market:used' => "Used",
	 'market:good' => "Good",
	 'market:fair' => "Fair",
	 'market:poor' => "Poor",
);
					
add_translation("en",$english);

?>
