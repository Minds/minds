<?php 
$node = $vars['node'];
?>

<h1>Congratulations on launching <?= $node->domain ?>!</h1> 

<p>If you need a hand getting setup, feel free to reply to this email. </p>


<?php
if($node->allowedDomain() && strpos($node->domain, '.minds.com') == FALSE){
?>

<p>As you have opted for a custom domain name we need you to enter to following 'CName' record into your DNS settings. <b>CNAME:</b> multisite2loadbalancer2-1442974952.us-east-1.elb.amazonaws.com</p>

<?php
}
?>

<p><b>The Minds Team</b></p>
