<?php
/**
 * Elgg log rotator language pack.
 * 
 * @package ElggLogRotate
 * @version 1.8.15
 * @update 2013-5-1
 */

$japanese = array(
	'logrotate:period' => 'システムログを保管する頻度',
	
	'logrotate:weekly' => '１週間に１回',
	'logrotate:monthly' => '１ヶ月に１回',
	'logrotate:yearly' => '１年に１回',
	
	'logrotate:logrotated' => 'ログをローテートしました\n',
	'logrotate:lognotrotated' => "ログローテトに失敗しました\n",
	'logrotate:delete' => '保存したログの削除',

	'logrotate:week' => 'この1週より古い',
	'logrotate:month' => 'この１ヶ月よりも古い',
	'logrotate:year' => 'この１年よりも古い',
	'logrotate:never' => 'しない',
		
	'logrotate:logdeleted' => "ログを削除しました\n",
	'logrotate:lognotdeleted' => "ログを削除する際にエラーが発生しました\n",
);
					
add_translation("ja",$japanese);
