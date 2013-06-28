<?php
/**
 * Elgg file plugin language pack
 *
 * @package ElggFile
 * @update 2012-8-19
 * @version 1.8.4
 * @subpackage Languages.Japanese
 *
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 *
 * 'file:newupload' => '新しいファイルがアップロードされました',
 *
 */

$japanese = array(
	/**
	 * Menu items and titles
	 */
	'file' => "ファイル",
	'file:user' => "%s さんのファイル",
	'file:friends' => "友達のファイル",
	'file:all' => "サイト全体のファイル",
	'file:edit' => "ファイル編集",
	'file:more' => "もっとみる",
	'file:list' => "リスト表示",
	'file:group' => "グループファイル",
	'file:gallery' => "ギャラリー表示",
	'file:gallery_list' => "ギャラリー表示 or リスト表示",
	'file:num_files' => "表示数",
	'file:user:gallery'=>'%s さんのギャラリーをみる',
	'file:upload' => "ファイルのアップロード",
	'file:replace' => 'ファイルの置き換え(変更しない場合は空欄のまま)',
	'file:list:title' => "%s さんの %s %s",
	'file:title:friends' => "友達の",
	
	'file:add' => 'ファイルをアップロード',
			
	'file:file' => "ファイル",
	'file:title' => "タイトル",
	'file:desc' => "説明",
	'file:tags' => "タグ",
	
	'file:list:list' => 'リスト表示に切り替え',
	'file:list:gallery' => 'ギャラリー表示に切り替え',

	'file:types' => "アップロードされたファイルのタイプ",
	
	'file:type:' => 'ファイル',
	'file:type:all' => "すべてのファイル",
	'file:type:video' => "動画",
	'file:type:document' => "ドキュメント",
	'file:type:audio' => "音声",
	'file:type:image' => "画像",
	'file:type:general' => "その他",
	
	'file:user:type:video' => "%s さんの動画",
	'file:user:type:document' => "%s さんのドキュメント",
	'file:user:type:audio' => "%s さんの音声",
	'file:user:type:image' => "%s さん画像",
	'file:user:type:general' => "%s さんのその他のファイル",
	
	'file:friends:type:video' => "友達の動画",
	'file:friends:type:document' => "友達のドキュメント",
	'file:friends:type:audio' => "友達の音声",
	'file:friends:type:image' => "友達の画像",
	'file:friends:type:general' => "友達のその他のファイル",
	
	'file:widget' => "ファイル・ウィジェット",
	'file:widget:description' => "あなたのファイル一覧",
	
	'groups:enablefiles' => 'グループファイルを使用する',

	'file:download' => "このファイルをダウンロード",
	
	'file:delete:confirm' => "本当にこのファイルを削除しますか？",
	
	'file:tagcloud' => "タグクラウド",
	
	'file:display:number' => "表示数",
	
	'river:create:object:file' => '%s さんがファイル「 %s 」をアップロードしました',
	'river:comment:object:file' => '%s さんがファイル「 %s 」にコメントしました',
	
	'item:object:file' => 'ファイル',
	
	'file:newupload' => '新しいファイルがアップロードされました',
	'file:notification' =>
'%s さんが新しいファイルをアップロードしました:

%s
%s

閲覧してコメントするには:
%s
',
		
	/**
	 * Embed media
	 **/
	
	'file:embed' => "メディアの埋め込み",
	'file:embedall' => "すべて",
	
	/**
	 * Status messages
	 */
	
	'file:saved' => "ファイルを保存しました。",
	'file:deleted' => "ファイルを削除しました。",
	
	/**
	 * Error messages
	 */
	
	'file:none' => "ファイルがありません。",
	'file:uploadfailed' => "申し訳ありません。ファイルを保存できません。",
	'file:downloadfailed' => "申し訳ありません。今、このファイルは利用できません。",
	'file:deletefailed' => "今、このファイルは削除できません。",
	'file:noaccess' => "このファイルを変更する権限がありません。",
	'file:cannotload' => "ファイルを読み込む際にエラーが発生しました。",
	'file:nofile' => "ファイルを選択して下さい。",
	);
					
add_translation("ja",$japanese);
