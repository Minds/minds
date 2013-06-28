<?php
/* Message board plugin langue file
 *
 * @version 1.8.3
 * @update 2012-6-28
 *
 *
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 * 
 *
 * messageboard:email:subject
 *      伝言板にコメントされた時に送られるメール
 *
 * 例）
 *'messageboard:email:subject' => '【Elgg研究会】伝言板にコメントがされています！',
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
	
	'messageboard:board' => "伝言板",
	'messageboard:messageboard' => "伝言板",
	'messageboard:viewall' => "すべてみる",
	'messageboard:postit' => "投稿",
	'messageboard:history:title' => "履歴",
	'messageboard:none' => "伝言はありません。",
	'messageboard:num_display' => "表示数",
	'messageboard:desc' => "「伝言板」を使うとプロフィールページ上でいろいろな人から書き込みをしてもらえます。",
	
	'messageboard:user' => "%sさんの伝言板",
	
	'messageboard:replyon' => '返信',
	'messageboard:history' => "履歴",
	
	'messageboard:owner' => '%sさんの伝言板',
	'messageboard:owner_history' => '%sさんは、%sさんの伝言板に伝言を残しています',

	/**
	 * Message board widget river
	 **/
	        
	'river:messageboard:user:default' => "%sさんは%sさん伝言板に伝言を残しました",

	/**
	 * Status messages
	 */
	
	'messageboard:posted' => "伝言を書き込みをしました。",
	'messageboard:deleted' => "伝言を削除しました。",
	
	/**
	 * Email messages
	 */
	
	'messageboard:email:subject' => '伝言板にコメントがされています！',
	'messageboard:email:body' => "%s さんから伝言板に新しい書き込みがありました:

%s

伝言板のコメントをみるには:

	%s

%s さんのプロフィールを見るには:

	%s

(＊) このメールに返信しないでください。",

	/**
	 * Error messages
	 */
	
	'messageboard:blank' => "申し訳ありません。メッセージ欄が空欄では保存できません。",
	'messageboard:notfound' => "申し訳ありません。そのアイテムは見当たりません。",
	'messageboard:notdeleted' => "申し訳ありません。書き込みを削除できません。",
	'messageboard:somethingwentwrong' => "伝言を保存しようとしましたが、何らかの問題が発生しました。実際に伝言板にお書きになったのかご確認ください。",
	     
	'messageboard:failure' => "書き込みの際に何からのエラーが発生しました。もう一度お試しください。",
	
);
					
add_translation("ja",$japanese);
