<?php
/**
 * dislikes English language file
 */

$english = array(
	'dislikes:this' => 'disliked this',
	'dislikes:deleted' => 'Your dislike has been removed',
	'dislikes:see' => 'See who disliked this',
	'dislikes:remove' => 'Undislike this',
	'dislikes:notdeleted' => 'There was a problem removing your dislike',
	'dislikes:dislikes' => 'You now dislike this item',
	'dislikes:failure' => 'There was a problem disliking this item',
	'dislikes:alreadyliked' => 'You have already disliked this item',
	'dislikes:notfound' => 'The item you are trying to dislike cannot be found',
	'dislikes:dislikethis' => 'disLike this',
	'dislikes:userdislikedthis' => '%s dislike',
	'dislikes:usersdislikedthis' => '%s dislikes',
	'dislikes:river:annotate' => 'dislikes',

	'river:dislikes' => 'dislikes %s %s',

	// notifications. yikes.
	'dislikes:notifications:subject' => '%s dislikes your post "%s"',
	'dislikes:notifications:body' =>
'Hi %1$s,

%2$s dislikes your post "%3$s" on %4$s

See your original post here:

%5$s

or view %2$s\'s profile here:

%6$s

Thanks,
%4$s
',
	
);

add_translation('en', $english);
