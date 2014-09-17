<?php
/**
 * Wall river views
 */
$item = $vars['item'];

if($item->body){
	$excerpt = minds_filter($item->body);
}else{
	$object = $item->getObjectEntity();
	$excerpt = minds_filter($object->message);
}

$to = get_entity($object->to_guid);

$subject = $item->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$owner_link = elgg_view('output/url', array(
	'href' => elgg_instanceof($to, 'group') ? "wall/group/$to->guid" : "wall/$to->username",
	'text' => $to->name,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

if($object->owner_guid == $object->to_guid || $to instanceof ElggGroup || !$to){
	$summary = elgg_echo("river:create:object:wall", array($subject_link));
} else {
	$summary = elgg_echo("river:create:object:wall", array($subject_link, $owner_link));
}

if($item->attachment_guid){

	elgg_load_js('elgg.wall');
	elgg_load_js('popup');

	$attachment = new PostAttachment($item->attachment_guid);
	$src = elgg_get_site_url() . "wall/attachment/$item->attachment_guid";
	switch($attachment->subtype){
		case 'image':
			if($attachment->time_created < 1407524342){
				$image = $attachment;

			} else {
				$image = new minds\plugin\archive\entities\image($attachment);
			}

			$attachment = elgg_view('output/img', array( 
				'src' => $image->getIconURL('large'),
				'class' => 'river-img-attachment',
				
			)); //we are just going to assume they are images... change soon
			$attachment =  elgg_view('output/url', array(
				'text'=>$attachment,
				'href' => $image->getUrl(),
				'class'=>' lightbox-image',
				'id' => $attachment->guid,
				'data-album-guid'=>$attachment->container_guid
			)); 
			
			break;
		
		default:
			$attachment = '<div class="river-attachment"><a href="'.$src.'">Download ' . $attachment->originalfilename . '</a><p>'.round($attachment->size / (1024 * 1024)).' MB</p></div>';
			$attachment =  elgg_view('output/url', array(
				'text'=>$attachment,
				'href' => "$src/master",
				'class'=>' lightbox-image',
				'id' => $attachment->guid,
				'data-album-guid'=>$attachment->container_guid
			));
		}

} elseif($item->meta_title){
	$attachment = elgg_view('output/preview', array(
		'title' => $item->meta_title,
		'description' => $item->meta_description,
		'icon' => $item->meta_icon,
		'url' => $item->meta_url
	));
}

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => '<p>' . $excerpt . '</p>',
	'summary' => $summary,
	'attachments' => $attachment
));
