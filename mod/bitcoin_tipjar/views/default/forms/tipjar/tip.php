<div class="tipjar">

    <input type="hidden" name="to_user" value="<?= $vars['to_user']->guid; ?>" />

    <p><label>Enter your tip<br />
	    <input type="text" name="value" placeholder="Tip amount" /></label></p>

    <p><label>Currency<br />
	    <select name="currency">
		<option value="USD" selected>$</option>
		<option value="BTC">Bitcoin</option> 
	    </select>
	</label></p>

    <p><label>Enter your password to unlock your wallet<br />
	    <input type="password" name="wallet_password" placeholder="Your wallet password" /></label></p>

    <input type="submit" value="Tip!" />
</div>