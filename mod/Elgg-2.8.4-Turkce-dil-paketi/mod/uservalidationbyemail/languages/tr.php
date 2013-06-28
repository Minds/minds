<?php
/**
 * Email user validation eklentisi T&#252;rk&#231;e dil paketi.
 *
 * @package Elgg.Core.Plugin
 * @subpackage ElggUserValidationByEmail
 */

$turkish = array(
	'admin:users:unvalidated' => 'Do&#287;rulanmad&#305;',
	
	'email:validate:subject' => "%s l&#252;tfen e-posta adresinizi %s i&#231;in do&#287;rulay&#305;n!",
	'email:validate:body' => "%s,

%s sitesini kullanmaya ba&#351;lamadan &#246;nce, e-posta adresinizi do&#287;rulamal&#305;s&#305;n&#305;z.

L&#252;tfen a&#351;a&#287;&#305;daki ba&#287;lant&#305;ya t&#305;klayarak e-posta adresinizi do&#287;rulay&#305;n&#305;z:

%s

E&#287;er ba&#287;lant&#305;ya t&#305;klayam&#305;yorsan&#305;z, taray&#305;c&#305;n&#305;z&#305;n adres &#231;ubu&#287;una kopyalay&#305;p yap&#305;&#351;t&#305;r&#305;n ve adrese gidin.

%s
%s
",
	'email:confirm:success' => "E-Posta adresinizi do&#287;rulad&#305;n&#305;z!",
	'email:confirm:fail' => "E-Posta adresiniz do&#287;rulanamad&#305;...",

	'uservalidationbyemail:registerok' => "Hesab&#305;n&#305;z&#305; etkinle&#351;tirmek i&#231;in, l&#252;tfen size g&#246;nderilen ba&#287;lant&#305;ya t&#305;klayarak e-posta adresinizi do&#287;rulay&#305;n.",
	'uservalidationbyemail:login:fail' => "Hesab&#305;n&#305;z do&#287;rulanmad&#305;&#287;&#305; i&#231;in giri&#351; yapam&#305;yorsunuz. Tekrar do&#287;rulama e-postas&#305; g&#246;nderildi.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Do&#287;rulanmam&#305;&#351; kullan&#305;c&#305; yok.',

	'uservalidationbyemail:admin:unvalidated' => 'Do&#287;rulanmam&#305;&#351;',
	'uservalidationbyemail:admin:user_created' => '%s kaydedildi',
	'uservalidationbyemail:admin:resend_validation' => 'Tekrar do&#287;rulama e-postas&#305; g&#246;nder',
	'uservalidationbyemail:admin:validate' => 'Do&#287;rula',
	'uservalidationbyemail:admin:delete' => 'Sil',
	'uservalidationbyemail:confirm_validate_user' => '%s do&#287;rulans&#305;n m&#305;?',
	'uservalidationbyemail:confirm_resend_validation' => '%s i&#231;in tekrar do&#287;rulama e-postas&#305; g&#246;nderilsin mi?',
	'uservalidationbyemail:confirm_delete' => '%s silinsin mi?',
	'uservalidationbyemail:confirm_validate_checked' => 'Se&#231;ilen kullan&#305;c&#305;lar do&#287;rulans&#305;n m&#305;?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Se&#231;ilen kullan&#305;c&#305;lara tekrar do&#287;rulama e-postas&#305; g&#246;nderilsin mi?',
	'uservalidationbyemail:confirm_delete_checked' => 'Se&#231;ilen kullan&#305;c&#305;lar silinsin mi?',
	'uservalidationbyemail:check_all' => 'T&#252;m&#252;',

	'uservalidationbyemail:errors:unknown_users' => 'Bilinmeyen kullan&#305;c&#305;lar',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Kullan&#305;c&#305; do&#287;rulanamad&#305;.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Se&#231;ilen kullan&#305;c&#305;lar&#305;n hi&#231; biri do&#287;rulanamad&#305;.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Kullan&#305;c&#305; silinemedi.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Se&#231;ilen kullan&#305;c&#305;lar&#305;n hi&#231; biri silinemedi.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Do&#287;rulama iste&#287;i g&#246;nderilemedi.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Se&#231;ilen kullan&#305;c&#305;lar&#305;n hi&#231; birine do&#287;rulama iste&#287;i g&#246;nderilemedi.',

	'uservalidationbyemail:messages:validated_user' => 'Kullan&#305;c&#305; do&#287;ruland&#305;.',
	'uservalidationbyemail:messages:validated_users' => 'T&#252;m se&#231;ili kullan&#305;c&#305;lar do&#287;ruland&#305;.',
	'uservalidationbyemail:messages:deleted_user' => 'Kullan&#305;c&#305; silindi.',
	'uservalidationbyemail:messages:deleted_users' => 'T&#252;m se&#231;ili kullan&#305;c&#305;lar silindi.',
	'uservalidationbyemail:messages:resent_validation' => 'Do&#287;rulama iste&#287;i tekrar g&#246;nderildi.',
	'uservalidationbyemail:messages:resent_validations' => 'Se&#231;ilen kullan&#305;c&#305;lara do&#287;rulama iste&#287;i g&#246;nderildi.'

);

add_translation("tr", $turkish);