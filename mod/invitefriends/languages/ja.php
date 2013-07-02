<?php

/**
 * Elgg invite language file
 * 
 * @version 1.8.3
 * @update 2012.6.28
 * @package ElggInviteFriends
 *
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 * 
 * invitefriends:subject
 *    友達勧誘のメール作成時に送られます
 *
 * 例）
 *'invitefriends:subject' => '【%s】への招待状',
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
	
	'friends:invite' => '友達を招待する',

	'invitefriends:registration_disabled' => 'このサイトでは新規ユーザ登録はできないように設定されていまので、新しいユーザを招待することはできません。',

	'invitefriends:introduction' => '記入力欄にEメールアドレスを入力して（1メールアドレスにつき行ずつ）、このネットワークに友達を招待しましょう。:',
	'invitefriends:message' => 'このサイトへの招待状の本文を入力して下さい。',
	'invitefriends:subject' => '【%s】への招待状',
	
	'invitefriends:success' => 'あなたの友達を招待しました。',
	'invitefriends:invitations_sent' => '招待状を送りました: %s 。その際、以下の問題が発生しました:',
	'invitefriends:email_error' => '招待状を送信しましたが、次のアドレスはどこか間違っている為、送信できませんでした。: %s',
	'invitefriends:already_members' => '以下の方は、すでにメンバです。: %s',
	'invitefriends:noemails' => '電子メールアドレスが入力されていません。',
	
	'invitefriends:message:default' => '
こんにちは、

あなたをSNSサイト %s へお誘いしようと思っております。

ぜひご参加いただけたらと思います。
',
	'invitefriends:email' => '
SNSサイト %1$s への招待状をお届けいたします。
%2$s 様から下記のメッッセージをお預かりしております:

%3$s

ご参加いただける場合は、下のリンクをクリックしてください:

%4$s

アカウントを作成すると自動的に友達として登録されます。',
	);

add_translation("ja",$japanese);
