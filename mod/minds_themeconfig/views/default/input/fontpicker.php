
<select data-sample="<?php echo $vars['data-sample']; ?>" id="<?php echo $vars['id']; ?>" name="<?php echo $vars['name']; ?>" class="<?php echo $vars['class']; ?>">
    <?php 
    foreach (array(
	'Theme default' => '',
	"'Helvetica Neue',arial,Sans-Serif" => "'Helvetica Neue',arial,Sans-Serif"
    ) as $label => $value) {
	?>
    <option value="<?php echo $value; ?>" <?php if ($vars['value'] == $value) echo 'selected'; ?>><?php echo $label; ?></option>
	<?php
    }?>
</select>