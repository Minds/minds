

通知機能で使用されるメールのsubjectについて
==========================================
Language files for Elgg 1.8.9
2012-11-15


通知機能によって、ブログの更新時やファイルのアップロード時に各ユーザ宛にお知らせメールが送信されますが、このときのsubjectをカスタマイズしておくと、読み手にとって親切です。

通知に登録されているプラグインの言語ファイル
1)	languages/ja.php
2)	mod/blog/languages/ja.php
3)	mod/bookmarks/languages/ja.php
4)	mod/file/languages/ja.php
5)	mod/groups/languages/ja.php
6)	mod/invitefriends/languages/ja.php
7)	mod/likes/languages/ja.php
8)	mod/messageboard/languages/ja.php
9)	mod/messages/languages/ja.php
10)	mod/pages/languages/ja.php
11)	mod/uservalidationbyemail/languages/ja.php
12)	mod/thewire/languages/ja.php


以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
必要に応じて内容を書き換えて使用すると便利です。


例)
メッセージサブジェクト		=> メッセージ本文

----------------------------------------------------------------------
1)languages/ja.php

useradd:subject                => useradd:body
friend:newfriend:subject       => friend:newfriend:body
email:resetpassword:subject    => email:resetpassword:body
email:resetreq:subject	       => email:resetreq:body
generic_comment:email:subject  => generic_comment:email:body


----------------------------------------------------------------------
2)mod/blog/languages/ja.php

blog:newpost			=> blog:notification


----------------------------------------------------------------------
3)mod/bookmarks/languages/ja.php

bookmarks:new			=> bookmarks:notification


----------------------------------------------------------------------
4)mod/file/language/ja.php

file:newupload			=> file:notification


----------------------------------------------------------------------
5)mod/groups/languages/ja.php

groupforumtopic:new .....................参照箇所なし
discussion:notification:topic:subject	=> group:notification
					=> group:otification:reply:body
groups:invite:subject			=> groups:invite:body
groups:welcome:subject			=> groups:welcome:body
groups:request:subject			=> groups:request:body


----------------------------------------------------------------------
6)mod/invitefriends/languages/ja.php

invitefriends:subject			=> invitefriends:message:default


----------------------------------------------------------------------
7)mod/likes/languages/ja.php

likes:notifications:subject		=> likes:notifications:body


----------------------------------------------------------------------
8)mod/messageboard/language/ja.php

messageboard:email:subject		=> messageboard:email:body


----------------------------------------------------------------------
9)mod/messages/languages/ja.php

messages:email:subject			=> messages:email:body


----------------------------------------------------------------------
10)mod/pages/languages/ja.php

pages:new				=> pages:notification


----------------------------------------------------------------------
11)mod/uservalidationbyemail/languages/ja.php

email:validate:subject			=> email:validate:body


----------------------------------------------------------------------
12)mod/thewire/languages/ja.php

thewire:notify:subject			=> thewire:notify:reply
					=> thewire:notify:post
