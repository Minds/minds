<?php 
$types = array(0 => 'all', 1 => 'photo', 2 => 'video', 3 => 'sound', 4 => 'article', 5 => 'user', 6 => 'group');
$licenses = array('attribution-cc', 'attribution-sharealike-cc', 'attribution-noderivs-cc', 'attribution-noncommerical-cc', 'attribution-noncommercial-sharealike-cc', 'attribution-noncommercial-noderivs-cc', 'publicdomaincco');

$query = get_input('q');
$t = get_input('type', 'all');
$l = get_input('license', 'all');
$path = elgg_get_site_url() . 'search/?q=' . $query;

?>
<div class="minds-search minds-search-nav">
	<ul class="minds-search-nav-section minds-search-resource-types">
		<?php 
		foreach($types as $type){
			if($type=='all'){
				$text = elgg_echo('minds_search:type:' . $type).' ';
			} else {
				$text = elgg_echo('minds_search:type:' . $type);
			}
			echo "<a href=\"$path&type=$type&license=$l\"><li>";
			echo $text;
			echo "</li></a>";
			}
		?>
	</ul>
	<ul class="minds-search-nav-section minds-search-licenses">
		<a href="#" class="minds-search-license-menu-item">
			<li><?php echo elgg_echo('minds:license:' . $l);?> &#9660;</li>
		</a>
		<ul class="minds-search-menu-licenses">
			<?php
			echo "<a href=\"$path&type=$t&license=all\"><li>";
				echo 'All licenses';
				echo "</li></a>";
			foreach($licenses as $license){
				if($type=='all'){
					$text = elgg_echo('minds:license:' . $license).' ('.$count.')';
				} else {
					$text = elgg_echo('minds:license:' . $license);
				}
				echo "<a href=\"$path&type=$t&license=$license\"><li>";
				echo $text;
				echo "</li></a>";
				}
			?>
		</ul>
	</ul>
</div>
