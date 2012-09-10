<?php

// TODO: add ability to reorder poll questions?
$poll = elgg_extract('poll', $vars);
$body = '';
$i = 0;

if ($poll) {
	$choices = polls_get_choices($poll);
	if ($choices) {
		foreach($choices as $choice) {
			$body .= '<div id="choice_container_'.$i.'">';
			$body .= elgg_view('input/text',
				array(	'name'	=>	'choice_text_'.$i,
						'value' 		=> 	$choice->text,
						'class'			=> 	'input-poll-choice'
				)
			);
			$body .= '<a href="#" alt="'.elgg_echo('polls:delete_choice').'" title="'.elgg_echo('polls:delete_choice').' id="choice_delete_'.$i.'" onclick="javascript:polls_delete_choice('.$i.'); return false;">';
			$body .= '<img src="'.$vars['url'].'mod/polls/graphics/16-em-cross.png"></a>';
			$body .= '</div>';
			
			$i += 1;
		}
	}
}

$body .= elgg_view('input/hidden',
	array(	
		'name'	=>	'number_of_choices',
		'id'	=>	'number_of_choices',
		'value' 		=> 	$i,
	)
);

$body .= '<div id="new_choices_area"></div>';

$body .= elgg_view('input/button',
	array(
		'id'	=>	'add_choice',
		'value' 		=> 	elgg_echo('polls:add_choice'),
		'type' 			=> 	'button'
	)
);

echo $body;
?>
<script type="text/javascript">
$('#add_choice').click(
	function() {
		var cnum = parseInt($('#number_of_choices').val());
		$('#number_of_choices').val(cnum+1);
		var new_html = '<div id="choice_container_'+cnum+'">';
		new_html += '<input type="text" class="input-poll-choice" name="choice_text_'+cnum+'"> ';
		new_html += '<a href="#" title="<?php echo elgg_echo('polls:delete_choice'); ?>" alt="<?php echo elgg_echo('polls:delete_choice'); ?>" id="choice_delete_'+cnum+'" onclick="javascript:polls_delete_choice('+cnum+'); return false;">';
		new_html += '<img src="<?php echo $vars['url']; ?>mod/polls/graphics/16-em-cross.png"></a>'
		new_html += '</div>';
		$('#new_choices_area').append(new_html);
	}
);

function polls_delete_choice(cnum) {
	$("#choice_container_"+cnum).remove();
}

</script>