<?php
/* templates mustache wrapper */

echo '<!-- Mustaches Templates --><div class="hidden">';
echo elgg_view('deck_river/mustaches/main_templates');
echo elgg_view('deck_river/mustaches/linkbox');
echo elgg_view('deck_river/mustaches/twitter');
echo elgg_view('deck_river/mustaches/facebook');
echo '</div><div id="fb-root"></div>';