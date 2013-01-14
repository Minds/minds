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
        
        $('body').on('focus', '.comments-input',function(e){ $(this).autosize();});
        
        $('body').on('keyup', '.comments-input',function(e){
															  e = e || event;
															  if (e.keyCode === 13 && !e.ctrlKey) {
															    // start your submit function
															    $(this).submit();
															    $(this).height('25px');
															  }
															  return true;
															 });

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
               	var data = new Object();
               	
               	var form = $(this).parents('.elgg-river-responses').find('form');
               	console.log($(this).parents());

      		  	data.pid = $('input[name="pid"]', form).val();
        		data.type = $('input[name="type"]', form).val();
       
                ref.push(data);
                console.log(data);
                $(this).css({'height':'16px'}).html(bar_loader);
                hj.comments.refresh(ref, 'old');
            });
        });

       /* $('.hj-ajaxed-comment-save')
        .removeAttr('onsubmit')
        .unbind('submit')
        .bind('submit', hj.comments.saveComment);*/
       
       $('body').on('submit', '.hj-ajaxed-comment-save', hj.comments.saveComment);

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
                	console.log(data);
                    if (data && data.output != 'null') {
                        $.each(data.output, function(key, val) {
                            var container = $('#minds-comments-'+ val.pid);
                            var commentsList = container.find('ul.hj-syncable .comments .minds-comments:first');
                            val.comments.reverse();
                            $.each(val.comments, function(key2, val2) {
                                var new_item = $(val2).show();
                                if (sync == 'new') {
                                    commentsList.append(new_item);
                                    console.log(new_item);
                                } else {
                                    commentsList
                                    .prepend(new_item);
                                    console.log(new_item);
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
    
    hj.comments.saveCommentNew = function(event) {
    	        
        event.preventDefault();
        
    	console.log('submit');
    }

    hj.comments.saveComment = function(e) {
    	        
        e.preventDefault();
        $('body').off('submit', '.hj-ajaxed-comment-save');
        console.log('hit');

        var     values = $(this).serialize(),
        action = $(this).attr('action'),
        data = new Object(),
        ref = new Array(),
        id,
        container, commentsList;

        data.pid = $('input[name="pid"]', $(this)).val();
        data.type = $('input[name="type"]', $(this)).val();
        
        container = $('#hj-annotations-'+ id);
        commentsList = container.find('ul.hj-syncable.hj-comments:first');

        ref.push(data);

        var input = $('textarea[name="comment"]', $(this));

        input.addClass('hj-input-processing');

        elgg.action(action + '?' + values, {
	    contentType : 'application/json',
            success : function(output) {
				console.log('saving');
               // hj.comments.refresh(ref, 'new');
                var container = $('#minds-comments-'+ data.pid);
                var commentsList = container.find('ul.hj-syncable .comments .minds-comments:first');
				commentsList.append(output);

                input
                .removeClass('hj-input-processing')
                .val('')
                .parents('div.hj-comments-input:first')
                .toggle();
                window.ajaxcommentsready = true;
                    elgg.trigger_hook('success', 'hj:framework:ajax');
            }
        });

    }

    elgg.register_hook_handler('init', 'system', hj.comments.init);
    //elgg.register_hook_handler('init', 'system', hj.comments.triggerRefresh);
    elgg.register_hook_handler('success', 'hj:framework:ajax', hj.comments.init, 500);
	
	
	
<?php if (FALSE) : ?></script><?php endif; ?>