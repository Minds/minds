<?php
/**
 * Core Japanese Language
 *
 * @update 2013-5-1
 * @version 1.8.15
 * @package Elgg.Core
 * @subpackage Languages.Japanese
 *
 *
 *------------------------------------------------------------------
 * 以下は、このファイルで(Email 通知に使われるメールのサブジェクト)に使われるキー名です。
 * 必要に応じて内容を書き換えて使用すると便利です。
 * 
 * useradd:subject
 *    ユーザの作成時に送られます
 *
 * friend:newfriend:subject
 *    友だち登録時に送られます。
 *
 * email:resetpassword:subject
 *    パスワードのリセット完了後に送られます。
 *
 * email:resetreq:subject
 *    パスワードのリセット要求時に送られます。
 *
 * generic_comment:emailo:subject
 *    コメントされた時に送られます。
 *
 * 例）
 *'useradd:subject' => '【Elgg研究会】ユーザを作成しました。',
 *
 *'friend:newfriend:subject' => "【Elgg研究会】%s さんはあなたを友達に登録しました！",
 *
 *'email:resetpassword:subject' => "【Elgg研究会】パスワードをリセットしました",
 *
 *'email:resetreq:subject' => "【Elgg研究会】新しいパスワードのリクエスト",
 *
 *'generic_comment:email:subject' => '【Elgg研究会】新しいコメントがあります！',
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
 * Sites
 */

	'item:site' => 'サイト',

/**
 * Sessions
 */

	'login' => 'ログイン',
	'loginok' => "ログインしました。",
	'loginerror' => "ログインできませんでした。このサイトの登録したかどうかご確認の上、もう一度お試しください。",
	'login:empty' => "ログイン名とパスワードが必要です。",
	'login:baduser' => "あなたのログインアカウントを読み込むことができませんでした。",
	'auth:nopams' => "内部エラー。ユーザ認証機能がインストールされていません。",

	'logout' => "ログアウト",
	'logoutok' => "ログアウトしました。",
	'logouterror' => "ログアウトできませんでした。もう一度お試しください。",

	'loggedinrequired' => "ログインしないと、このページを見ることはできません。",
	'adminrequired' => "管理者でないと、このページを見ることはできません。",
	'membershiprequired' => "このグループのメンバでないと、このページを見ることはできません。",


/**
 * Errors
 */
	'exception:title' => "致命的なエラーです",
	'exception:contact_admin' => '復帰不可能なエラーが発生しましたのでログに記録しました。サイト管理者にコンタクトをとって次の情報を報告してください。:',

	'actionundefined' => "要求されたアクション(%s) はこのシステムで定義されていません。",
	'actionnotfound' => "%s のアクションファイルが見つかりませんでした。",
	'actionloggedout' => "ログアウトのままですと、アクションを実行できません。",
	'actionunauthorized' => 'あなたの権限では、このアクションを実行することはできません。',

	'InstallationException:SiteNotInstalled' => 'このリスエストは扱うことができません。このサイトがうまく設定されていないか、データベースがダウンしています。',
	'InstallationException:MissingLibrary' => '%s をロードできませんでした。',
	'InstallationException:CannotLoadSettings' => 'Elggは設定ファイルを読み込むことができませんでした。そのファイルは存在しないか、適切なパーミッションになっていないと思われます。',

	'SecurityException:Codeblock' => "特権コードブロックを実行しょうとしましたが拒否されました。",
	'DatabaseException:WrongCredentials' => "Elggは入力された情報でデータベースに接続することができませんでした。設定ファイルを見なおしてください。",
	'DatabaseException:NoConnect' => "Elggはデータベース「 %s 」を見つけることができませんでした。 データベースが作成済みかどうか、アクセスできるのかどうか確認してください。",
	'SecurityException:FunctionDenied' => "権限の必要な機能「 %s 」へのアクセスが拒否されました。",
	'DatabaseException:DBSetupIssues' => "いくつか問題が発生しました: ",
	'DatabaseException:ScriptNotFound' => "Elggは %s にて要求されたデータベーススクリプトを見つけることができませんでした。",
	'DatabaseException:InvalidQuery' => "クエリーの記述が間違っています。",
	'DatabaseException:InvalidDBLink' => "データベースへの接続が切れました。",

	'IOException:FailedToLoadGUID' => 'GUID:%2$d から、新しい %1$s を読込こもうとしましたが、失敗しました。',
	'InvalidParameterException:NonElggObject' => "非ElggObjectがElggObjectコンストラクタに渡されました！",
	'InvalidParameterException:UnrecognisedValue' => "コンストラクタに認識できない値が渡されました。",

	'InvalidClassException:NotValidElggStar' => "GUID:%d は適切な %s ではありません。",

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) は、設定にミスがあるプラグインですので、起動を無効にしました。起こり得る原因についてはElgg wikiにて検索してみてください。(http://docs.elgg.org/wiki/)",
	'PluginException:CannotStart' => '%s (guid: %s) は起動できず停止状態のままです。理由: %s',
	'PluginException:InvalidID' => "%s は、不正なプラグインIDです。",
	'PluginException:InvalidPath' => "%s は、不正なプラグインのpathです",
	'PluginException:InvalidManifest' => '%s プラグインのマニフェストファイルに間違いがあります。',
	'PluginException:InvalidPlugin' => '%s は、不正なプラグインです。',
	'PluginException:InvalidPlugin:Details' => '%s は、不正なプラグインです。: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin のインスタンスは null であってはいけません。GUID、plugin ID もしくはfull path を渡してください。',

	'ElggPlugin:MissingID' => 'プラグインID (guid %s) が、ありません。',
	'ElggPlugin:NoPluginPackagePackage' => 'プラグインID %s (guid %s) のElggPluginPackage がありません。',

	'ElggPluginPackage:InvalidPlugin:MissingFile' => '必要なファイル "%s" が見つかりません。',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'マニフェストに記述されている依存関係のタイプ "%s" が正しくありません。',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'マニフェストに記述されているプロバイドのタイプ "%s" が正しくありません。',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'プラグイン %3$s で依存関係のタイプ %2$s の "%1$s" が正しくありません。依存関係が循環しています。',

	'ElggPlugin:Exception:CannotIncludeFile' => '%s (プラグイン %s (guid: %s))が %s に含まれていません。パーミッションを調べてください！',
	'ElggPlugin:Exception:CannotRegisterViews' => 'プラグイン %s (guid: %s)のViewディレクトリを %s で開くことができません。パーミッションを調べてください！',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'プラグイン %s (guid: %s)の言語ファイルを%sに登録できません。パーミッションを調べてください！',
	'ElggPlugin:Exception:NoID' => 'プラグイン guid %s のIDがありません！',

	'PluginException:ParserError' => 'API(var. %s)でプラグイン %s のマニフェストを解析するときにエラーが発生しました)。',
	'PluginException:NoAvailableParser' => 'マニフェストAPI(Ver. %s)のパーサをプラグイン%sの中で見つけることができません。',
	'PluginException:ParserErrorMissingRequiredAttribute' => "マニフェストファイル内で'%s'属性が必要なのですがプラグイン%sの中ではありませんでした。",

	'ElggPlugin:Dependencies:Requires' => '必須',
	'ElggPlugin:Dependencies:Suggests' => '示唆',
	'ElggPlugin:Dependencies:Conflicts' => '混乱',
	'ElggPlugin:Dependencies:Conflicted' => '混乱した',
	'ElggPlugin:Dependencies:Provides' => '生成',
	'ElggPlugin:Dependencies:Priority' => '優先',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg version',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP extension: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ind セッティング: %s',
	'ElggPlugin:Dependencies:Plugin' => 'プラグイン:%s',
	'ElggPlugin:Dependencies:Priority:After' => '%s の後',
	'ElggPlugin:Dependencies:Priority:Before' => '%s の前',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s は、インストールされていません',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'ありません',

	'ElggPlugin:InvalidAndDeactivated' => '%s は、不正なプラグインですので停止しました。',

	'InvalidParameterException:NonElggUser' => "非ElggUserオブジェクトがElggUserのコンストラクタに渡されました！",

	'InvalidParameterException:NonElggSite' => "非ElggSiteオブジェクトがElggSiteのコンストラクタに渡されました！",

	'InvalidParameterException:NonElggGroup' => "非ElggGroupがElggGroupのコンストラクタに渡されました！",

	'IOException:UnableToSaveNew' => "新しい %s を保存できません。",

	'InvalidParameterException:GUIDNotForExport' => "エクスポート中にGUIDが記述されていませんでした。このようなことは起こらないはずなのですが。",
	'InvalidParameterException:NonArrayReturnValue' => "エンティティーをシリアライズする関数に非配列の戻り値のパラメータを渡しています。",

	'ConfigurationException:NoCachePath' => "キャッシュのパスが設定されていません。",
	'IOException:NotDirectory' => "%s はディレクトリではありません。",

	'IOException:BaseEntitySaveFailed' => "オブジェクトのベースエンティティの情報を新規保存することができません！",
	'InvalidParameterException:UnexpectedODDClass' => "import() に見当違いなODD classが渡されました。",
	'InvalidParameterException:EntityTypeNotSet' => "Entity タイプをセットしてください。",

	'ClassException:ClassnameNotClass' => "%s は %s ではありません。",
	'ClassNotFoundException:MissingClass' => "クラス「 %s 」がみつかりませんでした。プラグインがないのかも。",
	'InstallationException:TypeNotSupported' => "型「 %s 」はサポートされていません。この場合、インストール時にエラーが発生していたことが考えられます。たいていは、アップグレードが完全にされなかったときに起こるようです。",

	'ImportException:ImportFailed' => "次の要素をインポートできません。: %d",
	'ImportException:ProblemSaving' => "保存中に問題が発生しました。:  %s",

	'ImportException:NoGUID' => "新しくエンティティーが生成されましたが、GUIDが付いていません。これは、ありえないことです。",


	'ImportException:GUIDNotFound' => "エンティティー '%d' を見つけることができませんでした。",
	'ImportException:ProblemUpdatingMeta' => "'%s' (エンティティー %d)の更新時に問題が起こりました。",

	'ExportException:NoSuchEntity' => "そのようなエンティティー(GUID:%id)は、ありません。",

	'ImportException:NoODDElements' => "インポートデータにOpenDD要素が見つかりませんでした。インポートは失敗しました。",
	'ImportException:NotAllImported' => "全ての要素がインポートされませんでした。",

	'InvalidParameterException:UnrecognisedFileMode' => "認識できないファイルモード(%s)です",
	'InvalidParameterException:MissingOwner' => "ファイル %s (file guid:%d) (owner guid:%d) はオーナーが不明です。",
	'IOException:CouldNotMake' => "%s を作成できませんでした。",
	'IOException:MissingFileName' => "ファイル名を入力しないとファイルを開くことができません",
	'ClassNotFoundException:NotFoundNotSavedWithFile' => "Filesstore class %s をファイル %u で使用するにあたって、ロードすることができません。",
	'NotificationException:NoNotificationMethod' => "通知の手段が指定されていません。",
	'NotificationException:NoHandlerFound' => "'%s'に対するハンドラが見つかりません、または、呼び出せませんでした。",
	'NotificationException:ErrorNotifyingGuid' => "%d をお知らせするときにエラーが起こりました。",
	'NotificationException:NoEmailAddress' => "GUID:%d に対して電子メールアドレスを取得することができませんでした。",
	'NotificationException:MissingParameter' => "必要なパラメータがありません。'%s'",

	'DatabaseException:WhereSetNonQuery' => "WHEREが非WhereQueryComponentに含まれています",
	'DatabaseException:SelectFieldsMissing' => "SELECTクエリ文にフィールドが記述されていません",
	'DatabaseException:UnspecifiedQueryType' => "認識できない、もしくは仕様に見当たらないクエリのタイプです",
	'DatabaseException:NoTablesSpecified' => "クエリ文にテーブルが記述されていません",
	'DatabaseException:NoACL' => "クエリでアクセスコントロールが生成されていません",

	'InvalidParameterException:NoEntityFound' => "エンティティーが見つかりませんでした。存在しないか、あなたがアクセスできないかどちらかでしょう。",

	'InvalidParameterException:GUIDNotFound' => "GUID:%s が見つかりませんでした。もしくは、あなたがアクセス権を持っていないかです。",
	'InvalidParameterException:IdNotExistForGUID' => "申し訳ありません、「'%s(guid:%d)」は存在しません。 ",
	'InvalidParameterException:CanNotExportType' => "申し訳ありません。「%s」のエクスポートの方法がわかりません。",
	'InvalidParameterException:NoDataFound' => "データをひとつも見つけることができませんでした。",
	'InvalidParameterException:DoesNotBelong' => "エンティティーに属していません。",
	'InvalidParameterException:DoesNotBelongOrRefer' => "エンティティーに属していないか参照していません。",
	'InvalidParameterException:MissingParameter' => "パラメータがありません。GUIDを生成する必要があります。",
	'InvalidParameterException:LibraryNotRegistered' => '%s は登録されたライブラリではありません。',
	'InvalidParameterException:LibraryNotFound' => '%s ライブラリをロードすることができませんでした(from %s)',

	'APIException:ApiResultUnknown' => "API 結果は未知の型です。このようなことは通常起こりえません。",
	'ConfigurationException:NoSiteID' => "サイトIDが記述されていません",
	'SecurityException:APIAccessDenied' => "申し訳ありません、APIへのアクセスが管理者によって許可されていません。",
	'SecurityException:NoAuthMethods' => "このAPIリクエストを認証できる認証法が見つかりませんでした。",
	'SecurityException:ForwardFailedToRedirect' => 'ヘッダがすでに送られましたので、問題はリダイレクトにはありません。安全性のために、実行を停止しています。ファイル %s の %d 行目より出力を開始します。さらに詳しい情報は、http://docs.elgg.org/ で探してみてください。',
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "expose_method()呼び出しにおいて、メソッドもしくは関数がセットされていませんでした。",
	'InvalidParameterException:APIParametersArrayStructure' => "メソッド「%s」呼び出しにおいて、パラメータ配列構造体が間違っています。",
	'InvalidParameterException:UnrecognisedHttpMethod' => "%s は、APIメソッド「%s」に対して認識されていないhttpメソッドです。",
	'APIException:MissingParameterInMethod' => "パラメータ%s（メソッド%s）がありません。",
	'APIException:ParameterNotArray' => "%sは、配列にしてください。",
	'APIException:UnrecognisedTypeCast' => 'メソッド「%3$s」の変数「%2$s」は%1$sにキャストされていますが、認識でない型です。',
	'APIException:InvalidParameter' => 'メソッド「%2$s」の「%1$s」において不正なパラメータが見つかりました。',
	'APIException:FunctionParseError' => "%s(%s) にパースエラーが生じました",
	'APIException:FunctionNoReturn' => "%s(%s) は値を返しませんでした。",
	'APIException:APIAuthenticationFailed' => "メソッド呼び出しがAPI認識で失敗しました",
	'APIException:UserAuthenticationFailed' => "メソッド呼び出しがユーザ認識で失敗しました。",
	'SecurityException:AuthTokenExpired' => "ユーザ認証のトークンが欠如、不正、期限切れのうちのいづれかです。",
	'CallException:InvalidCallMethod' => "%s は '%s' を用いて呼び出されなければいけません。",
	'APIException:MethodCallNotImplemented' => "メソッドコール '%s' は、実装されていません。",
	'APIException:FunctionDoesNotExist' => "メソッド '%s' の関数は呼び出し不可です。",
	'APIException:AlgorithmNotSupported' => "アルゴリズム '%s' はサーポートされていないか使用不可にされています。",
	'ConfigurationException:CacheDirNotSet' => "キャッシュディレクトリ 'cache_path' が未設定です。",
	'APIException:NotGetOrPost' => "リクエストメソッドは GET か POST でなければなりません。",
	'APIException:MissingAPIKey' => "API キーが欠如しています。",
	'APIException:BadAPIKey' => "API キーが間違っています",
	'APIException:MissingHmac' => "X-Elgg-hmac ヘッダが欠如しています",
	'APIException:MissingHmacAlgo' => "X-Elgg-hmac-algo ヘッダが欠如しています",
	'APIException:MissingTime' => "X-Elgg-time ヘッダが欠如しています",
	'APIException:MissingNonce' => "X-Elgg-nonce ヘッダが欠如しています",
	'APIException:TemporalDrift' => "X-Elgg-time があまりにも遠い過去か未来になっています。エポックが失敗しています。",
	'APIException:NoQueryString' => "クエリ文字列にデータが欠如しています",
	'APIException:MissingPOSTHash' => "X-Elgg-posthash ヘッダが欠如しています",
	'APIException:MissingPOSTAlgo' => "X-Elgg-posthash_algo ヘッダが欠如いています",
	'APIException:MissingContentType' => "投稿データにコンテントタイプが欠如しています",
	'SecurityException:InvalidPostHash' => "POST データハッシュが不正です。 -  %s であるはずなのですが %s 担っています",
	'SecurityException:DupePacket' => "すでにパケットシグネチャを受け取っているのですが、再び現れました。",
	'SecurityException:InvalidAPIKey' => "API キーが不正か欠如しています。",
	'NotImplementedException:CallMethodNotImplemented' => "メソッド '%s' の呼び出しは現在サポートされていません。",

	'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC メソッドコール '%s' は、まだ導入されていません。",
	'InvalidParameterException:UnexpectedReturnFormat' => "メソッド '%s' を呼び出しましたが、予測外の結果が返されてきました。",
	'CallException:NotRPCCall' => "先ほどの呼び出しは正しいXML-RPC呼び出しでは無いようです",

	'PluginException:NoPluginName' => "プラグイン名が見つかりませんでした",

	'SecurityException:authenticationfailed' => "ユーザを認証することができませんでした",

	'CronException:unknownperiod' => '%s は、期間として認識できませんでした。',

	'SecurityException:deletedisablecurrentsite' => 'あなたが現在見ているサイトを削除またはアクセス不可にすることができません',

	'RegistrationException:EmptyPassword' => 'パスワードの項目は空欄のままにはできません',
	'RegistrationException:PasswordMismatch' => 'パスワードは一致させなければなりません',
	'LoginException:BannedUser' => 'あなたはこのサイトに出入り禁止になっていますのでログインできません。',
	'LoginException:UsernameFailure' => 'ログインできません。あなたのログイン名とパスワードをもう一度お確かめください。',
	'LoginException:PasswordFailure' => 'ログインでできません。あなたのログイン名とパスワードをもう一度お確かめください。',
	'LoginException:AccountLocked' => 'ログイン失敗が多いので、あなたのアカウントをロックしています',
	'LoginException:ChangePasswordFailure' => '現在ご使用になられているパスワードのチェックに失敗しました。',
	'LoginException:Unknown' => '不明なエラーがおこりましたので、ログインできませんでした。',

	'deprecatedfunction' => '警告: このコードは廃止された時代遅れの関数「 %s 」を使用しており、このバージョンのElggとは互換性がありません。',

	'pageownerunavailable' => '警告： ページオーナー %d を許可できません。',
	'viewfailure' => 'View %s において内部エラーが発生しました。',
	'changebookmark' => 'このページに対するあなたのブックマークを変更してください。',
	'noaccess' => 'ログインしないとコンテントを閲覧することはできません。あるいは、閲覧しようとしているこのコンテントはすでに削除されてしまっているか、あなたに閲覧する権限がないかです。',
	'error:missing_data' => 'あなたのリクエストにおいていくつかデータの欠損がありました。',

	'error:default' => 'おっと、なにかうまくいきませんでした。',
	'error:404' => '申し訳ありません、あなたがリクエストしたページを見つけることができませんでした。',

/**
 * API
 */
	'system.api.list' => "システムで利用可能な全てのAPIコールのリスト",
	'auth.gettoken' => "このAPIコールでユーザは認証トークンを取得できます。この認証トークンは、これから実装が予定されている認証APIコールに使用することができます。auth_tokenの引数のパラメータに使用してください。",

/**
 * User details
 */

	'name' => "名前",
	'email' => "電子メール",
	'username' => "ログイン名",
	'loginusername' => "ログイン名もしくは電子メール",
	'password' => "パスワード",
	'passwordagain' => "パスワード（確認）",
	'admin_option' => "このユーザに管理者権限を与える",

/**
 * Access
 */

	'PRIVATE' => "本人のみ",
	'LOGGED_IN' => "ログインユーザのみ",
	'PUBLIC' => "公開",
	'access:friends:label' => "友達のみ",
	'access' => "公開範囲",
	'access:limited:label' => "限定公開",
	'access:help' => "コンテンツの公開範囲を設定します。",

/**
 * Dashboard and widgets
 */

	'dashboard' => "ダッシュボード",
	'dashboard:nowidgets' => "ダッシュボードでは、あなたのアクティビティやこのサイトでのあなたに関するコンテンツを表示させることができます。",

	'widgets:add' => 'ウィジェットを追加',
	'widgets:add:description' => "下のウィジェットボタンをクリックして、ページに追加してみてください。",
	'widgets:position:fixed' => '（固定した位置）',
	'widget:unavailable' => 'すでに、このウィジェットを追加済みです。',
	'widget:numbertodisplay' => '表示するアイテムの数',

	'widget:delete' => '%s を削除',
	'widget:edit' => 'このウィジェットをカスタマイズする',

	'widgets' => "ウィジェット",
	'widget' => "ウィジェット",
	'item:object:widget' => "ウィジェット",
	'widgets:save:success' => "ウィジェットを保存しました。",
	'widgets:save:failure' => "ウィジェットを保存できませんでした。",
	'widgets:add:success' => "ウィジェットを追加しました。",
	'widgets:add:failure' => "あなたのウィジェットを追加できませんでした。",
	'widgets:move:failure' => "ウィジェットを新しい場所に移動できませんでした。",
	'widgets:remove:failure' => "このウィジェットを削除することができませんでした",

/**
 * Groups
 */

	'group' => "グループ",
	'item:group' => "グループ",

/**
 * Users
 */

	'user' => "ユーザ",
	'item:user' => "ユーザ",

/**
 * Friends
 */

	'friends' => "友達",
	'friends:yours' => "あなたの友達",
	'friends:owned' => "%sさんの友達",
	'friend:add' => "友達登録する",
	'friend:remove' => "友達登録を解除する",

	'friends:add:successful' => "%s さんを友達登録しました。",
	'friends:add:failure' => "%s さんを友達登録できませんでした。",

	'friends:remove:successful' => "%s さんの友達登録を解除しました。",
	'friends:remove:failure' => "%s さんの友達登録を解除できませんでした。",

	'friends:none' => "友達登録はありません。",
	'friends:none:you' => "あなたはまだ誰も友達登録していません。",

	'friends:none:found' => "友達が見つかりませんでした。",

	'friends:of:none' => "まだ誰もこのユーザを友達として登録していません。",
	'friends:of:none:you' => "誰もあなたを友達登録していません。コンテンツを追加したりプロフィール欄を埋めて目立ちましょう！",

	'friends:of:owned' => "%s さんを友達登録しているメンバ",

	'friends:of' => "このユーザを友達登録しているメンバ",
	'friends:collections' => "友達リスト",
	'collections:add' => "新しい友達リスト",
	'friends:collections:add' => "新規友達リストの作成",
	'friends:addfriends' => "友達を選んでください",
	'friends:collectionname' => "リストの名前",
	'friends:collectionfriends' => "リストに登録された友達",
	'friends:collectionedit' => "リストの編集",
	'friends:nocollections' => "リストがありません。",
	'friends:collectiondeleted' => "リストを削除しました。",
	'friends:collectiondeletefailed' => "リストが削除できません。権限がないか、何らかの問題が発生しています。",
	'friends:collectionadded' => "リストを作成しました。",
	'friends:nocollectionname' => "リストの名前を入力してください。",
	'friends:collections:members' => "リストのメンバ",
	'friends:collections:edit' => "リストの編集",
	'friends:collections:edited' => "保存したリスト",
	'friends:collection:edit_failed' => 'リストを保存できませんでした。',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'アバター',
	'avatar:create' => 'アバターを作る',
	'avatar:edit' => 'アバターを編集する',
	'avatar:preview' => 'プレビュー',
	'avatar:upload' => '新しいアバターをアップロードする',
	'avatar:current' => '現在使用中のアバター',
	'avatar:remove' => 'アバターを削除して、デフォルトのアイコンに戻す',
	'avatar:crop:title' => 'アバターの切り貼りツール',
	'avatar:upload:instructions' => "あなたのアバターは、このサイト内であなたの顔写真として表示されます。好きな時に変更することができます。(ファイルの形式はPNG,JPG,GIFのいずれかでお願いします)",
	'avatar:create:instructions' => "アバターに使用する画像の大きさををマウスで調整してください。加工された後がどのような感じになるか右のボックスにプレビューとして表示されます。決まったら「アバターを作成する」ボタンを押してください。加工後の画像はアバターとしてこのサイトで表示されます。",
	'avatar:upload:success' => 'アバター画像は無事アップロードされました。',
	'avatar:upload:fail' => 'アバター画像のアップロードに失敗しました',
	'avatar:resize:fail' => 'アバター画像の大きさ変更に失敗しました',
	'avatar:crop:success' => 'アバター画像の切り取りに成功しました',
	'avatar:crop:fail' => 'アバター画像の切り取りに失敗しました',
	'avatar:remove:success' => 'アバターを無事削除しました',
	'avatar:remove:fail' => 'アバターをの削除に失敗しました。',

	'profile:edit' => 'プロフィールを編集',
	'profile:aboutme' => "自己紹介",
	'profile:description' => "自己紹介",
	'profile:briefdescription' => "ちょっと一言",
	'profile:location' => "住所・地域",
	'profile:skills' => "特技",
	'profile:interests' => "趣味",
	'profile:contactemail' => "電子メール",
	'profile:phone' => "電話番号",
	'profile:mobile' => "携帯番号",
	'profile:website' => "Website",
	'profile:twitter' => "Twitterユーザ名",
	'profile:saved' => "プロフィールデータを保存しました",

	'profile:field:text' => '短文',
	'profile:field:longtext' => '長文',
	'profile:field:tags' => 'タグ',
	'profile:field:url' => 'Webアドレス',
	'profile:field:email' => 'Emailアドレス',
	'profile:field:location' => '地域・場所',
	'profile:field:date' => '日付',

	'admin:appearance:profile_fields' => 'プロフィール項目を編集',
	'profile:edit:default' => 'プロフィール項目を編集',
	'profile:label' => "プロフィールのラベル",
	'profile:type' => "プロフィールのタイプ",
	'profile:editdefault:delete:fail' => 'デフォルトのプロフィール項目を削除するのに失敗しました！',
	'profile:editdefault:delete:success' => 'デフォルトのプロフィール項目を削除しました！',
	'profile:defaultprofile:reset' => 'デフォルトのシステムプロフィールにリセット',
	'profile:resetdefault' => 'プロフィールの項目をシステムのデフォルトにリセットする',
	'profile:resetdefault:confirm' => 'カスタムプロフィールの項目を削除してもよろしいですか？',
	'profile:explainchangefields' => "既存のプロフィールフィールドをあなた独自のものに置き換えることができます。\n\n 新しいプロフィール項目にラベルを付けてください。たとえば、「好きなチーム」など。次に、プロフィールタイプ(text, url, tagなど)を選択して「追加」ボタンを押してください。順番を並び替えるには、ラベルの右にあるハンドルをマウスでつまんで調整してください。フィールドのラベルを編集するにはラベルをマウスでクリックしてください。\n\n いつでもデフォルトに戻すことができますが、あなた独自に作ったの項目を削除した場合、そこに入っていた値は失われてしまうでしょう。",
	'profile:editdefault:success' => 'デフォルトプロフィールに項目を追加しました',
	'profile:editdefault:fail' => 'デフォルトプロフィールを保存できませんでした。',
	'profile:field_too_long' => '"%s" セクションが長すぎるので、あなたのプロフィール情報を保存することができません。',
	'profile:noaccess' => "あなたには、このプロフィールを編集する権限がありません。",

/**
 * Feeds
 */
	'feed:rss' => 'このページをRSSフィードする',
/**
 * Links
 */
	'link:view' => 'リンクを見る',
	'link:view:all' => '全て見る',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%sさんは、%sさんと友達になりました",
	'river:update:user:avatar' => '%sさんが、新しいアバターを設定しました',
	'river:update:user:profile' => '%sさんがプロフィールを更新しました',
	'river:noaccess' => 'このアイテムを見る権限がありません。',
	'river:posted:generic' => '%sさんが投稿しました。',
	'riveritem:single:user' => 'ユーザ',
	'riveritem:plural:user' => 'ユーザ',
	'river:ingroup' => '%sグループ内',
	'river:none' => '近況報告はありません',
	'river:update' => '%s さんの更新',
	'river:delete:success' => 'River 項目を削除しました。',
	'river:delete:fail' => 'River 項目は削除できませんでした。',

	'river:widget:title' => '近況報告',
	'river:widget:description' => "最新の近況報告を表示",
	'river:widget:type' => "近況報告のタイプ",
	'river:widgets:friends' => '友達の近況報告',
	'river:widgets:all' => '全近況報告',

/**
 * Notifications
 */
	'notifications:usersettings' => "通知設定",
	'notifications:methods' => "通知の方法を選んで下さい。",
	'notification:method:email' => 'Email',

	'notifications:usersettings:save:ok' => "通知設定を保存しました。",
	'notifications:usersettings:save:fail' => "通知設定の保存に失敗しました。",

	'user.notification.get' => '特定のユーザへの通知の設定を表示します。',
	'user.notification.set' => '特定のユーザへの通知について設定してください。',
/**
 * Search
 */

	'search' => "検索",
	'searchtitle' => "検索: %s",
	'users:searchtitle' => "ユーザ検索: %s",
	'groups:searchtitle' => "グループ検索: %s",
	'advancedsearchtitle' => "%s(%sと一致)",
	'notfound' => "検索結果なし",
	'next' => "次へ",
	'previous' => "前へ",

	'viewtype:change' => "表示の仕方の変更",
	'viewtype:list' => "リスト",
	'viewtype:gallery' => "ギャラリ",

	'tag:search:startblurb' => "「%s」と一致したタグは:",

	'user:search:startblurb' => "「%s」と一致したユーザ:",
	'user:search:finishblurb' => "もっとみる",

	'group:search:startblurb' => "「%s」と一致したグループは:",
	'group:search:finishblurb' => "もっとみる",
	'search:go' => 'Go',
	'userpicker:only_friends' => '友達のみ',

/**
 * Account
 */

	'account' => "アカウント",
	'settings' => "設定",
	'tools' => "ツール",
	'settings:edit' => '設定を編集',

	'register' => "新規登録",
	'registerok' => "あなたは %s で登録されました。",
	'registerbad' => "未知のエラーのため、登録作業が失敗しました。",
	'registerdisabled' => "システム管理者が新規登録を禁止しています。",
	'register:fields' => 'すべての項目が必須となります。',

	'registration:notemail' => 'あなたが入力したEメールアドレスは、正しいものでは無いようです。',
	'registration:userexists' => 'そのログイン名はすでに使われています。',
	'registration:usernametooshort' => 'ログイン名は半角英字で %u 文字以上にしてください。',
	'registration:usernametoolong' => 'あなたのログイン名は長過ぎます。 %u 文字以内でお願いします。',
	'registration:passwordtooshort' => 'パスワードは半角英字で %u 文字以上にしてください。',
	'registration:dupeemail' => 'そのEメールアドレスはすでに利用されています。',
	'registration:invalidchars' => '申し訳ありません。入力されたログイン名には利用できない文字「 %s 」が含まれています。次のこれらの文字は使えません: %s',
	'registration:emailnotvalid' => '申し訳ありません。入力されたEメールアドレスは、このシステムで使えません。',
	'registration:passwordnotvalid' => '申し訳ありません。入力されたパスワードは、このシステムで使えません。',
	'registration:usernamenotvalid' => '申し訳ありません。入力されたログイン名は、このシステムで使えません。',

	'adduser' => "ユーザ登録",
	'adduser:ok' => "新しいユーザを登録しました。",
	'adduser:bad' => "新しいユーザが登録できません。",

	'user:set:name' => "アカウント編集",
	'user:name:label' => "氏名", // （＊）Display name(ログイン時に必要なログイン名usernameと違ってサイトで実際に表示される名前)のこと。各サイトの都合で変更してください。
	'user:name:success' => "氏名を変更しました。",
	'user:name:fail' => "氏名を変更できません。長すぎた可能性があります。もう一度試してみてください。",

	'user:set:password' => "パスワード",
	'user:current_password:label' => '現在のパスワード',
	'user:password:label' => "新しいパスワード",
	'user:password2:label' => "新しいパスワード（確認）",
	'user:password:success' => "パスワードを変更しました。",
	'user:password:fail' => "パスワードが変更できませんでした。",
	'user:password:fail:notsame' => "パスワードが一致しません。",
	'user:password:fail:tooshort' => "パスワードが短すぎるので登録できません。",
	'user:password:fail:incorrect_current_password' => '先ほど入力されたパスワードは間違っています。',
	'user:resetpassword:unknown_user' => 'ユーザが見当たりません。',
	'user:resetpassword:reset_password_confirm' => '登録されたEメールアドレスに新しいパスワードを送信しました。',

	'user:set:language' => "言語設定",
	'user:language:label' => "利用する言語",
	'user:language:success' => "利用する言語設定を更新しました。",
	'user:language:fail' => "言語設定が保存できません。",

	'user:username:notfound' => 'ログイン名 %s が見当たりません。',

	'user:password:lost' => 'パスワードを忘れた場合',
	'user:password:resetreq:success' => '新しいパスワード発行の手続きをしました。ご登録のEメールあてに確認のメールを送信しました。',
	'user:password:resetreq:fail' => '新しいパスワード発行の手続きに失敗しました。',

	'user:password:text' => '新しいパスワードを再発行されたい場合は、ログイン名もしくは電子メールアドレスを入力し送信ボタンを押してください。',

	'user:persistent' => '次回入力を省略',

	'walled_garden:welcome' => 'Welcome to',

/**
 * Administration
 */
	'menu:page:header:administer' => '管理業務',
	'menu:page:header:configure' => '設定',
	'menu:page:header:develop' => '開発',
	'menu:page:header:default' => 'その他',

	'admin:view_site' => 'サイトを見る',
	'admin:loggedin' => '%s でログイン中',
	'admin:menu' => 'メニュー',

	'admin:configuration:success' => "設定を保存しました。",
	'admin:configuration:fail' => "設定を保存できませんでした。",
	'admin:configuration:dataroot:relative_path' => '「 %s 」をデータルートとして仕様出来ません：絶対パスを使用してください。',

	'admin:unknown_section' => '不正な管理セクションです',

	'admin' => "管理業務",
	'admin:description' => "この管理パネルでは、ユーザの管理からプラグインの振る舞いにいたるまで、システムの全ての事柄をコントロールすることができます。開始するには以下のオプションを選択してください。",

	'admin:statistics' => '統計情報',
	'admin:statistics:overview' => '概要',
	'admin:statistics:server' => 'サーバの情報',

	'admin:appearance' => '見た目',
	'admin:administer_utilities' => 'ユーティリティ',
	'admin:develop_utilities' => 'ユーティリティ',

	'admin:users' => "ユーザ",
	'admin:users:online' => 'オンライン中',
	'admin:users:newest' => '最新',
	'admin:users:admins' => '管理者',
	'admin:users:add' => '新規ユーザ追加',
	'admin:users:description' => "この管理者パネルでサイト内でのユーザの設定をコントロールすることができます。開始するには、下のオプションを選択してください。",
	'admin:users:adduser:label' => "新規ユーザを追加するには、ここをクリック...",
	'admin:users:opt:linktext' => "ユーザ設定...",
	'admin:users:opt:description' => "ユーザとアカウント情報の設定",
	'admin:users:find' => '検索',

	'admin:settings' => 'セッティング',
	'admin:settings:basic' => '基本設定',
	'admin:settings:advanced' => '詳細設定',
	'admin:site:description' => "この管理パネルでは、インストールしたサイト全体に関わる設定をコントロールすることができます。はじめるには、以下のオプションを選択してください。",
	'admin:site:opt:linktext' => "サイトの構築..",
	'admin:site:access:warning' => "公開範囲の変更は、これから作成されるコンテンツにのみに適用されます。（作成済みのコンテンツには影響しません）",

	'admin:dashboard' => 'ダッシュボード',
	'admin:widget:online_users' => 'オンライン中のユーザ',
	'admin:widget:online_users:help' => '現在サイトにいるユーザのリスト',
	'admin:widget:new_users' => '新規ユーザ',
	'admin:widget:new_users:help' => '新規ユーザのリスト',
	'admin:widget:content_stats' => 'コンテントの統計情報',
	'admin:widget:content_stats:help' => 'ユーザが作成したコンテントの記録を保存しています。',
	'widget:content_stats:type' => 'コンテントのタイプ',
	'widget:content_stats:number' => '件数',

	'admin:widget:admin_welcome' => 'Welcome',
	'admin:widget:admin_welcome:help' => "Elggの管理エリアについての短い紹介",
	'admin:widget:admin_welcome:intro' =>
'Elggにようこそ！現在あなたは管理業務のダッシュボードを見ていると思います。このページはサイトで何がおっこっているかを追跡するのに便利なようにできています。',

	'admin:widget:admin_welcome:admin_overview' =>
"管理エリアのナビゲーションは右側のメニューにあり、3節で構成されています:
	<dl>
			<dt>管理業務</dt><dd>報告コンテントの監視、誰がオンラインしているか、統計情報を見るなど日常の業務</dd>
			<dt>構成設定</dt><dd>サイト名の設定やプラグインの起動など、たまにしか行わない業務</dd>
			<dt>開発</dt><dd>プラグイン作成やテーマデザインなどの開発者向けの項目。（developerプラグインが必要）</dd>
	</dl>",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />フッタリンクに’使える’リソースがありますので、チェックしてみてください。Elggをご使用いただき、誠にありがとうございました。',

	'admin:widget:control_panel' => 'コントロールパネル',
	'admin:widget:control_panel:help' => "簡単に各種設定を行うことができます。",

	'admin:cache:flush' => 'キャッシュをクリアする',
	'admin:cache:flushed' => "サイトのキャシュをクリアしました",

	'admin:footer:faq' => '管理業務FAQ',
	'admin:footer:manual' => '管理業務マニュアル',
	'admin:footer:community_forums' => 'Elggコミュニティーフォーラム',
	'admin:footer:blog' => 'Elggブログ',

	'admin:plugins:category:all' => '全プラグイン',
	'admin:plugins:category:active' => '起動中のプラグイン',
	'admin:plugins:category:inactive' => '停止中のプラグイン',
	'admin:plugins:category:admin' => '管理者',
	'admin:plugins:category:bundled' => 'Bundled',
	'admin:plugins:category:nonbundled' => 'Non-bundled',
	'admin:plugins:category:content' => 'コンテント',
	'admin:plugins:category:development' => '開発',
	'admin:plugins:category:enhancement' => '機能拡張',
	'admin:plugins:category:api' => 'サービスAPI',
	'admin:plugins:category:communication' => 'コミュニケーション',
	'admin:plugins:category:security' => 'セキュリティーとスパム',
	'admin:plugins:category:social' => 'ソーシャル',
	'admin:plugins:category:multimedia' => 'マルチメディア',
	'admin:plugins:category:theme' => 'テーマ',
	'admin:plugins:category:widget' => 'ウィジェット',
	'admin:plugins:category:utility' => 'ユーティリティ',

	'admin:plugins:sort:priority' => '優先順位',
	'admin:plugins:sort:alpha' => 'アルファベット順',
	'admin:plugins:sort:date' => '最新',

	'admin:plugins:markdown:unknown_plugin' => '不明なプラグイン',
	'admin:plugins:markdown:unknown_file' => '不明なファイル',


	'admin:notices:could_not_delete' => '通知を消去することができませんでした。',
	'item:object:admin_notice' => '通知の管理',

	'admin:options' => '管理者オプション',


/**
 * Plugins
 */
	'plugins:disabled' => '「disabled」というファイルがmodディレクトリにありますので、プラグインらを読み込みこんでおりません。',
	'plugins:settings:save:ok' => "プラグイン %s のセッティングを保存しました。",
	'plugins:settings:save:fail' => "プラグイン %s のセッティングを保存する際に問題が発生しました",
	'plugins:usersettings:save:ok' => "プラグイン %s のユーザセッティングを保存しました。",
	'plugins:usersettings:save:fail' => "プラグイン %s のユーザセッティングを保存する際に問題が発生しました",
	'item:object:plugin' => 'プラグイン',

	'admin:plugins' => "プラグイン管理",
	'admin:plugins:activate_all' => '全て起動する',
	'admin:plugins:deactivate_all' => 'すべて停止する',
	'admin:plugins:activate' => '起動',
	'admin:plugins:deactivate' => '停止',
	'admin:plugins:description' => "この管理パネルでは、インストールしたツールの管理や構築設定を行います。",
	'admin:plugins:opt:linktext' => "ツールの設定...",
	'admin:plugins:opt:description' => "インストールされたツールを構築するための各種設定をします",
	'admin:plugins:label:author' => "開発者",
	'admin:plugins:label:copyright' => "コピーライト",
	'admin:plugins:label:categories' => 'カテゴリ',
	'admin:plugins:label:licence' => "ライセンス",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "問題を報告する",
	'admin:plugins:label:donate' => "寄付する",
	'admin:plugins:label:moreinfo' => '詳細情報',
	'admin:plugins:label:version' => "バージョン",
	'admin:plugins:label:location' => '場所',
	'admin:plugins:label:dependencies' => '依存関係',

	'admin:plugins:warning:elgg_version_unknown' => 'このプラグインは、旧のマニフェストファイルを使用していますので互換性のあるElggバージョンを記載していません。おそらく、うまく作動しないでしょう。',
	'admin:plugins:warning:unmet_dependencies' => 'このプラグインは依存関係が不適切なので起動できません。詳細情報で依存関係をチェックしてください。',
	'admin:plugins:warning:invalid' => 'このプラグインは正しくありません: %s',
	'admin:plugins:warning:invalid:check_docs' => '問題解決には <a href="http://docs.elgg.org/Invalid_Plugin">the Elgg documentation</a> をチェックしてみてください。',
	'admin:plugins:cannot_activate' => '起動できません',

	'admin:plugins:set_priority:yes' => "%s を並べ直しました。",
	'admin:plugins:set_priority:no' => "%s を並べ直せませんでした。",
	'admin:plugins:set_priority:no_with_msg' => "%s を並べ直せませんでした。Error: %s",
	'admin:plugins:deactivate:yes' => "%s を停止状態にしました。",
	'admin:plugins:deactivate:no' => "%s を停止できませんでした。",
	'admin:plugins:deactivate:no_with_msg' => "%s を停止できませんでした。Error: %s",
	'admin:plugins:activate:yes' => "%s を起動状態にしました。",
	'admin:plugins:activate:no' => "% sを起動できませんでした。",
	'admin:plugins:activate:no_with_msg' => "%s を起動できませんでした。Error: %s",
	'admin:plugins:categories:all' => '全てのカテゴリ',
	'admin:plugins:plugin_website' => 'プラグインのウェブサイト',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugin_settings' => 'プラグインの設定',
	'admin:plugins:warning:unmet_dependencies_active' => 'このプラグインは起動状態ですが、依存関係に問題があります。下の"詳細情報"をチェックしてください。',

	'admin:plugins:dependencies:type' => 'タイプ',
	'admin:plugins:dependencies:name' => '名前',
	'admin:plugins:dependencies:expected_value' => '推奨値',
	'admin:plugins:dependencies:local_value' => '実際の値',
	'admin:plugins:dependencies:comment' => 'コメント',

	'admin:statistics:description' => "これはあなたのサイトの大ざぱな統計情報です。更に詳細な統計情報が必要なときは、専門的な管理機能をご利用ください。",
	'admin:statistics:opt:description' => "サイト上のユーザとオブジェクトに関する統計情報を表示します。",
	'admin:statistics:opt:linktext' => "統計情報をみる...",
	'admin:statistics:label:basic' => "サイト統計情報（概要）",
	'admin:statistics:label:numentities' => "サイト統計情報（数値）",
	'admin:statistics:label:numusers' => "ユーザ数",
	'admin:statistics:label:numonline' => "ログイン中のユーザ数",
	'admin:statistics:label:onlineusers' => "ログイン中のユーザ",
	'admin:statistics:label:admins'=>"管理者",
	'admin:statistics:label:version' => "Elgg バージョン",
	'admin:statistics:label:version:release' => "リリース",
	'admin:statistics:label:version:version' => "バージョン",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Webサーバ',
	'admin:server:label:server' => 'サーバ',
	'admin:server:label:log_location' => 'ログ記録の保存場所',
	'admin:server:label:php_version' => 'PHP version',
	'admin:server:label:php_ini' => 'PHP ini ファイルの場所',
	'admin:server:label:php_log' => 'PHP ログ',
	'admin:server:label:mem_avail' => 'メモリの利用可能量',
	'admin:server:label:mem_used' => 'メモリの使用量',
	'admin:server:error_log' => "Web サーバのエラーログ",
	'admin:server:label:post_max_size' => '最大 POST サイズ',
	'admin:server:label:upload_max_filesize' => '最大アップロードサイズ',
	'admin:server:warning:post_max_too_small' => '(注: このサイズは、post_max_size よりも小さくなければなりません。)',

	'admin:user:label:search' => "ユーザ検索",
	'admin:user:label:searchbutton' => "検索",

	'admin:user:ban:no' => "ユーザを禁止できません。",
	'admin:user:ban:yes' => "ユーザの出入りを禁止",
	'admin:user:self:ban:no' => "自分自身を入場禁止にすることはできません。",
	'admin:user:unban:no' => "入場禁止措置を解除することができませんでした。",
	'admin:user:unban:yes' => "ユーザの入場禁止措置を解除しました。",
	'admin:user:delete:no' => "ユーザを削除することができませんでした。",
	'admin:user:delete:yes' => "%s さんを除名（削除）しました。",
	'admin:user:self:delete:no' => "自分自身を除名（削除）することはできません。",

	'admin:user:resetpassword:yes' => "パスワードをリセットして、ユーザに通知します。",
	'admin:user:resetpassword:no' => "パスワードはリセットできませんでした。",

	'admin:user:makeadmin:yes' => "このユーザに管理者権限を与えました。",
	'admin:user:makeadmin:no' => "このユーザに管理者権限を与えることができませんでした。",

	'admin:user:removeadmin:yes' => "ユーザはもう管理者権限を持っていません。",
	'admin:user:removeadmin:no' => "このユーザの管理者権限が解除できませんでした。",
	'admin:user:self:removeadmin:no' => "自分自身の管理者権限を削除することはできません。",

	'admin:appearance:menu_items' => 'メニュー項目',
	'admin:menu_items:configure' => 'メインメニュー項目の構築設定',
	'admin:menu_items:description' => 'どのメニューアイテムをfeaturedリンクとして表示したいのかを選択してください。使用しない項目は、メニューリストの最後の"More"以下に追加されます。',
	'admin:menu_items:hide_toolbar_entries' => 'ツールバーメニューからリンクを削除する。',
	'admin:menu_items:saved' => 'メニュー項目を保存しました。',
	'admin:add_menu_item' => 'カスタムメニュー項目を追加する',
	'admin:add_menu_item:description' => 'ナビゲーションメニューにカスタム項目を追加するため、表示名とURLを欄に入れててください。',

	'admin:appearance:default_widgets' => 'デフォルト ウィジェット',
	'admin:default_widgets:unknown_type' => '不明なウィジェットのタイプです。',
	'admin:default_widgets:instructions' => '選択したウィジェットページに既定のウィジェットを追加、削除、配置変更、設定変更します。ここでした変更はこのサイトの新規ユーザのみに反映されます。',

/**
 * User settings
 */
	'usersettings:description' => "ユーザセッティングパネルを使うと、ユーザマネージメントからプラグインの振る舞い方まで、あなたの個人的な設定の全てを管理することができます。開始するには、以下のオプションを選択してください。",

	'usersettings:statistics' => "あなたの統計情報",
	'usersettings:statistics:opt:description' => "サイト上のユーザとオブジェクトに関する統計情報を表示します。",
	'usersettings:statistics:opt:linktext' => "アカウントの統計情報",

	'usersettings:user' => "あなたの設定",
	'usersettings:user:opt:description' => "ユーザ設定の管理を行います。",
	'usersettings:user:opt:linktext' => "設定の変更",

	'usersettings:plugins' => "ツール",
	'usersettings:plugins:opt:description' => "あなたの起動したツール（もしあれば）の設定をします",
	'usersettings:plugins:opt:linktext' => "ツールの設定をする",

	'usersettings:plugins:description' => "このパネルでは、システム管理者がインストールしたツールの個人的なコントロールや設定をすることができます。",
	'usersettings:statistics:label:numentities' => "コンテント",

	'usersettings:statistics:yourdetails' => "詳細",
	'usersettings:statistics:label:name' => "氏名",
	'usersettings:statistics:label:email' => "Eメール",
	'usersettings:statistics:label:membersince' => "利用開始時期",
	'usersettings:statistics:label:lastlogin' => "前回のログイン",

/**
 * Activity river
 */
	'river:all' => '全アクティビティ',
	'river:mine' => 'My アクティビティ',
	'river:friends' => '友達のアクティティ',
	'river:select' => '表示:%s',
	'river:comments:more' => '+%u more',
	'river:generic_comment' => 'commented on %s %s',

	'friends:widget:description' => "友達を何人か表示",
	'friends:num_display' => "表示する友達の人数",
	'friends:icon_size' => "アイコンのサイズ",
	'friends:tiny' => "tiny",
	'friends:small' => "small",

/**
 * Generic action words
 */

	'save' => "保存",
	'reset' => 'リセット',
	'publish' => "公開",
	'cancel' => "キャンセル",
	'saving' => "保存中...",
	'update' => "更新",
	'preview' => "プレビュー",
	'edit' => "編集",
	'delete' => "削除",
	'accept' => "承認する",
	'load' => "読込",
	'upload' => "アップロード",
	'ban' => "投稿禁止",
	'unban' => "投稿禁止解除",
	'banned' => "入場禁止",
	'enable' => "有効にする",
	'disable' => "無効にする",
	'request' => "リクエスト",
	'complete' => "完了",
	'open' => "開く",
	'close' => '閉じる',
	'reply' => "返信",
	'more' => 'More',
	'comments' => 'コメント',
	'import' => 'インポート',
	'export' => 'エクスポート',
	'untitled' => 'タイトルなし',
	'help' => 'ヘルプ',
	'send' => '送信',
	'post' => '投稿',
	'submit' => '送信',
	'comment' => 'コメント',
	'upgrade' => 'アップグレード',
	'sort' => '並び替え',
	'filter' => 'フィルタ',
	'new' => '新規',
	'add' => '追加',
	'create' => '作成',
	'remove' => '削除',
	'revert' => '戻す',

	'site' => 'サイト',
	'activity' => 'アクティビティ',
	'members' => 'メンバ',

	'up' => '上へ',
	'down' => '下へ',
	'top' => '最初',
	'bottom' => '最後',
	'back' => '後へ',

	'invite' => "招待する",

	'resetpassword' => "パスワードをリセットする",
	'makeadmin' => "管理者権限を与える",
	'removeadmin' => "管理者権限を外す",

	'option:yes' => "はい",
	'option:no' => "いいえ",

	'unknown' => 'よくわからない',

	'active' => 'Active',
	'total' => '総数',

	'learnmore' => "詳細はここをクリック",

	'content' => "コンテント",
	'content:latest' => '最新のアクティビティ',
	'content:latest:blurb' => 'もしくは、ここをクリックしてサイト全体での新しい記事を見る',

	'link:text' => 'リンク一覧',
/**
 * Generic questions
 */

	'question:areyousure' => 'よろしいですか？',

/**
 * Generic data words
 */

	'title' => "タイトル",
	'description' => "説明",
	'tags' => "タグ",
	'spotlight' => "スポットライト",
	'all' => "全部",
	'mine' => "自分の",

	'by' => 'by',
	'none' => 'none',

	'annotations' => "注釈",
	'relationships' => "関連",
	'metadata' => "メタデータ",
	'tagcloud' => "タグクラウド",
	'tagcloud:allsitetags' => "全タグ",

	'on' => 'On',
	'off' => 'Off',

/**
 * Entity actions
 */
	'edit:this' => 'これを編集',
	'delete:this' => 'これを削除',
	'comment:this' => 'コメントをつける',

/**
 * Input / output strings
 */

	'deleteconfirm' => "このアイテムを削除してよいですか？",
	'deleteconfirm:plural' => "これらのアイテムを削除してもよろしいですか？",
	'fileexists' => "ファイルはすでにアップロードされています。置き換えるときは以下から選択してください:",

/**
 * User add
 */

	'useradd:subject' => 'ユーザを作成しました。',
	'useradd:body' => '
%s 様,

%s にあなたのメールアドレスでユーザアカウントが登録されました。 ログインするには、以下のURLにアクセスしてください:

%s

ログインのためのユーザ名とパスワードは次の通りです:

ログイン名: %s
パスワード: %s

ログイン後は、できるだけ早くにご自身でパスワードを変更することをおすすめします。
',

/**
 * System messages
 **/

	'systemmessages:dismiss' => "クリックすると消えます。",


/**
 * Import / export
 */
	'importsuccess' => "データのインポートに成功しました。",
	'importfail' => "OpenDDデータのインポートに失敗しました。",

/**
 * Time
 */

	'friendlytime:justnow' => "Now!",
	'friendlytime:minutes' => "%s 分前",
	'friendlytime:minutes:singular' => "1 分前",
	'friendlytime:hours' => "%s 時間前",
	'friendlytime:hours:singular' => "1 時間前",
	'friendlytime:days' => "%s 日前",
	'friendlytime:days:singular' => "昨日",
	'friendlytime:date_format' => 'Y年m月d日@ H:i',

	'date:month:01' => '1月 %s',
	'date:month:02' => '2月 %s',
	'date:month:03' => '3月 %s',
	'date:month:04' => '4月 %s',
	'date:month:05' => '5月 %s',
	'date:month:06' => '6月 %s',
	'date:month:07' => '7月 %s',
	'date:month:08' => '8月 %s',
	'date:month:09' => '9月 %s',
	'date:month:10' => '10月 %s',
	'date:month:11' => '11月 %s',
	'date:month:12' => '12月 %s',


/**
 * System settings
 */

	'installation:sitename' => 'あなたのサイト名:',
	'installation:sitedescription' => "あなたのサイトのちょっとした説明（任意）:",
	'installation:wwwroot' => "サイトのURL",
	'installation:path' => "Elggのインストール先のフルパス",
	'installation:dataroot' => "データディレクトリのフルパス",
	'installation:dataroot:warning' => "手作業でこのディレクトリを作成しないといけません。Elggのインストールしたディレクトリと別のところのほうがいいでしょう。",
	'installation:sitepermissions' => "デフォルトのアクセス権限",
	'installation:language' => "サイトのデフォルトの言語",
	'installation:debug' => "デバッグモード（詳しい情報を生成し、エラーを診断するときに使えます。）しかし、システムをスローダウンさせてしまうことがありますので、何か問題が起こった時にだけ使用されることをお勧めします:",
	'installation:debug:none' => 'デバッグモードをOFFにする（推奨）',
	'installation:debug:error' => '致命的なエラーのみ表示する',
	'installation:debug:warning' => 'エラーと警告を表示する',
	'installation:debug:notice' => 'エラーと警告と通告の3つ全部を記録する',

	// Walled Garden support
	'installation:registration:description' => 'ユーザ登録はデフォルトで可能となっています。新規ユーザが勝手に登録できるようにさせたくなければ、OFFにしてください。',
	'installation:registration:label' => '新規ユーザに登録ができるようにする',
	'installation:walled_garden:description' => 'このサイトをプライベートネットワークにする。この設定にすると、ログインしていないユーザは公開指定されているものを除いてサイト内のページを見ることができなくなります。',
	'installation:walled_garden:label' => 'ページをログインユーザ限定にする',

	'installation:httpslogin' => "HTTPS接続越しにユーザをログインさせることができるようにします。ただし、あなたのサイトのサーバがHTTPS接続に対応していないといけません。",
	'installation:httpslogin:label' => "HTTPSログインを可能にする",
	'installation:view' => "あなたのサイトのデフォルトで使用するviewを入力してください。デフォルトviewを使用する場合は、空欄のままにしておいてください。(よくわからない場合は、そのままにしておいてください)",

	'installation:siteemail' => "サイトの電子メールアドレス（システムメールを送信するときに使用します）:",

	'installation:disableapi' => "Elggはwebサービスを構築するときのためにAPIを備えており、リモートアプリケーションがあなたのサイトと通信を可能とします。",
	'installation:disableapi:label' => "ElggのwebサービスAPIを可能にする",

	'installation:allow_user_default_access:description' => "チェックすると、ユーザ個人で自分のデフォルトのアクセスレベルを設定することができます。ユーザが設定した値は、システムの値よりも優先されてしまいます。",
	'installation:allow_user_default_access:label' => "ユーザデフォルトアクセスを可能にする",

	'installation:simplecache:description' => "このsimple cacheは、CSSやJavaScriptなどの静的コンテントをキャッシュすることによって、サイトのパフォーマンスを改善させます。通常、この設定は有効にしておきます。",
	'installation:simplecache:label' => "Simple cache を使う(推奨)",

	'installation:systemcache:description' => "システムキャッシュ機能を使用すると、ファイルのデータをキャッシュすることによって、Elggエンジンの読み込み時間を短縮することができます。",
	'installation:systemcache:label' => "システムキャッシュを使う(推奨)",

	'upgrading' => 'アップグレード中...',
	'upgrade:db' => 'データベースをアップグレードしました。',
	'upgrade:core' => 'Elggをアップグレードしました。',
	'upgrade:unlock' => 'アプグレードのロックを解除する',
	'upgrade:unlock:confirm' => "もうひとつアップグレードがありますのでデータベースをロックします。複数のアップグレードを同時に実行するのは危険です。他のアップグレードがないことをご確認の上作業を継続してください。ロックを解除しますか？",
	'upgrade:locked' => "アップグレードできません。別のアップグレードが実行されています。アップグレードのロックを解除するには、管理セクションに行ってください。",
	'upgrade:unlock:success' => "アップグレードのロックを解除しました。",
'upgrade:unable_to_upgrade' => 'アップグレードできませんでした',
	'upgrade:unable_to_upgrade_info' =>
		'今回のインストールではアップグレードできませんでした。旧バージョンのviewがElggコアviewディレクトリにあるためです。
		これらの旧viewは廃止または削除されたので、そのまま残っていると新Elggが正常に作動しなくなります。
		もし、Elggコアに手を加えていないなら、単純にviewディレクトリを削除して最新のElggのと置き換えてください。
		最新のElggは、<a href="http://elgg.org">elgg.org</a> よりダウンロードできます。<br /><br />
		
		手順の詳細は、<a href="http://docs.elgg.org/wiki/Upgrading_Elgg">Upgrading Elgg documentation</a> をご覧ください。
		その他なにかお困りでしたら、遠慮無く<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>に投稿してみてください。',


	'update:twitter_api:deactivated' => 'Twitter API(旧称Twitter Service)はアップグレード中に停止しました。必要なら、手動でプラグインを再起動させてください。',
	'update:oauth_api:deactivated' => 'OAuth API(旧称 OAuth LIb)はアップグレード中に停止しました。必要なら、手動でプラグインを再起動させてください。',

	'deprecated:function' => '%s() は %s()で置き換えられました。',

/**
 * Welcome
 */

	'welcome' => "ようこそ",
	'welcome:user' => 'ようこそ、%s さん！',

/**
 * Emails
 */
	'email:settings' => "Eメール設定",
	'email:address:label' => "あなたのEメールアドレス",

	'email:save:success' => "新しい電子メールアドレスを保存しました。",
	'email:save:fail' => "あなたの電子メールアドレスを保存できませんでした。",

	'friend:newfriend:subject' => "%s さんはあなたを友達に登録しました！",
	'friend:newfriend:body' => "%s さんはあなたを友達として登録しました！

プロフィールは次のURLで確認できます。

%s

(※) このメールには返信しないでください。",


	'email:resetpassword:subject' => "パスワードをリセットしました",
	'email:resetpassword:body' => "%s さん, こんにちは。

あなたのパスワードをリセットしました: %s",


	'email:resetreq:subject' => "新しいパスワードのリクエスト",
	'email:resetreq:body' => "%s さん、こんにちは,

だれか (from the IP address %s) があなたのアカウントのパスワードの再発行を求めています。

御自身がパスワードの再発行をリクエストされたのでしたら、下記リンクをクリックしてください。見の覚えが無いようでしたらこのメールを無視してください。

%s
",

/**
 * user default access
 */

	'default_access:settings' => "あなたのデフォルトの公開範囲",
	'default_access:label' => "デフォルトの公開範囲",
	'user:default_access:success' => "新しい公開範囲の設定を保存しました。",
	'user:default_access:failure' => "新しい公開範囲の設定が保存できません。",

/**
 * XML-RPC
 */
	'xmlrpc:noinputdata'	=>	"入力データがありません",

/**
 * Comments
 */

	'comments:count' => "%s さんのコメント",

	'riveraction:annotation:generic_comment' => '%s さんが、%s さんにコメントしました',

	'generic_comments:add' => "コメントする",
	'generic_comments:post' => "コメントを投稿する",
	'generic_comments:text' => "コメント",
	'generic_comments:latest' => "最新のコメント",
	'generic_comment:posted' => "コメントを投稿しました。",
	'generic_comment:deleted' => "コメントを削除しました。",
	'generic_comment:blank' => "申し訳ありません。コメント内容が空欄のため保存できません。",
	'generic_comment:notfound' => "申し訳ありません。検索しましたが見つかりませんでした。",
	'generic_comment:notdeleted' => "申し訳ありません。このコメントが削除できませんでした。",
	'generic_comment:failure' => "コメントした際、予期せぬエラーが発生しました。",
	'generic_comment:none' => 'コメントはありません',
	'generic_comment:title' => '%s さんが付けたコメント',
	'generic_comment:on' => '%s さんが %s にコメント',
	'generic_comment:email:subject' => '新しいコメントがあります！',
	'generic_comment:email:body' => "あなたの投稿「 %s 」に、 %s さんがコメントしました:


%s


このコメントを見るか、返信する場合はここをクリックしてください: 

%s

%sさんのプロフィールを見る場合は下記をクリックしてください: 

%s

※　このメールには返信しないでください。",

/**
 * Entities
 */
	'byline' => 'By %s',
	'entity:default:strapline' => '作成 %s by %s',
	'entity:default:missingsupport:popup' => 'この情報を正確に表示できません。利用していたプラグインがうまく動作していないか、アンインストールされた可能性があります。',

	'entity:delete:success' => 'エンティティ「 %s 」を削除しました。',
	'entity:delete:fail' => 'エンティティ「 %s 」を削除できませんでした。',


/**
 * Action gatekeeper
 */
	'actiongatekeeper:missingfields' => 'フォームに __token もしくは __ts 項目が欠けています',
	'actiongatekeeper:tokeninvalid' => "あなたが使用しているページの期限が切れました。もう一度試してみてください。",
	'actiongatekeeper:timeerror' => 'ご覧のページは閲覧期限が切れています。再度ページを読み込んでください。',
	'actiongatekeeper:pluginprevents' => '拡張機能がこのフォームが送信されないようにしいます。',
	'actiongatekeeper:uploadexceeded' => 'アップロードファイルのサイズがこのサイトの管理者が設定した最大値を超えています。',
	'actiongatekeeper:crosssitelogin' => '異なるドメインからのログインは禁止しています。もう一度試してみてください。',


/**
 * Word blacklists
 */
	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'タグ',
	'tags:site_cloud' => 'タグクラウド',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => '%s への接続に失敗しました。コンテントを保存するときに問題が発生したようです。このページを再読込してください。',
	'js:security:token_refreshed' => '%s への接続が復帰しました！',

/**
 * Languages according to ISO 639-1
 */
	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali; Bangla",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "French",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots / Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebrew",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonesian",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"iw" => "Hebrew (obsolete)",
	"ja" => "Japanese(日本語)",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "Korean",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian/Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Tegulu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zu" => "Zulu",
);

add_translation("ja",$japanese);
