<div class="tipjar">
    
    <input type="hidden" name="to_user" value="<?= $vars['to_user']->guid; ?>" />
    <input type="text" name="value" />
    <select name="currency">
	<option value="USD" selected>$</option>
	<option value="Satoshi">Satoshi</option> 
    </select>
    
    <input type="submit" value="Tip!" />
</div>