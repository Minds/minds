<?php
/**
    * Elgg Membership plugin
    * Membership tab menu page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    if(!$num_groups) {
        $num_groups = 0;
    }
    global $CONFIG;

    $filter = $vars['filter'];

    global $CONFIG;
    $path=$CONFIG->wwwroot."membership/report";
    $path1=$CONFIG->wwwroot."membership/premium";
    //$url = $vars['url'] . "pg/profile/".$username;

?>
<div id="elgg_horizontal_tabbed_nav">
    <ul>
        <?php
        foreach($CONFIG->membership_usertype as $value => $option) {
            $entity=get_entity($value);
            $type=$entity->title;
            if(!$entity) {
                $type='Free';
            }
        ?>
        <li <?php if($filter == $value)  echo "class='selected'"; ?> id="halo1">
            <a href="<?php echo $path.'/'.$value;?>" ><?php echo $type;  ?></a>
        </li>
        <?php
        }
        ?>
    </ul>
</div>

