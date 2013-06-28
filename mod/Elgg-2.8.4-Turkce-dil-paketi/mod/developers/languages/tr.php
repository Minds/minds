<?php
/**
 * Elgg developer tools T&#252;rk&#231;e dil dosyas&#305;.
 *
 */

$turkish = array(
	// menu
	'admin:develop_tools' => 'Ara&#231;lar',
	'admin:develop_tools:preview' => 'Tema Havuzu',
	'admin:develop_tools:inspect' => 'Denetle',
	'admin:develop_tools:unit_tests' => 'Birim Testleri',
	'admin:developers' => 'Geli&#351;tiriciler',
	'admin:developers:settings' => 'Ayarlar',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Geli&#351;tirme ve hata ay&#305;klama ayarlar&#305;n&#305; a&#351;a&#287;&#305;da kontrol edin. Bu ayarlardan baz&#305;lar&#305; di&#287;er y&#246;netici sayfalar&#305;nda da mevcuttur.',
	'developers:label:simple_cache' => 'Basit &#246;nbellek kullan',
	'developers:help:simple_cache' => 'Geli&#351;tirme yaparken dosya &#246;nbelle&#287;ini kapat&#305;n. Aksi takdirde, g&#246;r&#252;n&#252;m de&#287;i&#351;iklikleri (css dahil) yok say&#305;lacak.',
	'developers:label:view_path_cache' => 'Yol g&#246;r&#252;nt&#252;leme &#246;nbelle&#287;i kullan',
	'developers:help:view_path_cache' => 'Geli&#351;tirme yaparken bunu kapat&#305;n. Aksi takdirde, eklentilerinizdeki yeni g&#246;r&#252;nt&#252;lemeler kaydedilemeyecek.',
	'developers:label:debug_level' => "&#304;zleme seviyesi",
	'developers:help:debug_level' => "Bu kaydedilen bilgi miktar&#305;n&#305; kontrol eder. Daha fazla bilgi i&#231;in elgg_log() inceleyin.",
	'developers:label:display_errors' => '&#214;nemli PHP hatalar&#305;n&#305; g&#246;r&#252;nt&#252;le',
	'developers:help:display_errors' => "Varsay&#305;lan olarak, Elgg'in .htaccess dosyas&#305; &#246;nemli hata g&#246;r&#252;nt&#252;lemelerini engeller.",
	'developers:label:screen_log' => "Loglar&#305; ekrana yazd&#305;r",
	'developers:help:screen_log' => "Bu elgg_log() ve elgg_dump() &#231;&#305;kt&#305;lar&#305;n&#305; web sayfas&#305;nda g&#246;sterir.",
	'developers:label:show_strings' => "Ham &#231;eviri dizilerini g&#246;ster",
	'developers:help:show_strings' => "Bu elgg_echo() taraf&#305;ndan kullan&#305;lan &#231;eviri dizilerini g&#246;sterir.",
	'developers:label:wrap_views' => "G&#246;r&#252;nt&#252;leri &#246;rt",
	'developers:help:wrap_views' => "Bu neredeyse t&#252;m yorumlar&#305; HTML yorumlarla &#246;rter. Belirli bir HTML olu&#351;turarak g&#246;r&#252;n&#252;m&#252; bulmak i&#231;in kullan&#305;&#351;l&#305;d&#305;r.",
	'developers:label:log_events' => "Log olaylar&#305; ve eklenti kancalar&#305;",
	'developers:help:log_events' => "Olaylar&#305; ve eklenti kancalar&#305;n&#305; log dosyalar&#305;na yaz&#305;n. Uyar&#305;: Sayfa ba&#351;&#305;na &#231;ok fazla vard&#305;r.",

	'developers:debug:off' => 'Kapal&#305;',
	'developers:debug:error' => 'Hata',
	'developers:debug:warning' => 'Uyar&#305;',
	'developers:debug:notice' => 'Bilgi',
	
	// inspection
	'developers:inspect:help' => 'Elgg &#231;er&#231;evesinin yap&#305;land&#305;rmas&#305;n&#305; kontrol et',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' %s'de",

	// theme preview
	'theme_preview:general' => 'Giri&#351;',
	'theme_preview:breakout' => 'Iframe &#231;&#305;k&#305;&#351;&#305;',
	'theme_preview:buttons' => 'D&#252;&#287;meler',
	'theme_preview:components' => 'Bile&#351;enler',
	'theme_preview:forms' => 'Formlar',
	'theme_preview:grid' => 'Izgara',
	'theme_preview:icons' => '&#304;konlar',
	'theme_preview:modules' => 'Mod&#252;ller',
	'theme_preview:navigation' => 'Navigasyon',
	'theme_preview:typography' => 'Tipografya',

	// unit tests
	'developers:unit_tests:description' => 'Elgg &#231;ekirdek s&#305;n&#305;flar&#305;nda ve fonksiyonlar&#305;nda hatalr&#305; tespit edebilen birimlere ve b&#252;t&#252;nlemelere sahiptir.',
	'developers:unit_tests:warning' => 'Uyar&#305;: Bu Testleri Yay&#305;nlanm&#305;&#351; Bir Sitede Uygulamay&#305;n. Veritaban&#305;n&#305;z&#305; bozabilir.',
	'developers:unit_tests:run' => '&#199;al&#305;&#351;t&#305;r',

	// status messages
	'developers:settings:success' => 'Ayarlar kaydedildi',
);

add_translation('tr', $turkish);
