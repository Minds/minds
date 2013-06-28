<?php
/**
 * Email user validation plugin 日本語 language pack.
 *
 * @package Elgg.Core.Plugin
 * @subpackage ElggUserValidationByEmail
 * @version 1.8.3
 * @update 2012-9-20
 *
 *
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 * 
 * email:validate:subject
 *          登録確認メール
 *
 * 例）
 *'email:validate:subject' => "【Elgg研究会:登録確認メール】%s様、%sで登録されたメールアドレスにお送りしています",
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

	'admin:users:unvalidated' => '未確認ユーザ',
	
	'email:validate:subject' => "【登録確認メール】%s様、%sで登録されたメールアドレスにお送りしています",
	'email:validate:body' => "%s様、
この度は当サイトへのご登録申し込みありがとうございました。

%s
をご利用になられる前に、ご登録されたメールアドレスが正しいかどか確認をいたします。お手数ですが、下記のリンクをクリックして確認作業を終了させてください。

%s

もし、何らかの理由でリンクをクリックできないときは、手作業にてこのリンクアドレスをブラウザにコーピー＆ペーストしてください。

尚、このメールに心当たりのない方は、無視してください。

%s
%s
",
	'email:confirm:success' => "あなたの電子メールアドレスを確認しました。",
	'email:confirm:fail' => "あなたの電子メールアドレスが正しいアドレスなのかどうかを確認できませんでした...",

	'uservalidationbyemail:registerok' => "先ほどあなたが登録されたメールアドレスに確認メールをお送りしました。ご登録されたメールアドレスが正しければご覧になれるはずです。その確認メールに書いておりますリンクをクリックしていただいて、初めてアカウントが有効になり登録が完了となります。",
	'uservalidationbyemail:login:fail' => "あなたのアカウントはまだご確認させていただいておりませんので、ログインすることができません。再度別の確認メールをお送りいたしますのでそれにしたがって、確認作業を完了させていただくよう、よろしくお願いします。",

	'uservalidationbyemail:admin:no_unvalidated_users' => '未確認のユーザは、いません。',

	'uservalidationbyemail:admin:unvalidated' => '未確認',
	'uservalidationbyemail:admin:user_created' => '%s さんを登録しました',
	'uservalidationbyemail:admin:resend_validation' => '確認メールを再送',
	'uservalidationbyemail:admin:validate' => '確認済み',
	'uservalidationbyemail:admin:delete' => '削除',
	'uservalidationbyemail:confirm_validate_user' => '%s さんを確認しますか？',
	'uservalidationbyemail:confirm_resend_validation' => '%s さんに確認メールを再送しますか？',
	'uservalidationbyemail:confirm_delete' => '%s さんの登録を抹消しますか？',
	'uservalidationbyemail:confirm_validate_checked' => 'チェックしたユーザを確認しますか？',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'チェックしたユーザに確認メールを再送しますか？',
	'uservalidationbyemail:confirm_delete_checked' => 'チェックしたユーザを削除しますか？',
	'uservalidationbyemail:check_all' => 'すべて',

	'uservalidationbyemail:errors:unknown_users' => 'そのユーザは登録されていません',
	'uservalidationbyemail:errors:could_not_validate_user' => 'ユーザを確認できまんでした。',
	'uservalidationbyemail:errors:could_not_validate_users' => 'チェックしたユーザは全員確認できませんでした。',
	'uservalidationbyemail:errors:could_not_delete_user' => 'ユーザを削除できませんでした。',
	'uservalidationbyemail:errors:could_not_delete_users' => 'チェックしたユーザは全員削除できませんでした。',
	'uservalidationbyemail:errors:could_not_resend_validation' => '確認メールの再送ができませんでした。',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'チエックしたユーザ全員に確認メールを再送できませんでした。',

	'uservalidationbyemail:messages:validated_user' => 'ユーザを確認しました',
	'uservalidationbyemail:messages:validated_users' => 'チェックしたユーザは全員確認しました。',
	'uservalidationbyemail:messages:deleted_user' => 'ユーザを削除しました',
	'uservalidationbyemail:messages:deleted_users' => 'チェックしたユーザ全員を削除しました。',
	'uservalidationbyemail:messages:resent_validation' => '確認メールを送信しました。',
	'uservalidationbyemail:messages:resent_validations' => 'チェックしたユーザ全員に確認メールを再送しました。',

);

add_translation('ja', $japanese);
