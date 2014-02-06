<?php
/**
 * Minds Search CC Article View
 */

$article = $vars['source']; 
$full_view = $vars['full_view'];

$title = $article['title'];
$url = elgg_get_site_url().'search/result/'.$article['id'];
$provider = $article['source'];
$description = strip_tags($article['description']);


if($provider == 'minds'){
	$entity = get_entity($article['guid'], 'object');
	$iconURL = minds_fetch_image($entity->description, $entity->owner_guid);
	$icon = "<img src='".$iconURL."'/>";
}

if(!$full_view){
	
?>
<a href='<?php echo $url;?>'>
		<?php echo $icon;?>
		<h3><?php echo $title;?></h3>
		<p><?php echo $description;?> <br/>
		<b><?php echo $source;?></b><br/>
	</p>
</a>
<?php 
}else {
	minds_set_metatags('og:title', $article['title']);
	minds_set_metatags('og:type', 'mindscom:photo');
	minds_set_metatags('og:url', $url);
	minds_set_metatags('og:image', $imageURL);
	minds_set_metatags('mindscom:photo', $imageURL);
	minds_set_metatags('og:description', 'License: ' . elgg_echo('minds:license:'.$article['license']));
	
	if($source=='wikipedia'){
		elgg_load_css('wiki');
		$url = 'http://en.wikipedia.org/w/api.php?action=parse&page=' . urlencode($article['title']) .'&format=json&rvprop=content';
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, "mindsDOTCOM"); // required by wikipedia.org server; use YOUR user agent with YOUR contact information. (otherwise your IP might get blocked)
		$c = curl_exec($ch);
			
		$json = json_decode($c);
			 
		$content = $json->{'parse'}->{'text'}->{'*'};
		
		//include ads
		//$content = preg_replace('#<table id="toc" class="toc">#', '<div style="float:right;padding-right:25px;">'.elgg_view('minds/ads', array('type'=>'large-block')) . '</div><table id="toc" class="toc">', $content);
		$content = preg_replace('#<table class="infobox" (.*)>#',  '<div style="float:right;padding-right:25px;">' . elgg_view('minds/ads', array('type'=>'small-banner')) . '</div><table class="infobox" $1>', $content);
		//remove edit tags
		$content = preg_replace('#<span class="editsection">(.*?)</span>#', '', $content);
		//replace a tags with wkipedia urls
		$content = preg_replace('#<a(.*?)href="/wiki/(.*?)"(.*?)>#', '<a$1href="http://en.wikipedia.org/wiki/$2">', $content);

		//@todo Make sure all links go to wikipedia
		
		echo "<div style='clear:both;margin-top:35px;'>";
		echo $content;
		echo elgg_view('minds/ads', array('type'=>'content-foot'));
		echo "</div>";
	}elseif($source=='minds'){
		forward($entity->getURL());
	}
}?>
