<?php
echo '<ul class="minds-comments">';
if($vars['comments']){
	foreach($vars['comments'] as $comment){
        if(!$comment instanceof Minds\plugin\comments\entities\comment){
               continue;
        }
        echo '<li class="minds-comment" data-guid="'.$comment->guid.'">';
		echo $comment->view();
		echo '</li>';
	}
}
echo '</ul>';
