
<select data-sample="<?php echo $vars['data-sample']; ?>" id="<?php echo $vars['id']; ?>" name="<?php echo $vars['name']; ?>" class="<?php echo $vars['class']; ?>">
    <?php 
    foreach (array(
	'Theme default' => '',
	'Ubuntu' => 'Ubuntu',
	
	'Georgia, serif' => 'Georgia, serif',
	'"Palatino Linotype", "Book Antiqua", Palatino, serif' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
	
	'Arial, Helvetica, sans-serif' => 'Arial, Helvetica, sans-serif',
	'"Arial Black", Gadget, sans-serif' => '"Arial Black", Gadget, sans-serif',
	'"Comic Sans MS", cursive, sans-serif' => '"Comic Sans MS", cursive, sans-serif',
	'Impact, Charcoal, sans-serif' => 'Impact, Charcoal, sans-serif',
	'"Lucida Sans Unicode", "Lucida Grande", sans-serif' => '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
	'Tahoma, Geneva, sans-serif' => 'Tahoma, Geneva, sans-serif',
	'"Trebuchet MS", Helvetica, sans-serif' => '"Trebuchet MS", Helvetica, sans-serif',
	'Verdana, Geneva, sans-serif' => 'Verdana, Geneva, sans-serif',
	
	'"Courier New", Courier, monospace' => '"Courier New", Courier, monospace',
	'"Lucida Console", Monaco, monospace' => '"Lucida Console", Monaco, monospace',
	
	'cursive' => 'cursive',
	'fantasy' => 'fantasy'
    ) as $label => $value) {
	?>
    <option value='<?php echo $value; ?>' <?php if ($vars['value'] == $value) echo 'selected'; ?>><?php echo $label; ?></option>
	<?php
    }?>
</select>