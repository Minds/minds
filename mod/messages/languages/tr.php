<?php
/**
* Elgg send a message action page T&#252;rk&#231;e dil dosyas&#305;
* 
* @package ElggMessages
*/

$turkish = array(
	/**
	* Menu items and titles
	*/

	'messages' => "Mesajlar",
	'messages:unreadcount' => "%s okunmam&#305;&#351;",
	'messages:back' => "mesajlara d&#246;n",
	'messages:user' => "%s'in gelen kutusu",
	'messages:posttitle' => "%s'in mesajlar&#305;: %s",
	'messages:inbox' => "Gelen Kutusu",
	'messages:send' => "G&#246;nder",
	'messages:sent' => "G&#246;nderildi",
	'messages:message' => "Mesaj",
	'messages:title' => "Konu",
	'messages:to' => "Kime",
	'messages:from' => "Kimden",
	'messages:fly' => "G&#246;nder",
	'messages:replying' => "Yan&#305;t mesaj&#305;",
	'messages:inbox' => "Gelen Kutusu",
	'messages:sendmessage' => "Mesaj g&#246;nder",
	'messages:compose' => "Mesaj yaz",
	'messages:add' => "Mesaj yaz",
	'messages:sentmessages' => "G&#246;nderilmi&#351; mesajlar",
	'messages:recent' => "En yeni mesajlar",
	'messages:original' => "Orjinal mesaj",
	'messages:yours' => "Mesaj&#305;n&#305;z",
	'messages:answer' => "Yan&#305;tla",
	'messages:toggle' => 'T&#252;m&#252;n&#252; ge&#231;',
	'messages:markread' => 'Okundu olarak i&#351;aretle',
	'messages:recipient' => 'Bir al&#305;c&#305; se&#231;;',
	'messages:to_user' => 'Kime: %s',

	'messages:new' => 'Yeni mesaj',

	'notification:method:site' => 'Site',

	'messages:error' => 'Mesaj kaydedilirken hata meydana geldi. L&#252;tfen tekrar deneyin.',

	'item:object:messages' => 'Mesajlar',

	/**
	* Status messages
	*/

	'messages:posted' => "Mesaj&#305;n&#305;z ba&#351;ar&#305;yla g&#246;nderildi.",
	'messages:success:delete:single' => 'Mesaj silindi',
	'messages:success:delete' => 'Mesajlar silindi',
	'messages:success:read' => 'Okundu olarak i&#351;aretlenen mesajlar',
	'messages:error:messages_not_selected' => 'Hi&#231; mesaj se&#231;ilmedi',
	'messages:error:delete:single' => 'Mesaj silinemiyor',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'Yeni bir mesaj&#305;n&#305;z var!',
	'messages:email:body' => "%s yeni bir mesaj g&#246;nderdi:


	%s


	Mesajlar&#305;n&#305;z&#305; incelemek i&#231;in buraya t&#305;klay&#305;n:

	%s

	%s'e bir mesaj k&#246;ndermek i&#231;in buraya t&#305;klay&#305;n:

	%s

	Bu e-postay&#305; yan&#305;tlamay&#305;n&#305;z.",

	/**
	* Error messages
	*/

	'messages:blank' => "&#220;zg&#252;n&#252;z; kaydetmeden &#246;nce mesaj i&#231;eri&#287;ine mutlaka bir &#351;eyler yazmal&#305;s&#305;n&#305;z.",
	'messages:notfound' => "&#220;zg&#252;n&#252;z; belirtilen mesaj bulunamad&#305;.",
	'messages:notdeleted' => "&#220;zg&#252;n&#252;z; bu mesaj silinemedi.",
	'messages:nopermission' => "Bu mesaj&#305; de&#287;i&#351;tirmek i&#231;in yetkiniz yok.",
	'messages:nomessages' => "Hi&#231; mesaj yok.",
	'messages:user:nonexist' => "Kullan&#305;c&#305; veritaban&#305;nda al&#305;c&#305; bulunamad&#305;.",
	'messages:user:blank' => "G&#246;nderecek ki&#351;iyi se&#231;mediniz.",

	'messages:deleted_sender' => 'Kullan&#305;c&#305;y&#305; sil',

);
		
add_translation("tr", $turkish);