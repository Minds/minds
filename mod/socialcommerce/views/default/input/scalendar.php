<?php 
	static $calendarjs;
	global $CONFIG;
    if (empty($calendarjs)) {
        echo <<< END
        	<script language="JavaScript" src="{$vars['url']}mod/{$CONFIG->pluginname}/js/jquery.date_input.js"></script>
        	<script type="text/javascript">$($.date_input.initialize);</script>
END;
        $calendarjs = 1;
    }
   	$val = $vars['value'];
?>
<input type="text" name="<?php echo $vars['name']; ?>" class="date_input" id="<?php echo $vars['name']; ?>" value="<?php echo $val; ?>" />