
<select data-sample="<?php echo $vars['data-sample']; ?>" id="<?php echo $vars['id']; ?>" class="<?php echo $vars['class']; ?>">
    <?php 
    foreach (array(
	'default' => '',
	"'Helvetica Neue',arial,Sans-Serif" => "'Helvetica Neue',arial,Sans-Serif"
    ) as $label => $value) {
	?>
    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
	<?php
    }?>
</select>