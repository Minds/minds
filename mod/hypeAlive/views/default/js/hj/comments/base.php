<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
    elgg.provide('hj.comments');

    /**
     *  Initialize hypeAlive JS
     */
    hj.comments.init = function() {
        if(window.ajaxcommentsready === undefined) {
            window.ajaxcommentsready = true;
        }
        //hj.comments.triggerRefresh();

        var bar_loader = '<div class="hj-ajax-loader hj-loader-bar"></div>';

        // Show comment input on click
        $('.elgg-menu-item-comment')
        .unbind('click')
        .bind('click', function(event) {
            event.preventDefault();

            $(this)
            .parents('div.hj-annotations-menu')
            .siblings('ul:first')
            .find('.hj-comments-input:first')
            .toggle();
        });

        // Toggle loading of older comments
        $('.hj-comments-summary')
        .each(function() {
            $(this)
            .unbind('click')
            .bind('click', function(event) {
                event.preventDefault();
                var ref = new Array();
                var commentsList = $(this).siblings('.hj-comments-list:first').children('.hj-syncable.hj-comments:first');
                var last = $('li.elgg-item:last', commentsList);
                if (!last.length) {
                    last = commentsList;
                }
                var data = commentsList.data('options');
                data.timestamp = last.data('timestamp');
                ref.push(data);
                $(this).css({'height':'16px'}).html(bar_loader);
                hj.comments.refresh(ref, 'old');
            });
        });

        $('.hj-ajaxed-comment-save')
        .removeAttr('onsubmit')
        .unbind('submit')
        .bind('submit', hj.comments.saveComment);

    };

    hj.comments.triggerRefresh = function() {
        var time = 25000;
        if (!window.commentstimer) {
            window.commentstimer = true;
            var refresh_comments = window.setTimeout(function(){
                var ref = new Array();
                // Let's get the timestamp of the first item in the list (newest comment)
                $('.hj-syncable.hj-comments')
                .each(function() {
                    var first = $('li.elgg-item:first', $(this));
                    if (!first.length) {
                        first = $(this);
                    }
                    var data = $(this).data('options');
                    data.timestamp = first.data('timestamp');
                    ref.push(data);
                });
                if (window.ajaxcommentsready) {
                    //elgg.system_message(elgg.echo('hj:comments:refreshing'));
                    hj.comments.refresh(ref, 'new');
                }
                window.commentstimer = false;
            }, time);
        }
    }

    hj.comments.refresh = function(data, sync) {
		console.log('loaded');
        if (!data.length) return true;
        if (window.ajaxcommentsready || sync == 'old') {
            window.ajaxcommentsready = false;
            elgg.action('action/comment/get', {
                data : {
                    sync : sync,
                    data : data
                },
                success : function(data) {
                    if (data && data.output != 'null') {
                        $.each(data.output, function(key, val) {
                            var container = $('#hj-annotations-'+ val.id);
                            var commentsList = container.find('ul.hj-syncable.hj-comments:first');
                            $.each(val.comments, function(key2, val2) {
                                var new_item = $(val2).hide();
                                if (sync == 'new') {
                                    commentsList.append(new_item.fadeIn(1000));
                                } else {
                                    commentsList
                                    .prepend(new_item.fadeIn(1000));
                                }
                            });
                        });
                    }
                    $('.hj-comments-summary').has('.hj-ajax-loader').each(function() {$(this).hide()});
                    window.ajaxcommentsready = true;
                    elgg.trigger_hook('success', 'hj:framework:ajax');
                }
            });
        }
    }

    hj.comments.saveComment = function(event) {
        event.preventDefault();

        var     values = $(this).serialize(),
        action = $(this).attr('action'),
        data = new Object(),
        ref = new Array(),
        id,
        container, commentsList;

        data.container_guid = $('input[name="container_guid"]', $(this)).val();
        data.river_id = $('input[name="river_id"]', $(this)).val();
        data.aname = $('input[name="aname"]', $(this)).val();

        if (data.river_id) {
            id = data.river_id
        } else {
            id = data.container_guid
        }
        container = $('#hj-annotations-'+ id);
        commentsList = container.find('ul.hj-syncable.hj-comments:first');

        data.timestamp = $('li.elgg-item:first', commentsList).data('timestamp');
        ref.push(data);

        var input = $('input[name="annotation_value"]', $(this));

        input
        .addClass('hj-input-processing');

        elgg.action(action + '?' + values, {
	    contentType : 'application/json',
            success : function(output) {
				console.log('saving');
                hj.comments.refresh(ref, 'new');

                input
                .removeClass('hj-input-processing')
                .val('')
                .parents('div.hj-comments-input:first')
                .toggle();
            }
        });
    }

    elgg.register_hook_handler('init', 'system', hj.comments.init);
    //elgg.register_hook_handler('init', 'system', hj.comments.triggerRefresh);
    elgg.register_hook_handler('success', 'hj:framework:ajax', hj.comments.init, 500);
	
	
	
<?php if (FALSE) : ?></script><?php endif; ?>