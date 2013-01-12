<?php

$sync = get_input('sync', 'old');
$data = get_input('data');

if (!$data)
    exit;

if (!is_array($data)) {
    $data = array($data);
}
foreach ($data as $entity) {
    $ts = elgg_extract('timestamp', $entity, get_input('__elgg_ts', time() - 30));
	$type =elgg_extract('type', $entity, null);
	$pid = elgg_extract('pid', $entity, null);
	
	 if ($sync == 'new') {
		$limit = 1;
		 $offset= 0;
    } else {
        $limit = 100000;
		$offset= 3;
    }
	
	$mc = new MindsComments();
	$call = $mc->output($type, $pid, $limit,$offset);
	$count = $call['hits']['total'];
	$comments = array_reverse($call['hits']['hits']);

   
   foreach($comments as $comment){
   		$comments_output[] = minds_comments_view_comment($comment);
   }
   $output[] = array('pid'=>$pid, 'comments'=>$comments_output);
}

print(json_encode($output));
return true;