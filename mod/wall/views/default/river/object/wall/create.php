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

	 elgg_load_js('lightbox');
        elgg_load_css('lightbox');
	?>
<script>
$(document).ready(function(){ 
	$('.attachment-lightbox').fancybox({
		'type': 'image',
		'width': '90%'
	}); 
});
</script>
<?php

	$src = elgg_get_site_url() . "photos/thumbnail/$item->attachment_guid/large";

	$attachment = elgg_view('output/img', array( 
		'src' => $src,
		'class' => 'river-img-attachment'
	)); //we are just going to assume they are images... change soon
	 
	$attachment =  elgg_view('output/url', array(
		'text'=>$attachment,
		'href'=>$src,
		'class'=>'attachment-lightbox'
	));


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
