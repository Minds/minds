<?php

echo '<ul class="minds-comments">';
foreach($vars['comments'] as $comment){
	echo '<li class="minds-comment" data-guid="'.$comment->guid.'">';
	echo $comment->view();
	echo '</li>';
}
echo '</ul>';