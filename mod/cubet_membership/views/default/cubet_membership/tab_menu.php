<?php
   /**
    * Elgg Membership plugin
    * Membership Tab Filter page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
	 
    if(!$num_groups) {
        $num_groups = 0;
    }

    $filter = $vars['filter'];


    if(!$filter) {
        $filter="general";
    }

    //$username = get_input('username');
    global $CONFIG;
    $path=$CONFIG->wwwroot."membership/settings";
    $path1=$CONFIG->wwwroot."membership/premium";
    //$url = $vars['url'] . "pg/profile/".$username;

?>
<div id="elgg_horizontal_tabbed_nav">
    <ul>
        <li <?php if($filter == "general") echo "class='selected'"; ?> id="halo1">
            <a href="<?php echo $path;?>" ><?php echo elgg_echo('General'); ?></a>
        </li>
        <li <?php if($filter == "premium") echo "class='selected'";  ?> id="halo2">
            <a href="<?php echo $path1; ?>" "><?php echo elgg_echo('Premium Settings'); ?></a>
        </li>

    </ul>
</div>

