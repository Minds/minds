<?php
/**
 * Elgg send a message action page
 * 
 * @package ElggMessages
 * @version 1.8.4
 * @update 2012-5-16
 * @subpackage Languages.Japanese
 *
 *
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 * 
 *
 * messages:email:subject
 *      メッセージが届いた時に通知されるメールの題名
 * 例）
 * 'messages:email:subject' => '【Elgg研究会】新しいメッセージが届きました！',
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
	
	'messages' => "メッセージ",
	'messages:unreadcount' => "未読 %s 件",
        'messages:back' => "メッセージにもどる",
	'messages:user' => "%s さんの受信箱",
	'messages:posttitle' => "%s さんのメッセージ: %s",
	'messages:inbox' => "受信箱",
	'messages:send' => "メッセージの送信",
	'messages:sent' => "送信済みメッセージ",
	'messages:message' => "メッセージ",
	'messages:title' => "タイトル",
	'messages:to' => "宛先",
	'messages:from' => "送信元",
	'messages:fly' => "送信",
	'messages:replying' => "返信先",
	'messages:inbox' => "受信箱",
	'messages:sendmessage' => "メッセージの送信",
	'messages:compose' => "新規メッセージの作成",
	'messages:add' => "メッセージの作成",
	'messages:sentmessages' => "送信済みメッセージ",
	'messages:recent' => "最近のメッセージ",
	'messages:original' => "オリジナルのメッセージ",
	'messages:yours' => "あなたのメッセージ",
	'messages:answer' => "返信",
	'messages:toggle' => '全てを選択',
	'messages:markread' => '既読マーク',
	'messages:recipient' => 'Choose a recipient&hellip;', //参照なし
	'messages:to_user' => '宛先: %s',
			
	'messages:new' => '新しいメッセージ',
	
	'notification:method:site' => 'サイト',
	
	'messages:error' => 'メッセージの保存の際に問題が発生しました。もう一度やり直してください。',
	
	'item:object:messages' => 'メッセージ',
	
	/**
	 * Status messages
	 */
	
	'messages:posted' => "メッセージを送信しました。",
	'messages:success:delete:single' => 'メッセージを削除しました',
	'messages:success:delete' => 'メッセージを削除しました',
	'messages:success:read' => 'メッセージを「既読」にしました',
	'messages:error:messages_not_selected' => '選択されてるメッセージはありません。',
	'messages:error:delete:single' => 'メッセージを削除できませんでした。',

	/**
	 * Email messages
	 */
	
	'messages:email:subject' => '新しいメッセージが届きました！',
	'messages:email:body' => "%sさんから新しいメッセージが届きました。

%s

メッセージをみるには下記をクリックして下さい。

        %s

%sさんに返信するには下記をクリックして下さい。

        %s

(＊）このメールに返信しないでください。",

	/**
	 * Error messages
	 */
	
	'messages:blank' => "申し訳ありません。メッセージの本文が空欄のため、保存できません。。",
	'messages:notfound' => "申し訳ありません。メッセージが見当たらいません。",
	'messages:notdeleted' => "申し訳ありません。メッセージが削除できません。",
	'messages:nopermission' => "メッセージを変更する権限がありません。",
	'messages:nomessages' => "メッセージがありません。",
	'messages:user:nonexist' => "ユーザー一覧にその送信先がありません。",
	'messages:user:blank' => "送信先を指定してください。",

	'messages:deleted_sender' => '削除されたユーザ',

);
					
add_translation("ja",$japanese);

