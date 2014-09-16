<?php

$card = new minds\plugin\payments\entities\card($vars['entity']);
//$card_details = $card->getCard();

$number = $card->number;
$month =  $card->expire_month; 
$year = $card->expire_year;
$type = $card->card_type;
?>

<p><?=$number?></p>
<p><?=$type?></p>