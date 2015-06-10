<?php
$recipient = $vars['recipient'];
$token = md5($vars['recipient']->getEmail() . $vars['recipient']->guid);
?>
					<!-- FOOTER -->
					<table class="footer" width="600" align="center" style="background:#FFF; border-bottom:1px solid #CCC; border-left:1px solid #CCC; border-right:1px solid #CCC; border-radius: 0 0 3px 3px; padding:16px;">
						<tr >
							<td>
								<p style="color:#888;">To unsubscribe from these emails, <a href="<?php echo elgg_get_site_url();?>notifications/unsubscribe?guid=<?php echo $recipient->guid;?>&token=<?php echo $token;?>"> click here </a></p>
							</td>
						<tr/>
					</table> <!-- End FOOTER -->
				</td>
			</tr>
		</table><!-- END table.hero -->
</html>