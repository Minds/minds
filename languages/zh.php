<?php
/**
 * Chinese Elgg Community 
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to opensource@cosmocommerce.com so we can send you a copy immediately.
 *
 * @category	CosmoCommerce
 * @package 	CosmoCommerce_Elgg_Language
 * @copyright	Copyright (c) 2011 Elgg.Org.cn(http://www.elgg.org.cn)	CosmoCommerce,LLC. (http://www.cosmocommerce.com)
 * @version: 0.5 SVN: Elgg 1.8.0.1 2011093101
 * @contact :
 * Author: CosmoMao airforce.e@gmail.com
 * T: +86-021-66346672
 * L: Shanghai,China
 * M:sales@cosmocommerce.com
 * GIT:http://github.com/cosmocommerce/Elgg-Community-Chinese-Lanugage/
 */
$chinese = array(
/**
 * Sites
 */

	'item:site' => '网站',

/**
 * Sessions
 */

	'login' => "登陆",
	'loginok' => "您已经登陆了。",
	'loginerror' => "登陆失败，请再次检查登陆信息。",
	'login:empty' => "用户名和密码是必填的。",
	'login:baduser' => "加载用户账户失败。",
	'auth:nopams' => "内部错误。没有安装用户验证功能。",

	'logout' => "登出",
	'logoutok' => "您已经登出了。",
	'logouterror' => "登出失败，请再次尝试。",

	'loggedinrequired' => "您必须登陆才能查看该页面。",
	'adminrequired' => "您必须成为管理员才能查看该页面。",
	'membershiprequired' => "您必须成为小组成员才能查看该页面。",


/**
 * Errors
 */
	'exception:title' => "系统遇到了致命错误。",

	'actionundefined' => "请求的操作(Action) (%s) 在系统中没有被定义。",
	'actionnotfound' => "%s 的操作(Action)文件没有找到.",
	'actionloggedout' => "抱歉您在登出的时候不能执行这个操作（Action）。",
	'actionunauthorized' => '您没有权限执行本操作。',

	'InstallationException:SiteNotInstalled' => '无法处理该请求。网站还未配置完成或者站点已经关闭。',
	'InstallationException:MissingLibrary' => '无法加载 %s',
	'InstallationException:CannotLoadSettings' => '系统无法加载配置文件。可能文件不存在或者文件访问权限不够。',

	'SecurityException:Codeblock' => "拒绝访问和执行特权代码位。",
	'DatabaseException:WrongCredentials' => "根据设置的数据库配置信息系统无法连接数据库，请检查配置文件。",
	'DatabaseException:NoConnect' => "系统无法连接数据库 '%s', 请检查数据库存在且有权限访问。",
	'SecurityException:FunctionDenied' => "拒绝访问特权函数 '%s' 。",
	'DatabaseException:DBSetupIssues' => "存在以下问题: ",
	'DatabaseException:ScriptNotFound' => "系统无法找到请求的数据库脚本位于 %s.",
	'DatabaseException:InvalidQuery' => "无效的查询",

	'IOException:FailedToLoadGUID' => "加载新的 %s 来自GUID:%d",
	'InvalidParameterException:NonElggObject' => "传递了一个非-ElggObject 构造器作为 ElggObject 的构造器!",
	'InvalidParameterException:UnrecognisedValue' => "构造器无法识别被传的值。",

	'InvalidClassException:NotValidElggStar' => "GUID:%d 是无效的 %s",

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) 是一个配置错误的插件。它已经被禁用。请搜索官方知识百科获得可能的解决方法 (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => '%s (guid: %s) 无法启动。 原因: %s',
	'PluginException:InvalidID' => "%s 是一个无效的插件 ID.",
	'PluginException:InvalidPath' => "%s 是一个无效的插件路径。",
	'PluginException:InvalidManifest' => '无效的文件清单针对插件 %s',
	'PluginException:InvalidPlugin' => '%s 是一个无效的插件。',
	'PluginException:InvalidPlugin:Details' => '%s 是一个无效的插件: %s',

	'ElggPlugin:MissingID' => '丢失插件 ID (GUID %s)',
	'ElggPlugin:NoPluginPackagePackage' => '丢失插件ID %s 的 ElggPluginPackage(guid %s)',

	'ElggPluginPackage:InvalidPlugin:MissingFile' => '丢失文件包里面的文件  %s ',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => '无效的依赖类型 "%s"',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => '无效的定义类型 "%s"',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => '无效 %s 依赖 "%s" 于插件 %s 中。插件遇到了冲突或者丢失了依赖的文件。!',

	'ElggPlugin:Exception:CannotIncludeFile' => '无法包含 %s 进入插件 %s (guid: %s) 位于 %s。请检查权限!',
	'ElggPlugin:Exception:CannotRegisterViews' => '无法为插件 %s 打开视图目录(guid: %s) 位于 %s。请检查权限!',
	'ElggPlugin:Exception:CannotRegisterLanguages' => '无法为插件 %s 注册语言文件(guid: %s) 位于 %s。请检查权限!',
	'ElggPlugin:Exception:NoID' => '没有插件 guid %s 的ID!',

	'PluginException:ParserError' => '解析API版本描述文件错误 %s 位于插件 %s 中。',
	'PluginException:NoAvailableParser' => '无法找到API版本描述文件中的解释器 %s 位于插件 %s 中。',
	'PluginException:ParserErrorMissingRequiredAttribute' => "丢失必须的 '%s' 属性在插件 %s 的描述文件中。",

	'ElggPlugin:Dependencies:Requires' => '必须',
	'ElggPlugin:Dependencies:Suggests' => '建议',
	'ElggPlugin:Dependencies:Conflicts' => '冲突',
	'ElggPlugin:Dependencies:Conflicted' => '冲突',
	'ElggPlugin:Dependencies:Provides' => '提供',
	'ElggPlugin:Dependencies:Priority' => '优先级',

	'ElggPlugin:Dependencies:Elgg' => '系统版本',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP插件: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP配置文件设置: %s',
	'ElggPlugin:Dependencies:Plugin' => '插件: %s',
	'ElggPlugin:Dependencies:Priority:After' => '%s 之后',
	'ElggPlugin:Dependencies:Priority:Before' => '%s 之前',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s 未安装',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => '丢失',

	'ElggPlugin:InvalidAndDeactivated' => '%s 是一个无效插件并且已经被禁用。',

	'InvalidParameterException:NonElggUser' => "传递一个非-ElggUser 对象到 ElggUser 构造器上!",

	'InvalidParameterException:NonElggSite' => "传递一个非-ElggSite 对象到 ElggSite 构造器上!",

	'InvalidParameterException:NonElggGroup' => "传递一个非-ElggGroup 对象到 ElggGroup 构造器上!",

	'IOException:UnableToSaveNew' => "无法保存新的 %s",

	'InvalidParameterException:GUIDNotForExport' => "GUID 导出时候未被指定，这是异常错误。",
	'InvalidParameterException:NonArrayReturnValue' => "实体特化函数传递了一个非数组返回值参数。",

	'ConfigurationException:NoCachePath' => "缓存路径为空!",
	'IOException:NotDirectory' => "%s 不是一个目录。",

	'IOException:BaseEntitySaveFailed' => "无法保存新的对象的实体信息。!",
	'InvalidParameterException:UnexpectedODDClass' => "import() 传递了一个未预期的 ODD 类",
	'InvalidParameterException:EntityTypeNotSet' => "实体类型必须被设置。",

	'ClassException:ClassnameNotClass' => "%s 不是一个 %s.",
	'ClassNotFoundException:MissingClass' => "类 '%s' 没有被找到。丢失了插件?",
	'InstallationException:TypeNotSupported' => "类型 %s 不被支持。这说明您的安装有错误，很可能来自一个不完整的更新。",

	'ImportException:ImportFailed' => "无法导入元素 %d",
	'ImportException:ProblemSaving' => "保存 %s 的时候遇到错误",
	'ImportException:NoGUID' => "新实体创建好了，但是没有 GUID。这是一个异常。",

	'ImportException:GUIDNotFound' => "实体 '%d' 没有找到。",
	'ImportException:ProblemUpdatingMeta' => "更新 '%s' 于实体 '%d' 上遇到异常",

	'ExportException:NoSuchEntity' => "没有实体 GUID:%d",

	'ImportException:NoODDElements' => "没有找到 OpenDD 元素于导入数据中，导入失败。",
	'ImportException:NotAllImported' => "没有把所有元素导入完成。",

	'InvalidParameterException:UnrecognisedFileMode' => "未识别的文件模式 '%s'",
	'InvalidParameterException:MissingOwner' => "文件 %s (文件 guid:%d) (所有者 guid:%d) 没有所有者!",
	'IOException:CouldNotMake' => "无法创建 %s",
	'IOException:MissingFileName' => "打开文件之前必须制定文件名。",
	'ClassNotFoundException:NotFoundNotSavedWithFile' => "无法加载文件存储类 %s 给文件 %u",
	'NotificationException:NoNotificationMethod' => "没有指定通知方式。",
	'NotificationException:NoHandlerFound' => "没有发现 '%s' 的 handler 或者它无法被调用。",
	'NotificationException:ErrorNotifyingGuid' => "通知时候遇到错误 %d",
	'NotificationException:NoEmailAddress' => "无法收到邮件地址 GUID:%d",
	'NotificationException:MissingParameter' => "没有必填参数 '%s'",

	'DatabaseException:WhereSetNonQuery' => "Where 集合没有包含 WhereQueryComponent",
	'DatabaseException:SelectFieldsMissing' => "Select查询中字段遗漏",
	'DatabaseException:UnspecifiedQueryType' => "不可识别或者未指定查询类型。",
	'DatabaseException:NoTablesSpecified' => "查询没有指定数据表。",
	'DatabaseException:NoACL' => "查询没有访问权限控制指定。",

	'InvalidParameterException:NoEntityFound' => "没有找到实体，或者实体不存在，或者您没有权限访问。",

	'InvalidParameterException:GUIDNotFound' => "GUID:%s 没有被找到或者您没有权限访问。",
	'InvalidParameterException:IdNotExistForGUID' => "抱歉, '%s' 不存在 guid:%d",
	'InvalidParameterException:CanNotExportType' => "抱歉, 无法预料如何导出 '%s'",
	'InvalidParameterException:NoDataFound' => "找不到任何数据",
	'InvalidParameterException:DoesNotBelong' => "不属于任何实体。",
	'InvalidParameterException:DoesNotBelongOrRefer' => "没有指向任何实体或者属于任何实体。",
	'InvalidParameterException:MissingParameter' => "没有参数，您需要指定 GUID.",
	'InvalidParameterException:LibraryNotRegistered' => '%s 不是一个注册过的类库',

	'APIException:ApiResultUnknown' => "API结果是一个未知类型，异常错误。",
	'ConfigurationException:NoSiteID' => "没有指定站点ID。",
	'SecurityException:APIAccessDenied' => "抱歉, API访问已经被管理员禁用了。",
	'SecurityException:NoAuthMethods' => "没有找到可以验证该API请求的验证方式。",
	'SecurityException:UnexpectedOutputInGatekeeper' => '未预料的权限控制 gatekeeper 调用请求。由于安全原因中断执行。搜索 http://docs.elgg.org/ 查看原因。',
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "expose_method() 中没有设置方式或者函数的对应调用",
	'InvalidParameterException:APIParametersArrayStructure' => "发布函数 '%s' 调用的参数数组结构不正确",
	'InvalidParameterException:UnrecognisedHttpMethod' => "不可识别的 http 方式 %s 于API 方式 '%s'",
	'APIException:MissingParameterInMethod' => "没有参数 %s 于方式 %s中",
	'APIException:ParameterNotArray' => "%s 不是一个数组。",
	'APIException:UnrecognisedTypeCast' => "不可识别的类型 %s 于参数 '%s' 在 '%s' 方式中。",
	'APIException:InvalidParameter' => "无效的参数 '%s' 在方式 '%s' 中。",
	'APIException:FunctionParseError' => "%s(%s) 解析错误。",
	'APIException:FunctionNoReturn' => "%s(%s) 没有返回值。",
	'APIException:APIAuthenticationFailed' => "API 验证调用失败",
	'APIException:UserAuthenticationFailed' => "用户验证调用失败",
	'SecurityException:AuthTokenExpired' => "验证 token 没有，无效或者超时。",
	'CallException:InvalidCallMethod' => "%s 必须被 '%s' 调用",
	'APIException:MethodCallNotImplemented' => "方式 '%s' 没有被实现。",
	'APIException:FunctionDoesNotExist' => "函数 '%s' 不可用。",
	'APIException:AlgorithmNotSupported' => "算法 '%s' 不被支持或者被禁用。",
	'ConfigurationException:CacheDirNotSet' => "缓存的目录 'cache_path' 未设定。",
	'APIException:NotGetOrPost' => "请求的方式必须是 GET 或 POST",
	'APIException:MissingAPIKey' => "没有 API key",
	'APIException:BadAPIKey' => "错误的 API key",
	'APIException:MissingHmac' => "没有 X-Elgg-hmac header",
	'APIException:MissingHmacAlgo' => "没有 X-Elgg-hmac-algo header",
	'APIException:MissingTime' => "没有 X-Elgg-time header",
	'APIException:MissingNonce' => "没有 X-Elgg-nonce header",
	'APIException:TemporalDrift' => "X-Elgg-time 时间差太大",
	'APIException:NoQueryString' => "查询字符串没有数据",
	'APIException:MissingPOSTHash' => "没有 X-Elgg-posthash header",
	'APIException:MissingPOSTAlgo' => "没有 X-Elgg-posthash_algo header",
	'APIException:MissingContentType' => "没有 POST 数据的内容类型",
	'SecurityException:InvalidPostHash' => "POST 数据 hash 是无效的 - 预期获得 %s 实际获得 %s.",
	'SecurityException:DupePacket' => "签名数据包已经可见。",
	'SecurityException:InvalidAPIKey' => "无效或者缺失API Key。",
	'NotImplementedException:CallMethodNotImplemented' => "调用方式 '%s' 当前不被支持。",

	'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC 方式调用 '%s' 未实现。",
	'InvalidParameterException:UnexpectedReturnFormat' => "调用方式 '%s' 返回了未预期的结果。",
	'CallException:NotRPCCall' => "调用没有被作为一个有效的 XML-RPC 请求",

	'PluginException:NoPluginName' => "插件名称没有被找到",

	'SecurityException:authenticationfailed' => "用户无法被验证",

	'CronException:unknownperiod' => '%s 不是一个可识别的周期。',

	'SecurityException:deletedisablecurrentsite' => '您可以删除或者关闭正在访问的网站!',

	'RegistrationException:EmptyPassword' => '密码字段不能为空。',
	'RegistrationException:PasswordMismatch' => '密码必须匹配',
	'LoginException:BannedUser' => '您已经被本站屏蔽，无法登陆。',
	'LoginException:UsernameFailure' => '登陆失败，请检查用户名和密码。',
	'LoginException:PasswordFailure' => '登陆失败，请检查用户名和密码。',
	'LoginException:AccountLocked' => '您的账户已经被锁定，因为日志中发现了大量的错误数据。',

	'memcache:notinstalled' => 'PHP memcache 模块没有安装，请安装 php5-memcache',
	'memcache:noservers' => '没有定义 memcache 服务器，请输入变量 $CONFIG->memcache_servers 在配置文件里面',
	'memcache:versiontoolow' => 'Memcache 需要至少版本 %s 来运行，您的版本 %s',
	'memcache:noaddserver' => '多服务器的支持被禁用，您需要升级您的 PECL memcache 类库。',

	'deprecatedfunction' => '警告: 代码使用的函数 \'%s\' 已经不和当前版本系统兼容',

	'pageownerunavailable' => '警告: 本页面的所有者 %d 无法访问!',
	'viewfailure' => '查看 %s 的时候遇到了内部错误',
	'changebookmark' => '请修改您本页书签',
/**
 * API
 */
	'system.api.list' => "列出所有系统可用的API调用请求。",
	'auth.gettoken' => "这个 API 调用允许客户获得一个验证 token 来作为验证通过请求，传输其作为参数 auth_token 。",

/**
 * User details
 */

	'name' => "名称",
	'email' => "邮箱",
	'username' => "用户名",
	'loginusername' => "用户名或者邮箱",
	'password' => "密码",
	'passwordagain' => "密码 (重新输入一次)",
	'admin_option' => "设置为管理员?",

/**
 * Access
 */

	'PRIVATE' => "保密",
	'LOGGED_IN' => "以用户登陆",
	'PUBLIC' => "公开",
	'access:friends:label' => "好友",
	'access' => "访问权限",

/**
 * Dashboard and widgets
 */

	'dashboard' => "控制面板",
	'dashboard:nowidgets' => "您的控制面板可以让您浏览您关注的信息和活动。",

	'widgets:add' => '添加控件',
	'widgets:add:description' => "点击任何控件下面的按钮添加到页面中。",
	'widgets:position:fixed' => '(页面的固定位置)',
	'widget:unavailable' => '您已经添加好了这个控件',
	'widget:numbertodisplay' => '显示的条目数量',

	'widget:delete' => '移除 %s',
	'widget:edit' => '定制控件',

	'widgets' => "控件",
	'widget' => "控件",
	'item:object:widget' => "控件",
	'widgets:save:success' => "控件保存成功。",
	'widgets:save:failure' => "保存控件失败，请再试一次。",
	'widgets:add:success' => "控件添加成功。",
	'widgets:add:failure' => "控件添加失败。",
	'widgets:move:failure' => "控件存储新位置失败。",
	'widgets:remove:failure' => "移除控件失败。",

/**
 * Groups
 */

	'group' => "小组",
	'item:group' => "小组",

/**
 * Users
 */

	'user' => "用户",
	'item:user' => "用户",

/**
 * Friends
 */

	'friends' => "好友",
	'friends:yours' => "您的好友",
	'friends:owned' => "%s 的好友",
	'friend:add' => "添加好友",
	'friend:remove' => "移除好友",

	'friends:add:successful' => "您成功的添加了 %s 作为好友。",
	'friends:add:failure' => "我们无法添加 %s 作为好友，请再试一次。",

	'friends:remove:successful' => "您已经成功的从好友中移除了 %s 。",
	'friends:remove:failure' => "我们不能把 %s 从您的好友中删除，请再试一次。",

	'friends:none' => "该用户还没有添加任何人为好友。",
	'friends:none:you' => "您还没有好友。",

	'friends:none:found' => "没有找到好友。",

	'friends:of:none' => "还没有其他用户添加该用户作为好友。",
	'friends:of:none:you' => "还没有人添加您为好友。开始输入您的个人资料来让更多人找到您!",

	'friends:of:owned' => "会员 %s 被添加为好友",

	'friends:of' => "被关注",
	'friends:collections' => "好友圈",
	'collections:add' => "新好友圈",
	'friends:collections:add' => "新好友圈",
	'friends:addfriends' => "选择好友",
	'friends:collectionname' => "好友圈名称",
	'friends:collectionfriends' => "好友圈中的朋友们",
	'friends:collectionedit' => "编辑好友圈",
	'friends:nocollections' => "您还没有任何好友圈",
	'friends:collectiondeleted' => "您的好友圈删除成功。",
	'friends:collectiondeletefailed' => "我们无法删除这个好友圈，可能您没有权限或者发生了其他错误。",
	'friends:collectionadded' => "您的好友圈创建成功。",
	'friends:nocollectionname' => "在保存好友圈之前请设置名称。",
	'friends:collections:members' => "好友圈成员",
	'friends:collections:edit' => "编辑好友圈",
	'friends:collections:edited' => "保存好友圈",
	'friends:collection:edit_failed' => '无法保存好友圈。',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => '头像',
	'avatar:create' => '创建头像',
	'avatar:edit' => '编辑头像',
	'avatar:preview' => '预览',
	'avatar:upload' => '上传头像',
	'avatar:current' => '当前头像',
	'avatar:crop:title' => '头像裁剪工具',
	'avatar:upload:instructions' => "您的头像将会在网站显示。您可以随时更新头像。(文件格式: GIF, JPG 或者 PNG)",
	'avatar:create:instructions' => '点击并拖动下方方框匹配到需要裁减的头像位置。预览图片将会在方框右边。如果确认预览效果请点击 \'创建头像\'。这个裁减过的版本将会在网站显示。',
	'avatar:upload:success' => '头像上传成功',
	'avatar:upload:fail' => '头像上传失败',
	'avatar:resize:fail' => '头像尺寸调整失败',
	'avatar:crop:success' => '头像裁减成功',
	'avatar:crop:fail' => '头像裁减失败',

	'profile:edit' => '编辑资料',
	'profile:aboutme' => "关于我",
	'profile:description' => "关于我",
	'profile:briefdescription' => "简述",
	'profile:location' => "地址",
	'profile:skills' => "技能",
	'profile:interests' => "兴趣",
	'profile:contactemail' => "联系邮件",
	'profile:phone' => "电话",
	'profile:mobile' => "手机",
	'profile:website' => "网站",
	'profile:twitter' => "Twitter用户名",
	'profile:saved' => "您的个人资料保存成功。",

	'admin:appearance:profile_fields' => '编辑个人资料字段',
	'profile:edit:default' => '编辑个人资料字段',
	'profile:label' => "个人资料标题",
	'profile:type' => "个人资料类型",
	'profile:editdefault:delete:fail' => '删除默认资料字段失败',
	'profile:editdefault:delete:success' => '删除默认资料字段成功!',
	'profile:defaultprofile:reset' => '系统资料重置',
	'profile:resetdefault' => '默认资料重置',
	'profile:explainchangefields' => "通过下方表单您可以替换字段为自己的。 \n\n 输入新资料字段的标题，例如：'喜欢的球队', 然后选择字段类型 (例如： text, url, tags), 之后点击 '添加' 按钮。 要重新排列字段，可以通过拖动字段标题来实现。 编辑字段标题只需要点击字段标题的文字使之可以编辑。\n\n 任何时候您都可以撤销修改返回默认字段的设置。但是您将会失去之前任何一家设置的定制字段在个人资料页面。",
	'profile:editdefault:success' => '资料字段添加到个人资料成功',
	'profile:editdefault:fail' => '资料字段添加到个人资料失败',


/**
 * Feeds
 */
	'feed:rss' => 'RSS订阅',
/**
 * Links
 */
	'link:view' => '查看链接',
	'link:view:all' => '查看所有',


/**
 * River
 */
	'river' => "活动板",
	'river:friend:user:default' => "%s 现在与 %s 是好友",
	'river:update:user:avatar' => '%s 更新了新的头像',
	'river:noaccess' => '您没有权限查看该页面。',
	'river:posted:generic' => '%s 发布了 ',
	'riveritem:single:user' => '一个用户',
	'riveritem:plural:user' => '一些用户',
	'river:ingroup' => '进入了小组 %s',
	'river:none' => '没有活动',

	'river:widget:title' => "活动",
	'river:widget:description' => "显示最新活动",
	'river:widget:type' => "活动类型",
	'river:widgets:friends' => '还有活动',
	'river:widgets:all' => '整站活动',

/**
 * Notifications
 */
	'notifications:usersettings' => "通知设定",
	'notifications:methods' => "请输入您允许的通知方式。",

	'notifications:usersettings:save:ok' => "您的通知设置保存成功。",
	'notifications:usersettings:save:fail' => "您的通知设置保存失败。",

	'user.notification.get' => '返回某个用户的通知设置。',
	'user.notification.set' => '设置某个用户的通知设置。',
/**
 * Search
 */

	'search' => "搜索",
	'searchtitle' => "搜索: %s",
	'users:searchtitle' => "搜索用户: %s",
	'groups:searchtitle' => "搜索小组: %s",
	'advancedsearchtitle' => "%s 的搜索结果匹配 %s",
	'notfound' => "没有找到匹配的结果。",
	'next' => "下一页",
	'previous' => "前一页",

	'viewtype:change' => "修改列表类型",
	'viewtype:list' => "列表视图",
	'viewtype:gallery' => "相册视图",

	'tag:search:startblurb' => "标签匹配 '%s' 的项目:",

	'user:search:startblurb' => "匹配 '%s' 的用户:",
	'user:search:finishblurb' => "查看更多点击这里。",

	'group:search:startblurb' => "匹配 '%s' 的小组:",
	'group:search:finishblurb' => "查看更多点击这里。",
	'search:go' => '搜索',
	'userpicker:only_friends' => '只有好友',

/**
 * Account
 */

	'account' => "我的账户",
	'settings' => "设置",
	'tools' => "插件",

	'register' => "注册",
	'registerok' => "您已经成功的注册了 %s.",
	'registerbad' => "您的注册不成功，因为有一个未知错误。",
	'registerdisabled' => "注册已经被系统管理员禁用了。",

	'registration:notemail' => '您输入的邮箱地址是无效的。',
	'registration:userexists' => '用户名已经存在。',
	'registration:usernametooshort' => '您的用户名最少需要 %u 位字符。',
	'registration:passwordtooshort' => '您的密码最少需要 %u 位字符。',
	'registration:dupeemail' => '这个Email地址已经被注册过了。',
	'registration:invalidchars' => '抱歉您的用户名包含了以下无效的字符: %s。所有这些字符都是无效的： %s',
	'registration:emailnotvalid' => '抱歉您输入的邮件地址是无效的。',
	'registration:passwordnotvalid' => '抱歉您输入的密码是无效的。',
	'registration:usernamenotvalid' => '抱歉您输入的用户名是无效的。',

	'adduser' => "添加用户",
	'adduser:ok' => "添加新用户成功。",
	'adduser:bad' => "添加新用户失败。",

	'user:set:name' => "账户名设置",
	'user:name:label' => "显示名称",
	'user:name:success' => "名称修改成功。",
	'user:name:fail' => "名称修改失败，请检查是否名称过长，并再试一次。",

	'user:set:password' => "账户密码",
	'user:current_password:label' => '当前密码',
	'user:password:label' => "新密码",
	'user:password2:label' => "新密码确认",
	'user:password:success' => "密码修改成功",
	'user:password:fail' => "密码修改失败",
	'user:password:fail:notsame' => "两次密码不匹配!",
	'user:password:fail:tooshort' => "密码太短!",
	'user:password:fail:incorrect_current_password' => '当前密码输入错误。',
	'user:resetpassword:unknown_user' => '无效的用户。',
	'user:resetpassword:reset_password_confirm' => '重置密码将会邮件给您新的密码到您注册邮箱中。',

	'user:set:language' => "语言设置",
	'user:language:label' => "您的语言",
	'user:language:success' => "您的语言设置更新成功。",
	'user:language:fail' => "您的语言设置更新失败。",

	'user:username:notfound' => '用户名 %s 没有找到',

	'user:password:lost' => '找回密码',
	'user:password:resetreq:success' => '请求新密码的邮件已经发出。',
	'user:password:resetreq:fail' => '无法处理请求新密码的功能',

	'user:password:text' => '请求发送新密码需要您输入用户名并点击请求按钮。',

	'user:persistent' => '记住我',

	'walled_garden:welcome' => '欢迎',

/**
 * Administration
 */
	'menu:page:header:administer' => '管理',
	'menu:page:header:configure' => '配置',
	'menu:page:header:develop' => '开发',
	'menu:page:header:default' => '其他',

	'admin:view_site' => '查看前台',
	'admin:loggedin' => '作为 %s 登陆',
	'admin:menu' => '菜单',

	'admin:configuration:success' => "设置保存成功。",
	'admin:configuration:fail' => "设置保存失败。",

	'admin:unknown_section' => '无效的管理功能。',

	'admin' => "管理",
	'admin:description' => "管理员后台允许您控制系统的所有部分，包括用户和插件的设置。选择选项来修改。",

	'admin:statistics' => "统计",
	'admin:statistics:overview' => '总览',

	'admin:appearance' => '界面',
	'admin:utilities' => '插件',

	'admin:users' => "用户",
	'admin:users:online' => '当前在线',
	'admin:users:newest' => '最新用户',
	'admin:users:add' => '添加新用户',
	'admin:users:description' => "管理员后台面板允许您控制用户在网站的设置。选择下方的一个选项开始设置。",
	'admin:users:adduser:label' => "点击添加一个新用户...",
	'admin:users:opt:linktext' => "设置用户...",
	'admin:users:opt:description' => "设置用户和账户信息。",
	'admin:users:find' => '查找',

	'admin:settings' => '设置',
	'admin:settings:basic' => '基本设置',
	'admin:settings:advanced' => '高级设置',
	'admin:site:description' => "管理员面板允许你控制全局设定。选择下方选项开始设置。", 
	'admin:site:opt:linktext' => "配置站点...",
	'admin:site:access:warning' => "修改权限将会仅仅影响以后创建的内容。",

	'admin:dashboard' => '控制面板',
	'admin:widget:online_users' => '站点会员',
	'admin:widget:online_users:help' => '列出当前正在访问的用户列表',
	'admin:widget:new_users' => '新用户',
	'admin:widget:new_users:help' => '列出最新的用户',
	'admin:widget:content_stats' => '内容统计',
	'admin:widget:content_stats:help' => '持续统计由用户创建的内容',
	'widget:content_stats:type' => '内容类型',
	'widget:content_stats:number' => '数量',

	'admin:widget:admin_welcome' => '欢迎',
	'admin:widget:admin_welcome:help' => "简述系统后台管理页面",
	'admin:widget:admin_welcome:intro' =>
'欢迎来到 Elgg! 您正在查看管理控制面板。这对追踪当前网站正在发生什么非常有用。',

	'admin:widget:admin_welcome:admin_overview' =>
"管理后台的导航在右边的菜单。主要功能分为三部分：
	<dl>
		<dt>管理</dt><dd>每日的任务监控，包括举报内容，在线情况和统计分析。</dd>
		<dt>配置</dt><dd>设置网站的配置信息，包括站点名称或者激活插件。</dd>
		<dt>开发</dt><dd>给程序员开发和构建模板主题。（需要开发者插件）</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />感谢使用 Elgg，可以通过底部链接访问更多资源!',

	'admin:footer:faq' => '问答解析',
	'admin:footer:manual' => '管理员手册',
	'admin:footer:community_forums' => 'Elgg 社区论坛',
	'admin:footer:blog' => 'Elgg 博客',

	'admin:plugins:category:all' => '所有插件',
	'admin:plugins:category:active' => '激活插件',
	'admin:plugins:category:inactive' => '未激活插件',
	'admin:plugins:category:admin' => '管理员',
	'admin:plugins:category:bundled' => '组合',
	'admin:plugins:category:content' => '内容',
	'admin:plugins:category:development' => '开发',
	'admin:plugins:category:enhancement' => '增强',
	'admin:plugins:category:api' => '服务/API',
	'admin:plugins:category:communication' => '通讯',
	'admin:plugins:category:security' => '安全和Spam',
	'admin:plugins:category:social' => '社交',
	'admin:plugins:category:multimedia' => '多媒体',
	'admin:plugins:category:theme' => '主题',
	'admin:plugins:category:widget' => '控件',

	'admin:plugins:sort:priority' => '优先级顺序',
	'admin:plugins:sort:alpha' => '字母顺序',
	'admin:plugins:sort:date' => '新旧顺序',

	'admin:plugins:markdown:unknown_plugin' => '未知插件。',
	'admin:plugins:markdown:unknown_file' => '未知文件。',


	'admin:notices:could_not_delete' => '无法删除提示。',

	'admin:options' => '管理员选项',


/**
 * Plugins
 */
	'plugins:settings:save:ok' => "设置 %s 插件成功。",
	'plugins:settings:save:fail' => "设置 %s 插件失败。",
	'plugins:usersettings:save:ok' => "插件 %s 的用户设置成功。",
	'plugins:usersettings:save:fail' => "插件 %s 的用户设置失败。",
	'item:object:plugin' => '插件',

	'admin:plugins' => "插件",
	'admin:plugins:activate_all' => '激活所有',
	'admin:plugins:deactivate_all' => '禁用所有',
	'admin:plugins:activate' => '激活',
	'admin:plugins:deactivate' => '禁用',
	'admin:plugins:description' => "本页面允许您控制和配置网站的插件。",
	'admin:plugins:opt:linktext' => "配置插件...",
	'admin:plugins:opt:description' => "配置安装在网站上的插件。 ",
	'admin:plugins:label:author' => "作者",
	'admin:plugins:label:copyright' => "版权",
	'admin:plugins:label:categories' => '分类',
	'admin:plugins:label:licence' => "授权",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:moreinfo' => '更多信息',
	'admin:plugins:label:version' => '版本',
	'admin:plugins:label:location' => '地址',
	'admin:plugins:label:dependencies' => '依赖',

	'admin:plugins:warning:elgg_version_unknown' => '本插件使用了陈旧的配置文件并且没有指定系统兼容版本。可能无法运行！',
	'admin:plugins:warning:unmet_dependencies' => '本插件不满足依赖关系无法激活。请查看更多信息来检查依赖关系。',
	'admin:plugins:warning:invalid' => '%s 不是一个有效的插件。检查 <a href="http://docs.elgg.org/Invalid_Plugin">系统文档</a> 排除问题。',
	'admin:plugins:cannot_activate' => '无法激活',

	'admin:plugins:set_priority:yes' => "排序 %s 成功。",
	'admin:plugins:set_priority:no' => "排序 %s 失败。",
	'admin:plugins:deactivate:yes' => "禁用 %s 成功。",
	'admin:plugins:deactivate:no' => "禁用 %s 失败。",
	'admin:plugins:activate:yes' => "激活 %s 成功。",
	'admin:plugins:activate:no' => "激活 %s 失败。",
	'admin:plugins:categories:all' => '所有分类',
	'admin:plugins:plugin_website' => '插件网站',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => '版本 %s',
	'admin:plugins:simple' => '简洁',
	'admin:plugins:advanced' => '高级',
	'admin:plugin_settings' => '插件设置',
	'admin:plugins:simple_simple_fail' => '无法保存设置',
	'admin:plugins:simple_simple_success' => '设置保存成功。',
	'admin:plugins:simple:cannot_activate' => '无法激活这个插件。检查高级插件管理区域的更多信息。',
	'admin:plugins:warning:unmet_dependencies_active' => '这个插件激活了，但是没有满足依赖关系。您可能会遇到错误。点击下方的 "更多信息" 查看详细情况。',

	'admin:plugins:dependencies:type' => '类型',
	'admin:plugins:dependencies:name' => '名称',
	'admin:plugins:dependencies:expected_value' => '测试值',
	'admin:plugins:dependencies:local_value' => '实际值',
	'admin:plugins:dependencies:comment' => '备注',

	'admin:statistics:description' => "这里是网站的统计总览。可以查看详细分析，提供更具体的统计功能。",
	'admin:statistics:opt:description' => "在您的网站上查看用户和各项数据的统计信息。",
	'admin:statistics:opt:linktext' => "查看统计...",
	'admin:statistics:label:basic' => "基本站点统计",
	'admin:statistics:label:numentities' => "实体数",
	'admin:statistics:label:numusers' => "用户数",
	'admin:statistics:label:numonline' => "在线数",
	'admin:statistics:label:onlineusers' => "当前在线",
	'admin:statistics:label:version' => "系统版本",
	'admin:statistics:label:version:release' => "发布",
	'admin:statistics:label:version:version' => "版本",

	'admin:user:label:search' => "查找用户:",
	'admin:user:label:searchbutton' => "搜索",

	'admin:user:ban:no' => "屏蔽用户失败。",
	'admin:user:ban:yes' => "屏蔽用户成功。",
	'admin:user:self:ban:no' => "您不能屏蔽自己",
	'admin:user:unban:no' => "取消屏蔽失败。",
	'admin:user:unban:yes' => "取消屏蔽成功。",
	'admin:user:delete:no' => "用户删除失败。",
	'admin:user:delete:yes' => "用户 %s 删除成功。",
	'admin:user:self:delete:no' => "您不能删除自己",

	'admin:user:resetpassword:yes' => "密码重置成功，用户已经收到邮件通知。",
	'admin:user:resetpassword:no' => "密码重置失败。",

	'admin:user:makeadmin:yes' => "用户设置为管理员成功。",
	'admin:user:makeadmin:no' => "用户设置为管理员失败。",

	'admin:user:removeadmin:yes' => "用户拥有管理员的权限取消成功。",
	'admin:user:removeadmin:no' => "用户拥有管理员的权限取消失败。",
	'admin:user:self:removeadmin:no' => "您不能取消自己的管理员权限。",

	'admin:appearance:menu_items' => '菜单项',
	'admin:menu_items:configure' => '配置菜单项',
	'admin:menu_items:description' => '选择哪些菜单项作为网站主推链接。其他的链接将会进入尾部的 "更多" 菜单中。',
	'admin:menu_items:hide_toolbar_entries' => '从工具条菜单中移除链接?',
	'admin:menu_items:saved' => '菜单项保存成功。',
	'admin:add_menu_item' => '添加一条自定义菜单项',
	'admin:add_menu_item:description' => '把菜单的名称和URL填入下方，作为自定义菜单。',

	'admin:appearance:default_widgets' => '默认控件',
	'admin:default_widgets:unknown_type' => '位置的控件类型',
	'admin:default_widgets:instructions' => '添加, 移除, 放置和配置默认控件到设定的控件页面中。这些修改将会仅对新用户有效。',

/**
 * User settings
 */
	'usersettings:description' => "用户设置面板允许您控制所有的个人设置，包括个人插件设置等。选择下方选项开始设置。",

	'usersettings:statistics' => "您的统计",
	'usersettings:statistics:opt:description' => "查看网站关于用户和对象统计信息",
	'usersettings:statistics:opt:linktext' => "账户统计",

	'usersettings:user' => "您的设置",
	'usersettings:user:opt:description' => "修改管理用户的设置。",
	'usersettings:user:opt:linktext' => "修改设置",

	'usersettings:plugins' => "插件",
	'usersettings:plugins:opt:description' => "您配置的插件设置信息。",
	'usersettings:plugins:opt:linktext' => "配置您的插件设置",

	'usersettings:plugins:description' => "这个面板将会允许您修改和配置个人插件信息。",
	'usersettings:statistics:label:numentities' => "您的内容",

	'usersettings:statistics:yourdetails' => "详细信息",
	'usersettings:statistics:label:name' => "全民",
	'usersettings:statistics:label:email' => "邮箱",
	'usersettings:statistics:label:membersince' => "注册日期",
	'usersettings:statistics:label:lastlogin' => "最新登陆",

/**
 * Activity river
 */
	'river:all' => '整站活动',
	'river:mine' => '我的活动',
	'river:friends' => '好友活动',
	'river:select' => '显示 %s',
	'river:comments:more' => '+%u 更多',
	'river:generic_comment' => '评论于 %s %s',

	'friends:widget:description' => "显示您一部分好友。",
	'friends:num_display' => "显示好友数",
	'friends:icon_size' => "图标尺寸",
	'friends:tiny' => "很小",
	'friends:small' => "小",

/**
 * Generic action words
 */

	'save' => "保存",
	'reset' => '重置',
	'publish' => "发布",
	'cancel' => "取消",
	'saving' => "保存中 ...",
	'update' => "更新",
	'preview' => "预览",
	'edit' => "编辑",
	'delete' => "删除",
	'accept' => "接受",
	'load' => "加载",
	'upload' => "上传",
	'ban' => "屏蔽",
	'unban' => "取消屏蔽",
	'banned' => "已经屏蔽",
	'enable' => "激活",
	'disable' => "禁用",
	'request' => "请求",
	'complete' => "完成",
	'open' => '打开',
	'close' => '关闭',
	'reply' => "回复",
	'more' => '更多',
	'comments' => '评论',
	'import' => '导入',
	'export' => '导出',
	'untitled' => '未命名',
	'help' => '帮助',
	'send' => '发出',
	'post' => '张贴',
	'submit' => '提交',
	'comment' => '评论',
	'upgrade' => '升级',
	'sort' => '排序',
	'filter' => '筛选',

	'site' => '整站',
	'activity' => '活动',
	'members' => '会员',

	'up' => '上移',
	'down' => '下移',
	'top' => '置顶',
	'bottom' => '置底',

	'more' => '更多',

	'invite' => "邀请",

	'resetpassword' => "重置密码",
	'makeadmin' => "设为管理员",
	'removeadmin' => "移除管理员",

	'option:yes' => "是",
	'option:no' => "否",

	'unknown' => '未知',

	'active' => '激活',
	'total' => '总共',

	'learnmore' => "点击了解更多。",

	'content' => "内容",
	'content:latest' => '最新活动',
	'content:latest:blurb' => '或者点击这里来查看最新的整站内容。',

	'link:text' => '查看',
/**
 * Generic questions
 */

	'question:areyousure' => '是否确认?',

/**
 * Generic data words
 */

	'title' => "标题",
	'description' => "描述",
	'tags' => "标签",
	'spotlight' => "关注",
	'all' => "所有",
	'mine' => "我的",

	'by' => 'by',
	'none' => '无',

	'annotations' => "标注",
	'relationships' => "关系",
	'metadata' => "元数据",
	'tagcloud' => "标签云",
	'tagcloud:allsitetags' => "整站标签云",

/**
 * Entity actions
 */
	'edit:this' => '编辑',
	'delete:this' => '删除',
	'comment:this' => '评论',

/**
 * Input / output strings
 */

	'deleteconfirm' => "确认删除本项目吗?",
	'fileexists' => "文件已经上传过了，如果需要替换它请选择下方：",

/**
 * User add
 */

	'useradd:subject' => '账户注册成功',
	'useradd:body' => '
%s,

账户已经注册成功于 %s。 登陆请访问以下地址:

%s

登陆信息如下:

账户名称: %s
账户密码: %s

一旦登陆成功，我们强烈建议您修改您的密码。
',

/**
 * System messages
 **/

	'systemmessages:dismiss' => "点击关闭",


/**
 * Import / export
 */
	'importsuccess' => "数据导入成功。",
	'importfail' => "OpenDD 数据导入失败。",

/**
 * Time
 */

	'friendlytime:justnow' => "刚刚",
	'friendlytime:minutes' => "%s 分钟前",
	'friendlytime:minutes:singular' => "一分钟前",
	'friendlytime:hours' => "%s 小时前",
	'friendlytime:hours:singular' => "一小时前",
	'friendlytime:days' => "%s 天前",
	'friendlytime:days:singular' => "昨天",
	'friendlytime:date_format' => 'j F Y @ g:ia',

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

	'installation:sitename' => "网站名称:",
	'installation:sitedescription' => "网站简介 (可选):",
	'installation:wwwroot' => "站点地址:",
	'installation:path' => "安装系统文件的完整路径:",
	'installation:dataroot' => "安装数据文件的完整路径:",
	'installation:dataroot:warning' => "您必须手动创建本目录。这个目录需要和系统目录不同。",
	'installation:sitepermissions' => "默认的访问权限:",
	'installation:language'  =>  "默认网站语言:" , 
	'installation:debug'  =>  "Debug 模式给于了额外的信息方便您调试错误.当然这会让系统变慢.因此仅在有错误的时候才建议开启:", 
	'installation:debug:none' => '关闭Debug模式（推荐）',
	'installation:debug:error' => '只显示关键错误',
	'installation:debug:warning' => '显示错误和警告',
	'installation:debug:notice' => '记录所有错误,警告和提醒',	
	

	// Walled Garden support
	'installation:registration:description' => '默认用户自主注册是允许的。如果您不希望用户可以自己注册请关闭本项。',
	'installation:registration:label' => '允许新用户注册',
	'installation:walled_garden:description' => '启用站点私有化运行。这将不允许非登陆用户查看任何网站内容。',
	'installation:walled_garden:label' => '限制页面仅仅可被登陆用户访问',

	
	'installation:httpslogin'  =>  "开启将使用户登录等操作使用HTTPS.您需要确保您的服务器支持https" , 
	'installation:httpslogin:label'  =>  "开启HTTPS登录" , 
	'installation:view'  =>  "输入默认网站使用的视图(如果有疑问请默认留空)" , 
	'installation:siteemail'  =>  "站点邮箱地址(用来发送系统邮件)" , 
	'installation:disableapi'  =>  "RESTful API 是一个弹性和高拓展性的接口,确保了其他外部系统可以访问Elgg" , 
	'installation:disableapi:label'  =>  "开启 RESTful API" , 
	'installation:allow_user_default_access:description'  =>  "如果选中,每个用户将会被允许设置自己的内容权限." , 
	'installation:allow_user_default_access:label'  =>  "允许用户设定权限" , 
	'installation:simplecache:description'  =>  "通过合并包括CSS和JavaScript这类静态内容,利用简单缓存机制来提高性能。通常您会需要开启该功能" , 
	'installation:simplecache:label'  =>  "使用简单缓存机制(推荐)" , 


	'installation:viewpathcache:description' => "通过缓存视图文件减少了插件加载次数。",
	'installation:viewpathcache:label' => "使用缓存视图(推荐)",	 
	

	'upgrading' => '升级中...',
	'upgrade:db' => '数据库结构已经升级完成。',
	'upgrade:core' => '您的系统安装以及升级完成。',
	'upgrade:unable_to_upgrade' => '无法升级。',
	'upgrade:unable_to_upgrade_info' =>
		'安装无法升级是因为原视图（views）文件位于系统核心视图目录（Elgg core views directory）中被检测到。您需要删除原文件从而启用新的代码，如果您没有定制过代码，可以直接删除原来的视图目录（views）并替换一个最新版本文件信息 <a href="http://elgg.org">elgg.org</a>.<br /><br />

		详细指导查看 <a href="http://docs.elgg.org/wiki/Upgrading_Elgg">
		升级 Elgg 文档</a>.  如果需要支持访问
		<a href="http://community.elgg.org/pg/groups/discussion/">社区支持论坛</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (原 Twitter Service) 在升级过程中被禁用。如果需要请之后手工激活。',
	'update:oauth_api:deactivated' => 'OAuth API (原 OAuth Lib) 在升级过程中被禁用。如果需要请之后手工激活。',

	'deprecated:function' => '%s() 已经陈旧，现在被 %s() 替代了',

/**
 * Welcome
 */

	'welcome' => "欢迎",
	'welcome:user' => '欢迎 %s',

/**
 * Emails
 */
	'email:settings' => "邮件设置",
	'email:address:label' => "您的邮箱地址",

	'email:save:success' => "新的邮箱地址已经保存。请到您的邮箱中确认修改。",
	'email:save:fail' => "新的邮箱地址保存失败。",

	'friend:newfriend:subject' => "%s 添加您为好友!",
	'friend:newfriend:body' => "%s 添加您为好友!

查看他的个人资料，请点击下方:

%s

请不要回复本邮件。",



	'email:resetpassword:subject' => "密码重设成功!",
	'email:resetpassword:body' => "您好 %s,

您的密码已经重新设置为: %s",


	'email:resetreq:subject' => "重置新密码请求",
	'email:resetreq:body' => "您好 %s,

来自IP地址 %s 的用户请求重置一个新密码来访问他的账户。

如果这个用户是您可以点击下方连接确认，不然请忽略链接。

%s
",

/**
 * user default access
 */

'default_access:settings' => "您的默认访问权限",
'default_access:label' => "默认权限",
'user:default_access:success' => "您的默认访问权限保存成功。",
'user:default_access:failure' => "您的默认访问权限保存失败。",

/**
 * XML-RPC
 */
	'xmlrpc:noinputdata'	=>	"没有输入数据。",

/**
 * Comments
 */

	'comments:count' => "%s 评论",

	'riveraction:annotation:generic_comment' => '%s 评论了 %s',

	'generic_comments:add' => "留下评论",
	'generic_comments:post' => "发表评论",
	'generic_comments:text' => "评论",
	'generic_comments:latest' => "最新评论",
	'generic_comment:posted' => "您的评论发表成功。",
	'generic_comment:deleted' => "您的评论删除成功。",
	'generic_comment:blank' => "抱歉您需要输入评论数据才能保存成功。",
	'generic_comment:notfound' => "抱歉我们无法找到请求的评论。",
	'generic_comment:notdeleted' => "抱歉评论删除失败。",
	'generic_comment:failure' => "添加评论时候遇到错误，请再试一次。",
	'generic_comment:none' => '没有评论',

	'generic_comment:email:subject' => '您收到一条新评论!',
	'generic_comment:email:body' => "您收到一条新评论 \"%s\" 来自 %s。 内容是:


%s


要回复该评论请点击:

%s

要查看 %s 的个人资料，请点击:

%s

请不要直接回复本邮件。",

/**
 * Entities
 */
	'byline' => '由 %s',
	'entity:default:strapline' => '创建于 %s 由 %s',
	'entity:default:missingsupport:popup' => '这个实体无法被正确显示。可能是因为它所需要的插件没有被安装。',

	'entity:delete:success' => '实体 %s 已经被删除',
	'entity:delete:fail' => '实体 %s 无法被删除',


/**
 * Action gatekeeper
 */
	'actiongatekeeper:missingfields' => '表单没有 __token 或者 __ts 字段',
	'actiongatekeeper:tokeninvalid' => "遇到错误 (token 不匹配)。这是因为您访问的页面已经超时，请刷新或者再次访问。",
	'actiongatekeeper:timeerror' => '您访问的页面已经超时，请刷新或者再次访问。',
	'actiongatekeeper:pluginprevents' => '插件已经对这次提交数据屏蔽。',


/**
 * Word blacklists
 */
	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => '标签',
	'tags:site_cloud' => '整站标签云',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => '无法联系 %s。 您可能遇到保存数据失败。',
	'js:security:token_refreshed' => '连接到 %s 恢复了!',

/**
 * Languages according to ISO 639-1
 */
	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "阿拉伯语",
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
	"da" => "丹麦语",
	"de" => "德语",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "英语",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "法语",
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
	"ja" => "日语",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "韩语",
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
	"nl" => "德语",
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
	"ru" => "俄语",
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
	"vi" => "越南语",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "中文",
	"zu" => "Zulu",
);

add_translation("zh",$chinese);
?>