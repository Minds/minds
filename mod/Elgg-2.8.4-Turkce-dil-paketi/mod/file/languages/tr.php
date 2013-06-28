<?php
/**
 * Elgg file eklentisi dil paketi
 *
 * @package ElggFile
 */

$turkish = array(

	/**
	 * Menu items and titles
	 */
	'file' => "Dosyalar",
	'file:user' => "%s'in dosyalar&#305;",
	'file:friends' => "Arkada&#351;lar&#305;n dosyalar&#305;",
	'file:all' => "T&#252;m site dosyalar&#305;",
	'file:edit' => "Dosya d&#252;zenle",
	'file:more' => "Daha fazla dosya",
	'file:list' => "liste g&#246;r&#252;n&#252;m&#252;",
	'file:group' => "Grup dosyalar&#305;",
	'file:gallery' => "galeri g&#246;r&#252;n&#252;m&#252;",
	'file:gallery_list' => "Galeri ya da liste g&#246;r&#252;n&#252;m&#252;",
	'file:num_files' => "G&#246;r&#252;nt&#252;lenecek dosya say&#305;s&#305;",
	'file:user:gallery'=>'%s galerisini g&#246;ster',
	'file:upload' => "Dosya y&#252;kle",
	'file:replace' => 'Dosya i&#231;eri&#287;ini de&#287;i&#351;tir (dosyay&#305; de&#287;i&#351;tirmek istemiyorsan&#305;z bo&#351; b&#305;rak&#305;n)',
	'file:list:title' => "%s'in %s %s",
	'file:title:friends' => "Arkada&#351;lar'",

	'file:add' => 'Dosya y&#252;kle',

	'file:file' => "Dosya",
	'file:title' => "Ba&#351;l&#305;k",
	'file:desc' => "A&#231;&#305;klama",
	'file:tags' => "Etiketler",

	'file:list:list' => 'Liste g&#246;r&#252;n&#252;m&#252;ne ge&#231;',
	'file:list:gallery' => 'Galeri g&#246;r&#252;n&#252;m&#252;ne ge&#231;',

	'file:types' => "Y&#252;klenen dosya t&#252;rleri",

	'file:type:' => 'Dosyalar',
	'file:type:all' => "T&#252;m Dosyalar",
	'file:type:video' => "Videolar",
	'file:type:document' => "Belgeler",
	'file:type:audio' => "Ses",
	'file:type:image' => "Resimler",
	'file:type:general' => "Genel",

	'file:user:type:video' => "%s'in videolar&#305;",
	'file:user:type:document' => "%s'in belgeleri",
	'file:user:type:audio' => "%s'in ses dosyalar&#305;",
	'file:user:type:image' => "%s'in resimleri",
	'file:user:type:general' => "%s'in genel dosyalar&#305;",

	'file:friends:type:video' => "Arkada&#351;lar&#305;n&#305;n dosyalar&#305;",
	'file:friends:type:document' => "Arkada&#351;lar&#305;n&#305;n belgeleri",
	'file:friends:type:audio' => "Arkada&#351;lar&#305;n&#305;n ses dosyalar&#305;",
	'file:friends:type:image' => "Arkada&#351;lar&#305;n&#305;n resimleri",
	'file:friends:type:general' => "Arkada&#351;lar&#305;n&#305;n genel dosyalar&#305;",

	'file:widget' => "Dosya arac&#305;",
	'file:widget:description' => "Son dosyalar&#305;n&#305; tan&#305;t",

	'groups:enablefiles' => 'Grup dosyalar&#305;na izin ver',

	'file:download' => "Bunu indir",

	'file:delete:confirm' => "Bu dosyay&#305; silmek istedi&#287;inizden emin misiniz?",

	'file:tagcloud' => "Etiket bulutu",

	'file:display:number' => "G&#246;r&#252;nt&#252;lenecek dosya say&#305;s&#305;",

	'river:create:object:file' => '%s %s dosyas&#305;n&#305; y&#252;kledi',
	'river:comment:object:file' => '%s %s dosyas&#305;na yorum yapt&#305;',

	'item:object:file' => 'Dosyalar',

	'file:newupload' => 'Yeni bir dosya y&#252;klendi',
	'file:notification' =>
'%s yeni bir dosya y&#252;kledi:

%s
%s

Yeni dosyay&#305; g&#246;r&#252;nt&#252;leyin ve yorumlay&#305;n:
%s
',

	/**
	 * Embed media
	 **/

		'file:embed' => "Media g&#246;m",
		'file:embedall' => "T&#252;m&#252;",

	/**
	 * Status messages
	 */

		'file:saved' => "Dosyan&#305;z ba&#351;ar&#305;yla kaydedildi.",
		'file:deleted' => "Dosyan&#305;z ba&#351;ar&#305;yla silindi.",

	/**
	 * Error messages
	 */

		'file:none' => "Dosya yok.",
		'file:uploadfailed' => "&#220;zg&#252;n&#252;z; dosyan&#305;z kaydedilemedi.",
		'file:downloadfailed' => "&#220;zg&#252;n&#252;z; bu dosya &#351;u anda ula&#351;&#305;labilir de&#287;il.",
		'file:deletefailed' => "Dosyan&#305;z &#351;u anda silinemedi.",
		'file:noaccess' => "Bu dosyay&#305; de&#287;i&#351;tirmek i&#231;in yetkiniz yok",
		'file:cannotload' => "Dosya y&#252;klenirken hata meydana geldi",
		'file:nofile' => "Bir dosya se&#231;melisiniz",
);

add_translation("tr", $turkish);