<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| Elgg Socialcommerce Plugin                                                  |
| Copyright (c) 2009-20010 Cubet Technologies <socialcommerce@cubettech.com>  |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://socialcommerce.elgg.in/license.html            |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS  SOFTWARE   PROGRAM  AND   ASSOCIATED   DOCUMENTATION    THAT  CUBET   |
| TECHNOLOGIES (hereinafter referred as "THE AUTHOR") IS FURNISHING OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

/**
 * Elgg widget - mostly - edit
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
?>
<p>
	<?php echo elgg_echo("stores:num_products"); ?>:
	<select name="params[num_display]">
	    <option value="1" <?php if($vars['entity']->num_display == 1) echo "SELECTED"; ?>>1</option>
	    <option value="2" <?php if($vars['entity']->num_display == 2) echo "SELECTED"; ?>>2</option>
	    <option value="3" <?php if($vars['entity']->num_display == 3) echo "SELECTED"; ?>>3</option>
	    <option value="4" <?php if($vars['entity']->num_display == 4) echo "SELECTED"; ?>>4</option>
	    <option value="5" <?php if($vars['entity']->num_display == 5) echo "SELECTED"; ?>>5</option>
	    <option value="6" <?php if($vars['entity']->num_display == 6) echo "SELECTED"; ?>>6</option>
	    <option value="7" <?php if($vars['entity']->num_display == 7) echo "SELECTED"; ?>>7</option>
	    <option value="8" <?php if($vars['entity']->num_display == 8) echo "SELECTED"; ?>>8</option>
	    <option value="9" <?php if($vars['entity']->num_display == 9) echo "SELECTED"; ?>>9</option>
	    <option value="10" <?php if($vars['entity']->num_display == 10) echo "SELECTED"; ?>>10</option>
	    <option value="15" <?php if($vars['entity']->num_display == 15) echo "SELECTED"; ?>>15</option>
	    <option value="20" <?php if($vars['entity']->num_display == 20) echo "SELECTED"; ?>>20</option>
	</select>
</p>

<p>
    <?php echo elgg_echo("stores:display");?>:
    <select name="params[product_display]">
        <option value="1" <?php if($vars['entity']->product_display == 1) echo "SELECTED"; ?>><?php echo elgg_echo("my:stores"); ?></option>
	    <option value="2" <?php if($vars['entity']->product_display == 2) echo "SELECTED"; ?>><?php echo elgg_echo("friends:stores"); ?></option>
	    <option value="3" <?php if($vars['entity']->product_display == 3) echo "SELECTED"; ?>><?php echo elgg_echo("all:stores"); ?></option>
    </select>
</p>

<p>
    <?php echo elgg_echo("stores:gallery_list"); ?>:
    <select name="params[gallery_list]">
        <option value="1" <?php if($vars['entity']->gallery_list == 1) echo "SELECTED"; ?>><?php echo elgg_echo("stores:list"); ?></option>
	    <option value="2" <?php if($vars['entity']->gallery_list == 2) echo "SELECTED"; ?>><?php echo elgg_echo("stores:gallery"); ?></option>
    </select>
</p>