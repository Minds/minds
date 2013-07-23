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

// Make sure we're logged in (send us to the front page if not)
if (!isloggedin()) forward();

// Get input data
$guid = (int) get_input('marketpost');
$title = get_input('markettitle');
$price = get_input('marketprice');
$custom = get_input('marketcustom');
$category = get_input('marketcategory');
$body = get_input('marketbody');
$access = get_input('access_id');
$tags = get_input('markettags');
		
// Make sure we actually have permission to edit
$market = get_entity($guid);
if ($market->getSubtype() == "market" && $market->canEdit()) {
	
	// Cache to the session
	$_SESSION['markettitle'] = $title;
	$_SESSION['marketbody'] = $body;
	$_SESSION['marketprice'] = $price;
	$_SESSION['marketcustom'] = $custom;
	$_SESSION['markettags'] = $tags;
	$_SESSION['marketcategory'] = $category;
	// Convert string of tags into a preformatted array
	$tagarray = string_to_tag_array($tags);
			
	// Make sure the title / description aren't blank
	if (empty($title) || empty($body)) {
		register_error(elgg_echo("market:blank"));
		forward("mod/market/addAngular.php");
				
	// Otherwise, save the market post 
	} else {
				
		// Get owning user
		$owner = get_entity($market->getOwner());
		// For now, set its access to public (we'll add an access dropdown shortly)
		$market->access_id = $access;
		// Set its title and description appropriately
		$market->title = $title;
		$market->description = $body;
		$market->price = $price;
		$market->custom = $custom;
		$market->marketcategory = $category;
		// Before we can set metadata, we need to save the market post
		if (!$market->save()) {
			register_error(elgg_echo("market:error"));
			forward("mod/market/edit.php?marketpost=" . $guid);
		}
		// Now let's add tags. We can pass an array directly to the object property! Easy.
		$market->clearMetadata('tags');
		if (is_array($tagarray)) {
			$market->tags = $tagarray;
		}

		// Now see if we have a file icon
		if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {

			$prefix = "market/".$market->guid;
			$filehandler = new ElggFile();
			$filehandler->owner_guid = $market->owner_guid;
			$filehandler->setFilename($prefix . ".jpg");
			$filehandler->open("write");
			$filehandler->write(get_uploaded_file('upload'));
			$filehandler->close();
		
			$thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
			$thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
			$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),153,153, true);
			$thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
			if ($thumbtiny) {
			
				$thumb = new ElggFile();
				$thumb->owner_guid = $market->owner_guid;
				$thumb->setMimeType('image/jpeg');
				$thumb->setFilename($prefix."tiny.jpg");
				$thumb->open("write");
				$thumb->write($thumbtiny);
				$thumb->close();
				$thumb->setFilename($prefix."small.jpg");
				$thumb->open("write");
				$thumb->write($thumbsmall);
				$thumb->close();
				$thumb->setFilename($prefix."medium.jpg");
				$thumb->open("write");
				$thumb->write($thumbmedium);
				$thumb->close();
				$thumb->setFilename($prefix."large.jpg");
				$thumb->open("write");
				$thumb->write($thumblarge);
				$thumb->close();
			}
		}				

		// Success message
		system_message(elgg_echo("market:posted"));

		// regenerate cache
		elgg_regenerate_simplecache();
		// add to river - doesn't work well with the new river!
		//add_to_river('river/object/market/update','update',$_SESSION['user']->guid,$market->guid);
		// Remove the market post cache
		unset($_SESSION['markettitle']); unset($_SESSION['marketbody']); unset($_SESSION['marketprice']); unset($_SESSION['markettags']);
		remove_metadata($_SESSION['user']->guid,'markettitle');
		remove_metadata($_SESSION['user']->guid,'marketbody');
		remove_metadata($_SESSION['user']->guid,'markettags');
		remove_metadata($_SESSION['user']->guid,'marketprice');
		remove_metadata($_SESSION['user']->guid,'markettype');

		// Forward to the main market page
		forward(elgg_get_site_url() . "market");
	}
		
}

