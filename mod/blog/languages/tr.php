<?php
/**
 * Blog T&#252;rk&#231;e Dil Dosyas&#305;.
 *
 */

$turkish = array(
	'blog' => 'Bloglar',
	'blog:blogs' => 'Bloglar',
	'blog:revisions' => 'Revizyonlar',
	'blog:archives' => 'Ar&#351;ivler',
	'blog:blog' => 'Blog',
	'item:object:blog' => 'Bloglar',

	'blog:title:user_blogs' => '%s\'in bloglar&#305;',
	'blog:title:all_blogs' => 'T&#252;m site bloglar&#305;',
	'blog:title:friends' => 'Arkada&#351;lar&#305;n bloglar&#305;',

	'blog:group' => 'Grup blogu',
	'blog:enableblog' => 'Grup blogunu etkinle&#351;tir',
	'blog:write' => 'Bir blog yaz',

	// Editing
	'blog:add' => 'Bir blog yaz&#305;s&#305; ekle',
	'blog:edit' => 'Blog yaz&#305;s&#305;n&#305; d&#252;zenle',
	'blog:excerpt' => 'Al&#305;nt&#305;',
	'blog:body' => 'G&#246;vde',
	'blog:save_status' => 'Son kay&#305;t: ',
	'blog:never' => 'Asla',

	// Statuses
	'blog:status' => 'Durum',
	'blog:status:draft' => 'Taslak',
	'blog:status:published' => 'Yay&#305;nland&#305;',
	'blog:status:unsaved_draft' => 'Kaydedilmemi&#351; Taslak',

	'blog:revision' => 'Revizyon',
	'blog:auto_saved_revision' => 'Oto-Kay&#305;t Revizyon',

	// messages
	'blog:message:saved' => 'Blog yaz&#305;s&#305; kaydedildi.',
	'blog:error:cannot_save' => 'Blog yaz&#305;s&#305; kaydedilemiyor.',
	'blog:error:cannot_write_to_container' => 'Gruba blog eklemek i&#231;in yetersiz eri&#351;im.',
	'blog:error:post_not_found' => 'Bu yaz&#305; kald&#305;r&#305;ld&#305;, ge&#231;ersiz, ya da g&#246;r&#252;nt&#252;lemek i&#231;in yeterli yetkiniz yok.',
	'blog:messages:warning:draft' => 'Bu yaz&#305;n&#305;n kaydedilmemi&#351; bir tasla&#287;&#305; var!',
	'blog:edit_revision_notice' => '(Eski versiyon)',
	'blog:message:deleted_post' => 'Blog yaz&#305;s&#305; silindi.',
	'blog:error:cannot_delete_post' => 'Blog yaz&#305;s&#305; silinemiyor',
	'blog:none' => 'Blog yaz&#305;s&#305; yok',
	'blog:error:missing:title' => 'L&#252;tfen blog ba&#351;l&#305;&#287;&#305;n&#305; yaz&#305;n!',
	'blog:error:missing:description' => 'L&#252;tfen blogunuzun g&#246;vde metnini yaz&#305;n!',
	'blog:error:cannot_edit_post' => 'Bu yaz&#305; var olmayabilir ya da d&#252;zenlemek i&#231;in yetkiniz olmayabilir.',
	'blog:error:revision_not_found' => 'Bu revizyon bulunam&#305;yor.',

	// river
	'river:create:object:blog' => '%s bir blog yaz&#305;s&#305; %s yay&#305;nlad&#305;',
	'river:comment:object:blog' => '%s %s bloguna yorum yapt&#305;',

	// notifications
	'blog:newpost' => 'Yeni blog yaz&#305;s&#305;',
	'blog:notification' =>
'
%s yeni bir blog yazd&#305;.

%s
%s

Yeni blo&#287;u g&#246;r&#252;nt&#252;leyin ve yorumlay&#305;n:
%s
',

	// widget
	'blog:widget:description' => 'En son blog yaz&#305;lar&#305;n&#305; g&#246;r&#252;nt&#252;le',
	'blog:moreblogs' => 'Daha fazla blog yaz&#305;s&#305;',
	'blog:numbertodisplay' => 'G&#246;sterilecek blog yaz&#305;s&#305; say&#305;s&#305;',
	'blog:noblogs' => 'Blog yaz&#305;s&#305; yok'
);

add_translation('tr', $turkish);
