<?php

$sections = $vars['sections'];

foreach($sections as $section):?>

	<section class="cms-section">
		<div class="container">
			<div class="left">
				<div class="cell">
					<h2>Left</h2>
					<p>Left example...</p>
				</div>
			</div>
			<div class="right">
				<div class="cell">
					<h2>Right</h2>
					<p>Right example...</p>
				</div>
			</div>
		</div>
	</section>
	
<?php endforeach;