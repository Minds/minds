<?php
/**
 * Bookmarks English language file
 *
 * @update 2012-9-9
 * @version 1.8.4
 * @subpackage Languages.Japanese
 */

/**
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 * 
 * bookmarks:new
 *    新規ブックマークが追加された時
 *
 */

$japanese = array(
	
	/**
	 * Menu items and titles
	 */

	 'bookmarks' => "ブックマーク",
	 'bookmarks:add' => "新規ブックマーク登録",
	 'bookmarks:edit' => "ブックマークを編集",
	 'bookmarks:owner' => "%s さんのブックマーク",
	 'bookmarks:friends' => "友達のブックマーク",
	 'bookmarks:everyone' => "サイト全体のブックマーク",
	 'bookmarks:this' => "このページをブックマークする",
	 'bookmarks:this:group' => "%s のブックマーク",
	 'bookmarks:bookmarklet' => "ブックマークレットの取得",
	 'bookmarks:bookmarklet:group' => "グループのブックマークレットの取得",
	 'bookmarks:inbox' => "Bookmarks inbox",//これを参照しているところはありません。
	 'bookmarks:morebookmarks' => "もっと見る",
	 'bookmarks:more' => "もっと見る",
	 'bookmarks:with' => "共有するメンバーの選択",
	 'bookmarks:new' => "新しいブックマークが追加されました",
	 'bookmarks:address' => "ブックマークのアドレス",
	 'bookmarks:none' => 'ブックマークはひとつも登録されていません',
	
	'bookmarks:notification' =>
'%s さんが新しいブックマークを追加しました。:

%s - %s
%s

閲覧してコメントするには:
%s
',
	 'bookmarks:delete:confirm' => "削除してもよろしいですか?",
	
	 'bookmarks:numbertodisplay' => '表示するブックマークの件数',
	
	 'bookmarks:shared' => "ブックマーク済み",
	 'bookmarks:visit' => "ブックマーク先へ",
	 'bookmarks:recent' => "最近のブックマーク",
	
	 'river:create:object:bookmarks' => '%s さんは、%s をブックマークに登録しました。',
	 'river:comment:object:bookmarks' => '%s さんは、ブックマーク %s にコメントしました。',
	 'bookmarks:river:annotate' => 'このブックマークへのコメント',
	 'bookmarks:river:item' => 'アイテム',
	
	 'item:object:bookmarks' => 'ブックマーク',
	
	 'bookmarks:group' => 'グループブックマーク',
	 'bookmarks:enablebookmarks' => 'グループブックマークの利用',
	 'bookmarks:nogroup' => 'このグループには、まだブックマークが登録されていません。',
	 'bookmarks:more' => 'もっと',

	 'bookmarks:no_title' => 'No title',

	 /**
	  * Widget and bookmarklet
	  */
	 'bookmarks:widget:description' => "あなたの最近のブクマークを表示します。",
	
	 'bookmarks:bookmarklet:description' => "ブックマークのブックマークレットはWebで見つけたサイトの情報を友達や自分のために素早く保存するためのものです。下のボタンをブラウザのリンクバーにドラッグするだけで利用が開始できます。",

	 'bookmarks:bookmarklet:descriptionie' => "Internet Explorerをお使いの方はブックマークレットアイコンを右クリックしてから「お気に入りに保存」を選択していただき、その後、リンクバーに登録してください。",
	 
	 'bookmarks:bookmarklet:description:conclusion' => "いつでもクリックすることによって、訪れたことのあるページを保存することができるようになります",
	
	 /**
	  * Status messages
	  */
	
	 'bookmarks:save:success' => "ブックマークに登録しました。",
	 'bookmarks:delete:success' => "ブックマークから削除しました。",
	
	 /**
	  * Error messages
	  */
	 
	 'bookmarks:save:failed' => "ブックマークに登録できませんでした。タイトル欄とアドレス欄に入力したことを確かめて、もう一度試してみてください。",
	 'bookmarks:save:invalid' => "このブックマークのアドレスはどこか間違っていますので、保存することはできませんでした。",
	 'bookmarks:delete:failed' => "ブックマークから削除できませんでした。もう一度試してみてください。",
	
	);
					
add_translation("ja",$japanese);
