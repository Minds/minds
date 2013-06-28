<?php
/**
 * The Wire English language file
 *
 * @version 1.8.4
 * @update 2012-6-28
 *
 *
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 * 
 *
 * thewire:notify:subject
 *      新しい「つぶやき」が投稿された時の通知メールの題名
 * 例）
 * 'thewire:notify:subject' => "【Elgg研究会】新しいつぶやき",
 * 
 *
 *
 *ちなみに、コアモジュールのうち、Email通知が定義されているファイルは、
 *
 *./mod/likes/languages/ja.php
 *./mod/groups/languages/ja.php
 *./mod/invitefriends/languages/ja.php
 *./mod/uservalidationbyemail/languages/ja.php
 *./mod/messageboard/languages/ja.php
 *./mod/messages/languages/ja.php
 *./mod/thewire/languages/ja.php
 *./languages/ja.php
 *
 *です。
 *

 */

$japanese = array(
	
	/**
	 * Menu items and titles
	 */
	'thewire' => "つぶやき",
	'thewire:everyone' => "みんなのつぶやき",
	'thewire:user' => "%s さんのつぶやき",
	'thewire:friends' => "友達のつぶやき",
	'thewire:reply' => "返信",
	'thewire:replying' => "返信: %s (@%s) さんへ 内容",
	'thewire:thread' => "スレッド",
	'thewire:charleft' => "残りの文字数（半角文字で）",
	'thewire:tags' => "「 %s 」でタグ付けされたつぶやき",
	'thewire:noposts' => "つぶやきはありません",
	'item:object:thewire' => "つぶやき",
	'thewire:update' => '更新',
	'thewire:by' => '%s さんのつぶやき',

	'thewire:previous' => "前",
	'thewire:hide' => "隠す",
	'thewire:previous:help' => "前の投稿を見る",
	'thewire:hide:help' => "前の投稿隠す",
	
        /**
	 * The wire river
	 **/
	'river:create:object:thewire' => "%s さんが %s に投稿しました",
	'thewire:wire' => 'つぶやき',

	/**
	 * Wire widget
	 **/
	'thewire:widget:desc' => 'アタナの最近のつぶやきを表示',
	'thewire:num' => '表示数',
	'thewire:moreposts' => 'もっと見る',
	
	/**
	 * Status messages
	 */
	'thewire:posted' => "あなたのつぶやきを投稿しました。",
	'thewire:deleted' => "つぶやきを削除しまいした。",
	'thewire:blank' => "申し訳ありません、入力欄が空欄なので投稿できません。",
	'thewire:notfound' => "申し訳ありません、お探しの投稿は見つかりませんでした。",
	'thewire:notdeleted' => "申し訳ありません、この投稿を削除できませんでした。",
	
	/**
	 * Notifications
	 */
	'thewire:notify:subject' => "新しいつぶやきが追加されました",
	'thewire:notify:reply' => '%s さんが%s さんのつぶやきに返信しました:',
	'thewire:notify:post' => '%s さんのつぶやき:',

);
					
add_translation("ja",$japanese);

