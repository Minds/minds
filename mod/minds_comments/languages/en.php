<?php

$english = array(
	'minds_comments:save:success' => 'Your comment has been saved',
    /**
     * Comments
     */
    'hj:alive:comments:likebutton' => 'Like',
    'hj:alive:comments:unlikebutton' => 'Unlike',
    'hj:alive:comments:commentsbutton' => 'Comment',
    'hj:alive:comments:sharebutton' => 'Share',
    'hj:alive:comments:viewall' => 'View all %s comments',
    'hj:alive:comments:remainder' => 'View remaining %s comments',
    'hj:alive:comments:nocomments' => 'Be first to comment',
    'hj:comment:commenton' => 'Comment on %s',
    'hj:alive:comments:valuecantbeblank' => 'Comment can not be blank',

    'hj:alive:comments:lang:you' => 'You ',
    'hj:alive:comments:lang:and' => 'and ',
    'hj:alive:comments:lang:others' => 'other people ',
    'hj:alive:comments:lang:othersone' => 'other person ',
    'hj:alive:comments:lang:people' => 'people ',
    'hj:alive:comments:lang:peopleone' => 'person ',
    'hj:alive:comments:lang:likethis' => 'like this',
    'hj:alive:comments:lang:likesthis' => 'likes this',


    'hj:alive:comments:count' => 'comments',
    'hj:alive:comments:comments' => 'comments',
    'hj:alive:comments:delete' => 'Delete',
    'hj:alive:comments:newcomment' => 'Write a comment',

    'hj:alive:comments:addtopic' => 'Add new topic',
    'hj:alive:comments:forumtopictitle' => 'Enter your forum title...',
    'hj:alive:comments:forumtopicdescription' => 'Enter your forum message...',
    'eComents:forumtopicaddbutton' => 'Add',

    'hj:alive:comments:commentmissing' => 'Oh, your comment is missing',
    'hj:alive:comments:bodymissing' => 'Oh, you have not entered any text',
    'hj:alive:comments:topicmissing' => 'Oh, you need to enter a name for your forum topic',

    'hj:alive:comments:commenton' => 'Comment on %s',
    'hj:alive:comments:commentcontent' => '%s: %s',
	'hj:alive:comment_on:river' => 'Comment on an activity: %s',

    'hj:comments:cantfind' => 'Oops, there was a problem adding your comment. The item must have been deleted',
    'hj:comments:savesuccess' => 'Your comment was added successfully',
    'hj:comments:refreshing' => 'Refreshing...',

    'hj:likes:savesuccess' => 'You now like this',
    'hj:likes:saveerror' => 'Sorry, we couldn\'t process your like',
    'hj:likes:likeremoved' => 'Your like was removed',

    /**
     * NOTIFICATIONS
     */
    'hj:comments:notify:activity_type:create' => 'New %s %s added',
    'hj:comments:notify:activity_type:update' => 'Updates to %s %s',
    'hj:comments:notify:activity' => 'activity | <br />%s',

    'hj:comments:notify:post' => 'content | %s %s',

    // Level 1
    'generic_comment:email:level1:subject' => 'You have a new comment',
    'generic_comment:email:level1:body' =>
            "You have a new comment from %s on your %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                You can reply here: <br />
                %s.
            ",

    // Level 2
    'generic_comment:email:level2:subject' => 'New comment in a discussion thread',
    'generic_comment:email:level2:body' =>
            "There is a new comment from %s in a discussion on %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                You can reply here: <br />
                %s.
            ",

    'group_topic_post:email:level1:subject' => 'New post on your group topic',
    'group_topic_post:email:level1:body' =>
            "You have a new post from %s on your %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                You can reply here: <br />
                %s.
            ",

    'group_topic_post:email:level2:subject' => 'New group topic post',
    'group_topic_post:email:level2:body' =>
            "There is a new post from %s in a discussion on %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                You can reply here: <br />
                %s.
            ",

    // Level 1
    'likes:email:level1:subject' => 'You have a new like',
    'likes:email:level1:body' =>
            "%s likes your %s <br />
            ",

    // Level 2
    'likes:email:level2:subject' => 'New like in a discussion thread',
    'likes:email:level2:body' =>
            "%s likes one of the responses in a discussion on %s<br />
                <br />
            ",

    /**
     * LiveSearch
     */
    'hj:alive:search:user' => 'Users',
    'hj:alive:search:group' => 'Groups',
    'hj:alive:search:blog' => 'Blogs',
    'hj:alive:search:bookmarks' => 'Bookmarks',
    'hj:alive:search:file' => 'Files',

	'search_types:group_topic_posts' => 'Discussion posts',
	'hj:alive:reply_to' => 'Reply to topic "%s" in group "%s"',
);

add_translation("en", $english);

?>