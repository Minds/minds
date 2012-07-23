<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
*
* This script is based on the artfolio rate script (thanks to Frederqiue Hermans)
**/

	require_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/includes.php");

	// Make sure we're logged in (send us to the front page if not)
	if (!elgg_is_logged_in()) forward();

	// Get input data
	$guid = (int) get_input('kaltura_video_guid');
	$rate = (int) get_input('rating',-1);

	// Get the post
	if ($entity = get_entity($guid)) {
		$metadata = kaltura_get_metadata($entity);
		if($metadata->kaltura_video_rating_on == 'Off') {
			unset($entity);
		}
	}

	if(!$entity) {
		register_error(elgg_echo("kalturavideo:notrated"));
		forward();
	}

	$owner = $entity->getOwnerGUID();

	if($rate == -1) {
		register_error(elgg_echo("kalturavideo:rateempty"));
		forward($entity->getURL());
	}
	// Get old rating
	list($numvotes,$image,$oldrate) = kaltura_get_rating($entity);

	// Calculate new rating
	$oldrate = ($oldrate * $numvotes);
	$newrate = ($oldrate + $rate);
	$newcount = ($numvotes + 1.00);
	$newrate = ($newrate / $newcount);

	//do no rate if is already rated
	if(!kaltura_is_rated_by_user($guid,$_SESSION['user'],$numvotes)) {
		// Delete old ratings
		$kaltura_video_ratings = $entity->getAnnotations('kaltura_video_rating');
		foreach ($kaltura_video_ratings as $kaltura_video_rating){
			$rating_id = $kaltura_video_rating['id'];
			$ratingobject = get_annotation($rating_id);
			$ratingobject->delete();
		}

		$kaltura_video_numvotes = $entity->getAnnotations('kaltura_video_numvotes');
		foreach ($kaltura_video_numvotes as $kaltura_video_numvote){
			$numvotes_id = $kaltura_video_numvote['id'];
			$numvotesobject = get_annotation($numvotes_id);
			$numvotesobject->delete();
		}

		// Save new rating
		$entity->annotate('kaltura_video_rating', $newrate, ACCESS_PUBLIC, $owner, "integer");
		$entity->annotate('kaltura_video_numvotes', $newcount, ACCESS_PUBLIC, $owner, "integer");
		// Save this vote to avoid new duplicate votes
		$_SESSION['user']->annotate('kaltura_video_rated', $guid);

		//add to the river
		add_to_river('river/object/kaltura_video/rate','rate',$_SESSION['user']->getGUID(),$entity->getGUID());

		system_message(elgg_echo("kalturavideo:ratesucces"));

	}
	else {
		register_error(elgg_echo("kalturavideo:notrated"));
	}

	forward($entity->getURL());
?>
