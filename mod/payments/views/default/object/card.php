<?php

$card = new minds\plugin\payments\entities\card($vars['entity']);
$card_details = $card->getCard();

$number = $card_details->number;
$month =  $card_details->expire_month; 
$year = $card_details->expire_year;
$type = $card_details->type;
?>

<p><?=$number?></p>
<p><?=$month?>/<?=$year?></p>
<p><?=$type?></p>