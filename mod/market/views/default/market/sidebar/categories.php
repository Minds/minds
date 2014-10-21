<?php
/**
 * The filter sidebar
 */
 
$categories = minds\plugin\market\start::getCategories();

if(!$categories)
	return;

?>
<ul class="minds-menu minds-menu-market">
<?php foreach($categories as $category):?>

	<li>
		<a href="<?= elgg_get_site_url() ?>market/category/<?=$category ?>" class="<?= $_SERVER['REQUEST_URI'] == '/market/category/'.$category ? 'active' : '' ?>"><?= elgg_echo($category) ?></a>
	</li>	

<?php endforeach; ?>
</ul>