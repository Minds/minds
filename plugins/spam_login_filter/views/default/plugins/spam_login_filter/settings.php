<hr>
<h3><?php echo elgg_echo('spam_login_filter:title_stopforumspam');?></h3>
<?php 
    //Stopforumspam
	echo elgg_echo('spam_login_filter:use_stopforumspam');
?>	
	<select name="params[use_stopforumspam]">
		<option value="yes" <?php if ($vars['entity']->use_stopforumspam == "yes") echo " selected=\"yes\" "; ?>>Yes</option>
		<option value="no" <?php if ($vars['entity']->use_stopforumspam == "no") echo " selected=\"yes\" "; ?>>No</option>
    </select><br><br>
<?php
	echo elgg_echo('spam_login_filter:stopforumspam_api_key');
    echo elgg_view('input/text', array('name' => "params[stopforumspam_api_key]", 'value' => $vars['entity']->stopforumspam_api_key));
	
	echo "<br><br>";
?>
<hr>
<h3><?php echo elgg_echo('spam_login_filter:title_fassim');?></h3>
<?php 
    //Fassim
	echo elgg_echo('spam_login_filter:use_fassim');
?>	
	<select name="params[use_fassim]">
		<option value="yes" <?php if ($vars['entity']->use_fassim == "yes") echo " selected=\"yes\" "; ?>>Yes</option>
		<option value="no" <?php if ($vars['entity']->use_fassim == "no") echo " selected=\"yes\" "; ?>>No</option>
    </select><br>

<?php
	echo elgg_echo('spam_login_filter:fassim_api_key');
    echo elgg_view('input/text', array('name' => "params[fassim_api_key]", 'value' => $vars['entity']->fassim_api_key));
	
	echo "<br><br>";
?>
	
<?php 
    //Check e-mails?
	echo elgg_echo('spam_login_filter:fassim_check_email');
?>	
	<select name="params[fassim_check_email]">
		<option value="1" <?php if ($vars['entity']->fassim_check_email == "1") echo " selected=\"1\" "; ?>>Yes</option>
		<option value="0" <?php if ($vars['entity']->fassim_check_email == "0") echo " selected=\"0\" "; ?>>No</option>
    </select><br>

<?php 
    //Check ips?
	echo elgg_echo('spam_login_filter:fassim_check_ip');
?>	
	<select name="params[fassim_check_ip]">
		<option value="1" <?php if ($vars['entity']->fassim_check_ip == "1") echo " selected=\"1\" "; ?>>Yes</option>
		<option value="0" <?php if ($vars['entity']->fassim_check_ip == "0") echo " selected=\"0\" "; ?>>No</option>
    </select><br>

<?php 
    //Block proxies?
	echo elgg_echo('spam_login_filter:fassim_block_proxies');
?>	
	<select name="params[fassim_block_proxies]">
		<option value="1" <?php if ($vars['entity']->fassim_block_proxies == "1") echo " selected=\"1\" "; ?>>Yes</option>
		<option value="0" <?php if ($vars['entity']->fassim_block_proxies == "0") echo " selected=\"0\" "; ?>>No</option>
    </select><br>

<?php 
    //Block top spamming ISPs?
	echo elgg_echo('spam_login_filter:fassim_block_top_spamming_isps');
?>	
	<select name="params[fassim_block_top_spamming_isps]">
		<option value="1" <?php if ($vars['entity']->fassim_block_top_spamming_isps == "1") echo " selected=\"1\" "; ?>>Yes</option>
		<option value="0" <?php if ($vars['entity']->fassim_block_top_spamming_isps == "0") echo " selected=\"0\" "; ?>>No</option>
    </select><br>

<?php 
    //Block top spamming domains?
	echo elgg_echo('spam_login_filter:fassim_block_top_spamming_domains');
?>	
	<select name="params[fassim_block_top_spamming_domains]">
		<option value="1" <?php if ($vars['entity']->fassim_block_top_spamming_domains == "1") echo " selected=\"1\" "; ?>>Yes</option>
		<option value="0" <?php if ($vars['entity']->fassim_block_top_spamming_domains == "0") echo " selected=\"0\" "; ?>>No</option>
    </select><br><br>
	
<?php
	//Block countries?
	echo elgg_echo('spam_login_filter:fassim_blocked_country_list');
	echo elgg_view('input/text', array('name' => "params[fassim_blocked_country_list]", 'value' => $vars['entity']->fassim_blocked_country_list));

	echo "<br><br>";
?>
<?php
	//Block regions?
	echo elgg_echo('spam_login_filter:fassim_blocked_region_list');
	echo elgg_view('input/text', array('name' => "params[fassim_blocked_region_list]", 'value' => $vars['entity']->fassim_blocked_region_list));

	echo "<br><br>";
?>
<hr>
<h3><?php echo elgg_echo('spam_login_filter:title_domain_blacklist');?></h3>
<?php	
	//Domain blacklist
	echo elgg_echo('spam_login_filter:use_mail_domain_blacklist');
?>
	<select name="params[use_mail_domain_blacklist]">
		<option value="yes" <?php if ($vars['entity']->use_mail_domain_blacklist == "yes") echo " selected=\"yes\" "; ?>>Yes</option>
		<option value="no" <?php if ($vars['entity']->use_mail_domain_blacklist == "no") echo " selected=\"yes\" "; ?>>No</option>
    </select><br>
<?php
	echo elgg_echo('spam_login_filter:blacklisted_mail_domains');
	echo elgg_view('input/longtext', array('name' => "params[blacklisted_mail_domains]", 'value' => $vars['entity']->blacklisted_mail_domains));

	echo "<br><br>";
?>
<hr>
<h3><?php echo elgg_echo('spam_login_filter:title_email_blacklist');?></h3>
<?php	
	//Email blacklist
	echo elgg_echo('spam_login_filter:use_mail_blacklist');
?>
	<select name="params[use_mail_blacklist]">
		<option value="yes" <?php if ($vars['entity']->use_mail_blacklist == "yes") echo " selected=\"yes\" "; ?>>Yes</option>
		<option value="no" <?php if ($vars['entity']->use_mail_blacklist == "no") echo " selected=\"yes\" "; ?>>No</option>
    </select><br>
<?php
	echo elgg_echo('spam_login_filter:blacklisted_mails');
	echo elgg_view('input/longtext', array('name' => "params[blacklisted_mails]", 'value' => $vars['entity']->blacklisted_mails));

	echo "<br><br>";
?>
<hr>
<h3><?php echo elgg_echo('spam_login_filter:title_plugin_notifications');?></h3>
<?php
	//Notify by mail
	echo elgg_echo('spam_login_filter:notify_by_mail');
?>
	<select name="params[notify_by_mail]">
		<option value="yes" <?php if ($vars['entity']->notify_by_mail == "yes") echo " selected=\"yes\" "; ?>>Yes</option>
		<option value="no" <?php if ($vars['entity']->notify_by_mail == "no") echo " selected=\"yes\" "; ?>>No</option>
    </select><br>

<?php
	echo elgg_echo('spam_login_filter:notify_mail_address');
    echo elgg_view('input/text', array('name' => "params[notify_mail_address]", 'value' => $vars['entity']->notify_mail_address));
	
	echo "<br><br>";
?>
<hr>
<h3><?php echo elgg_echo('spam_login_filter:title_ip_blacklist');?></h3>
<?php
	echo elgg_echo('spam_login_filter:use_ip_blacklist_cache_description');
	echo ('<br>');
	echo elgg_echo('spam_login_filter:use_ip_blacklist_cache');
?>
	<select name="params[use_ip_blacklist_cache]">
		<option value="yes" <?php if ($vars['entity']->use_ip_blacklist_cache == "yes") echo " selected=\"yes\" "; ?>>Yes</option>
		<option value="no" <?php if ($vars['entity']->use_ip_blacklist_cache == "no") echo " selected=\"yes\" "; ?>>No</option>
    </select><br>