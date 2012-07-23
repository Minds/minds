<?php

	$configured = $vars['configured'];

	$manifest= load_plugin_manifest("kaltura_video");
?>

<p><b>Version: </b> <?php echo $manifest['version']; ?><br />
<a href="<?php echo $CONFIG->wwwroot."mod/kaltura_video/INSTALL.txt"; ?>">INSTALL file</a>
 &bull;
 <a href="<?php echo $CONFIG->wwwroot."mod/kaltura_video/README.txt"; ?>">README file</a>
 &bull;
 <a href="<?php echo $CONFIG->wwwroot."mod/kaltura_video/CHANGELOG.txt"; ?>">CHANGELOG file</a>
 &bull;
 <a href="<?php echo $CONFIG->wwwroot."mod/kaltura_video/license.txt"; ?>">License file</a>
</p>

<h3 class="settings"><?php echo elgg_echo('kalturavideo:admin:suport'); ?></h3>

<p>Links to get suport and collaboration (translations are welcome!):</p>
<ul>
	<li><a href="http://community.elgg.org/pg/groups/282/kaltura-interactive-video-plugin/">Kaltura Collaborative Group on Elgg.org</a></li>
	<li><a href="http://community.elgg.org/pg/plugins/ivan">Download the last version of the plugin in Elgg.org</a></li>
	<li><a href="http://www.kaltura.org/project/community_edition_video_platform">Suport for Kaltura CE Server</a></li>
</ul>

<h3 class="settings"><?php echo elgg_echo('kalturavideo:admin:credits'); ?></h3>

<p><b>Kaltura Collaborative Video Plugin for Elgg</b></p>

<p><b>@author</b> Ivan Vergés &lt;<a href="mailto:ivan@microstudi.net">ivan@microstudi.net</a>&gt;<br />
&nbsp; Follow the last news about Kaltura Elgg Plugin on my personal Twitter: <a href="http://twitter.com/microstudi">http://twitter.com/microstudi</a><br />
&nbsp; Visit my <a href="http://community.elgg.org/pg/profile/ivan">personal profile in Elgg.org</a>
</p>

<p><b>@license</b> <a href="http://www.gnu.org/licenses/gpl.html">GNU Public License version 3</a><br />
<b>@copyright</b> Ivan Vergés 2010<br />
<b>@link</b> <a href="http://microstudi.net/elgg/">http://microstudi.net/elgg/</a></p>
