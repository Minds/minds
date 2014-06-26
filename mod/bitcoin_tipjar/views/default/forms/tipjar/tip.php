<div class="tipjar">
    
    <input type="hidden" name="to_user" value="<?= $vars['to_user']; ?>" />
    <input type="hidden" name="currency" value="USD" />
    <select name="value">
	<option value="1" selected>1 USD</option>
	<option value="2">2 USD</option>
	<option value="5">5 USD</option>
    </select>
    
    <input type="submit" value="Tip!" />
</div>