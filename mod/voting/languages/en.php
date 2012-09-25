<?php

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'poll' => "Vote",
            'polls:add' => "New Vote",
			'polls' => "Voting",
			'polls:votes' => "votes",
			'polls:user' => "%s's vote",
			'polls:group_polls' => "Group votes",
			'polls:group_polls:listing:title' => "%s's votes",
			'polls:user:friends' => "%s's friends' vote",
			'polls:your' => "Your votes",
			'polls:not_me' => "%s's votes",
			'polls:posttitle' => "%s's votes: %s",
			'polls:friends' => "Friends' votes",
			'polls:not_me_friends' => "%s's friend's votes",
			'polls:yourfriends' => "Your friends' latest votes",
			'polls:everyone' => "All site votes",
			'polls:read' => "Read vote",
			'polls:addpost' => "Create a vote",
			'polls:editpost' => "Edit a vote: %s",
			'polls:edit' => "Edit a vote",
			'polls:text' => "Vote text",
			'polls:strapline' => "%s",			
			'item:object:poll' => 'Votes',
			'item:object:poll_choice' => "Vote choices",
			'polls:question' => "Vote question",
			'polls:responses' => "Response choices",
			'polls:results' => "[+] Show the results",
			'polls:show_results' => "Show results",
			'polls:show_poll' => "Show vote",
			'polls:add_choice' => "Add response choice",
			'polls:delete_choice' => "Delete this choice",
			'polls:settings:group:title' => "Group votes",
			'polls:settings:group_polls_default' => "yes, on by default",
			'polls:settings:group_polls_not_default' => "yes, off by default",
			'polls:settings:no' => "no",
			'polls:settings:group_profile_display:title' => "If group votes are activated, where should votes content be displayed in group profiles?",
			'polls:settings:group_profile_display_option:left' => "left",
			'polls:settings:group_profile_display_option:right' => "right",
			'polls:settings:group_profile_display_option:none' => "none",
			'polls:settings:group_access:title' => "If group votes are activated, who gets to create polls?",
			'polls:settings:group_access:admins' => "group owners and admins only",
			'polls:settings:group_access:members' => "any group member",
			'polls:settings:front_page:title' => "Admins can set a front page poll (requires theme support)",
			'polls:none' => "No votes found.",
			'polls:permission_error' => "You do not have permission to edit this vote.",
			'polls:vote' => "Vote",
			'polls:login' => "Please login if you would like to vote in this vote.",
			'group:polls:empty' => "No polls",
			'polls:settings:site_access:title' => "Who can create site-wide votes?",
			'polls:settings:site_access:admins' => "Admins only",
			'polls:settings:site_access:all' => "Any logged-in user",
			'polls:can_not_create' => "You do not have permission to create votes.",
			'polls:front_page_label' => "Place this vote on the front page.",
		/**
	     * poll widget
	     **/
			'polls:latest_widget_title' => "Latest community votes",
			'polls:latest_widget_description' => "Displays the most recent votes.",
			'polls:my_widget_title' => "My votes",
			'polls:my_widget_description' => "This widget will display your votes.",
			'polls:widget:label:displaynum' => "How many votes you want to display?",
			'polls:individual' => "Latest vote",
			'poll_individual_group:widget:description' => "Display the latest vote for this group.",
			'poll_individual:widget:description' => "Display your latest vote",
			'polls:widget:no_polls' => "There are no votes for %s yet.",
			'polls:widget:nonefound' => "No votes found.",
			'polls:widget:think' => "Let %s know what you think!",
			'polls:enable_polls' => "Enable votes",
			'polls:group_identifier' => "(in %s)",
			'polls:noun_response' => "response",
			'polls:noun_responses' => "responses",
	        'polls:settings:yes' => "yes",
			'polls:settings:no' => "no",
			
         /**
	     * poll river
	     **/
	        'polls:settings:create_in_river:title' => "Show vote creation in activity river",
			'polls:settings:vote_in_river:title' => "Show vote voting in activity river",
			'river:create:object:poll' => '%s created a voed %s',
			'river:vote:object:poll' => '%s voted on %s',
			'river:comment:object:poll' => '%s commented on %s',
		/**
		 * Status messages
		 */
	
			'polls:added' => "Your vote was created.",
			'polls:edited' => "Your vote was saved.",
			'polls:responded' => "Thank you for responding, your vote was recorded.",
			'polls:deleted' => "Your vote was successfully deleted.",
			'polls:totalvotes' => "Total number of votes: ",
			'polls:voted' => "Your vote has been cast. Thank you for voting.",
			
	
		/**
		 * Error messages
		 */
	
			'polls:save:failure' => "Your vote could not be saved. Please try again.",
			'polls:blank' => "Sorry: you need to fill in both the question and responses before you can make a vote.",
			'polls:novote' => "Sorry: you need to choose an option to vote in this vote.",
			'polls:notfound' => "Sorry: we could not find the specified vote.",
			'polls:nonefound' => "No polls were found from %s",
			'polls:notdeleted' => "Sorry: we could not delete this vote.",
		
		/**
		 * Filters
		 */
		 	'polls:top' => 'Top',
		 	'polls:history' => 'History',
	);
					
	add_translation("en",$english);

?>