<?php 
	
	$event = $vars["entity"];

	if(!empty($event)){
		$files = json_decode($event->files);
		
		if(count($files) > 0) {
			$content = "<table class='elgg-table'>";
			foreach($files as $file) {
				$content .= "<tr>";
				$content .= "<td><a href='" . EVENT_MANAGER_BASEURL . "/event/file/" . $event->getGUID() . "/" . $file->file . "'>" . $file->title . "</a></td>";
				$content .= "<td>" . elgg_view("output/confirmlink", array("href" => "action/event_manager/event/deletefile?guid=" . $event->getGUID() . "&file=" . $file->file, "text" => elgg_view_icon("delete"))) . "</td>";
				$content .= "</tr>";
			}
			$content .= '</table>';
				
			echo elgg_view_module("main", elgg_echo("event_manager:edit:form:files"), $content);
		}
	}