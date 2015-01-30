<?php
$section = $vars['section'];
$img_src = elgg_get_site_url() . "s/$section->guid/bg/".$section->last_updated;
?>
<section class="cms-section <?= $section->size == 'fat' ? 'cms-section-fat' : 'cms-section-thin'?>" data-guid="<?= $section->guid ?>">
	<div class="cms-section-bg"  <?php if($section->background): ?> <?php endif; ?>>
		<img src="<?=$img_src?>" style="top:<?= $section->top_offset ?>px; <?php if(!$section->background): ?> display:none; <?php endif; ?>"/>
		<div class="cms-overlay" style="background:<?= $section->overlay_colour ?: "transparent"?>; opacity:<?= $section->overlay_opacity ?: 0.5?>"></div>
		<input type="hidden" name="top_offset" value="<?= $section->top_offset ?>"/>
	</div>
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
			<a class="cms-icon icon-overlay"/>
                <span>Overlay</span>
                <input type="text" name="overlay_colour" class="overlay" data-opacity="<?= $section->overlay_opacity ?: 0.5 ?>" value="<?= $section->overlay_colour ?>"/>
                <input type="hidden" name="overlay_opacity" value="<?= $section->overlay_opacity ?>"/>
			</a>
			<a class="cms-icon icon-colour">
			     <span>Text colour</span>
				<input type="text" name="colour" class="text-color" value="<?= $section->color ?>"/>
			</a>
			<a class="cms-icon icon-href">
			     <span>URL</span>
				<input type="text" name="href" class="" value="<?= $section->href ?>" placeholder="url"/>
			</a>
			<a class="cms-icon icon-toggle">
                 <span>Toggle size</span>
                 <input type="hidden" name="size" value="<?= $section->size ?>"/>
            </a>
		</div>
		
		<?php if($section->version == 2 && false): ?>
			<?= elgg_view('input/longtext', array('name'=>'content', 'value'=>$section->content)); ?>
		<?php else: ?>
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
		
		<?php endif; ?>
		<?php else: ?>
		
		<?php if($section->version == 2 && false): ?>
			<div class="section-gui-ouput">
				<?= $section->content; ?>
			</div>
		<?php else: ?>
			<a href="<?= $section->href ?>" target="_blank">		
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
			</a>
		<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
