<?php

/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

?>
<div class="kaltura_video_rating">
	<div class="left">
	<label><?php echo elgg_echo("kalturavideo:yourrate"); ?></label>
	</div>
	<div class="left">
	<label class="rate"><input type="radio" name="rating" class="input-radio" value="0.00" /> 0 </label>
	<label class="rate"><input type="radio" name="rating" class="input-radio" value="1.00" /> 1 </label>
	<label class="rate"><input type="radio" name="rating" class="input-radio" value="2.00" /> 2 </label>
	<label class="rate"><input type="radio" name="rating" class="input-radio" value="3.00" /> 3 </label>
	<label class="rate"><input type="radio" name="rating" class="input-radio" value="4.00" /> 4 </label>
	<label class="rate"><input type="radio" name="rating" class="input-radio" value="5.00" /> 5 </label>
	</div>
	<div class="left">
	<input type="hidden" name="kaltura_video_guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
	<input type="submit" class="submit_button" name="submit" value="<?php echo elgg_echo("kalturavideo:rate"); ?>" />
	</div>
	<div class="clear"></div>
</div>
