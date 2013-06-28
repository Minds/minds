<?php
/**
 * Blog Japanese language file.
 *
 * @update 2013-2-14
 * @version 1.8.6
 * @subpackage Languages.Japanese
 */

/**
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 * 
 * blog:newpost
 *    新規ブログが追加された時
*/

$japanese = array(
	'blog' => 'ブログ',
	'blog:blogs' => 'ブログ',
	'blog:revisions' => '変更履歴',
	'blog:archives' => '書庫',
	'blog:blog' => 'ブログ',
	'item:object:blog' => 'ブログ',

	'blog:title:user_blogs' => '%s さんのブログ',
	'blog:title:all_blogs' => 'サイトの全ブログ',
	'blog:title:friends' => '友達のブログ',

	'blog:group' => 'グループブログ',
	'blog:enableblog' => 'グループブログを使えるようにする',
	'blog:write' => 'ブログに投稿する',

	// Editing
	'blog:add' => 'ブログ記事を追加',
	'blog:edit' => 'ブログ記事を編集',
	'blog:excerpt' => '見出し',
	'blog:body' => '本文',
	'blog:save_status' => '最後に保存:',
	'blog:never' => '初稿',

	// Statuses
	'blog:status' => '状態',
	'blog:status:draft' => '下書き',
	'blog:status:published' => '公開済み',
	'blog:status:unsaved_draft' => '未保存の下書き',

	'blog:revision' => '変更履歴',
	'blog:auto_saved_revision' => '自動保存された変更履歴',

	// messages
	'blog:message:saved' => 'ブログ記事を保存しました。',
	'blog:error:cannot_save' => 'ブログ記事を保存できませんでした。',
	'blog:error:cannot_write_to_container' => 'あなたの権限ではグループにブログを保存する事はできません。',
	'blog:messages:warning:draft' => '保存されていない下書きの記事があります！',
	'blog:edit_revision_notice' => '(前の版)',
	'blog:message:deleted_post' => 'ブログ記事を削除しました。',
	'blog:error:cannot_delete_post' => 'ブログ記事を削除できませんでした。',
	'blog:none' => 'ブログ記事は一件もありません',
	'blog:error:missing:title' => 'ブログのタイトルを入力してください！',
	'blog:error:missing:description' => 'ブログの本文を入力してください！',
	'blog:error:cannot_edit_post' => 'この記事は存在していないか、あるいは、あなたにこの記事を編集する権限がないかのどちらかです。',
	'blog:error:revision_not_found' => 'この変更記録を見つけることはできませんでした。',

	// river
	'river:create:object:blog' => '%s さんは、ブログ記事「%s」を公表しました。',
	'river:comment:object:blog' => '%s さんは、ブログ「%s」にコメントしました。',

	// notifications
	'blog:newpost' => '新しいブログ記事が追加されました',
	'blog:notification' =>
'
%s さんがブログに新しい記事を投稿しました。

%s
%s

閲覧してコメントするには:
%s
',
	// widget
	'blog:widget:description' => 'あなたの最近のブログ記事を表示',
	'blog:moreblogs' => '別のブログ記事',
	'blog:numbertodisplay' => 'ブログ記事の表示件数',
	'blog:noblogs' => 'ブログ記事は一つもありません',
);

add_translation('ja', $japanese);
