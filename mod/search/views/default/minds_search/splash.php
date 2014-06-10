<form class="minds-search minds-search-index-page" action="<?php echo elgg_get_site_url(); ?>search" method="get">
	<fieldset>
		<!--<input type="text" class="search-input" size="21" name="q" value="<?php echo elgg_echo('search'); ?>" onblur="if (this.value=='') { this.value='<?php echo elgg_echo('search'); ?>' }" onfocus="if (this.value=='<?php echo elgg_echo('search'); ?>') { this.value='' };" />-->
		<?php echo elgg_view('input/text', array('name'=> 'q','value'=>get_input('q'), 'class'=>'search-input', 'placeholder'=> elgg_echo('search'), 'match_on' => 'users')); ?>
		<input type="hidden" name="services[]" value="all" />
		<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search-submit-button" />
	</fieldset>
</form>