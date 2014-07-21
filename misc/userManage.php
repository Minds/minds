<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access(true);

$blog = new ElggBlog(326005950190194688);
$blog->title = 'Opening Bell Portfolio Composition 18-June';
$blog->description = "
Nothing contained on this Website constitutes tax, legal, or investment advice. Neither the information, nor any opinion, contained on this Website constitutes a solicitation or offer by Word Capital LLC (\"Word Capital\") or its affiliates to buy or sell any securities, futures, options or other financial instruments, nor shall any such security be offered or sold to any person in any jurisdiction in which such offer, solicitation, purchase, or sale would be unlawful under the securities laws of such jurisdiction. Decisions based on information contained on this Website are the sole responsibility of the visitor. In exchange for using this Website, the visitor agrees to indemnify and hold Word Capital, its officers, directors, employees, affiliates, agents, licensors and suppliers harmless against any and all claims, losses, liability, costs and expenses (including but not limited to attorneys' fees) arising from your use of this Website, from your violation of these Terms or from any decisions that the visitor makes based on such information. Past performance is not indicative of future results.";
$blog->owner_guid = 306512124983644160;
$blog->time_created = time() - (60*60*24*12);
$blog->save();

exit;

//reset_login_failure_count($john->guid);
$user = new ElggUser('culture');

$user->icontime = time();
//var_dump(force_user_password_reset($user->guid, 'temp123'));
//var_dump($user);
//$user->makeAdmin();
//$user->enable();
$user->save();


