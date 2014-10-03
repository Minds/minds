<?php
$section = $vars['section'];
?>
<section class="cms-section" data-guid="<?= $section->guid ?>">
	<div class="cms-section-bg"  <?php if($section->background): ?> style="background-image:url(<?=elgg_get_site_url() . "s/$section->guid/bg/".$section->last_updated ?>)" <?php endif; ?>></div>
	<div class="container">
		
		<?php if(elgg_is_admin_logged_in()):
			elgg_load_js('minicolors');
			elgg_load_css('minicolors');
		?>
		<div class="cms-section-admin">
			<a class="cms-icon icon-delete">
				Remove
			</a>
			<a class="cms-icon icon-move">
				Move
				<input type="hidden" name="position" value="<?= $section->position ?>"/>
			</a>
			<a class="cms-icon icon-bg">
				<span>Background</span>
				<input type="file" name="bg"/>
			</a>
			<a class="cms-icon icon-colour">
				<input type="text" name="colour" class="text-color" value="<?= $section->color ?>"/>
			</a>
		</div>
		
		<div class="left">
			<div class="cell">
				<h2><input type="text" placeholder="Header 2." class="h2" value="<?= $section->leftH2 ?>" style="color:<?=$section->color?>"/></h2>
				<p><textarea placeholder="Paragraph with some text here." class="p" style="color:<?=$section->color?>"><?= $section->leftP ?></textarea></p>
			</div>
		</div>
		<div class="right">
			<div class="cell">
				<h2><input type="text" placeholder="Header 2." class="h2" value="<?= $section->rightH2 ?>" style="color:<?=$section->color?>"/></h2>
				<p><textarea placeholder="Paragraph with some text here." class="p" style="color:<?=$section->color?>"><?= $section->rightP ?></textarea></p>
			</div>
		</div>
		<?php else: ?>
		
			<div class="left">
				<div class="cell">
					<h2 style="color:<?=$section->color?>"><?= $section->leftH2 ?></h2>
					<p style="color:<?=$section->color?>"><?= $section->leftP ?></p>
				</div>
			</div>
			
			<div class="right">
				<div class="cell">
					<h2 style="color:<?=$section->color?>"><?= $section->rightH2 ?></h2>
					<p style="color:<?=$section->color?>"> <?= $section->rightP ?></p>
				</div>
			</div>
		
		<?php endif; ?>
	</div>
</section>
