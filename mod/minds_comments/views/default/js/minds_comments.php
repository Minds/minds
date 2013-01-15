<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
    elgg.provide('minds.comments');

    minds.comments.init = function() {
       
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
                minds.comments.refresh(ref, 'old');
            });
        });
       
       $('body').on('submit', '.hj-ajaxed-comment-save', minds.comments.saveComment);

    };

    minds.comments.refresh = function(data, sync) {
		//console.log('loaded');
        if (!data.length) return true;

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
                                    //console.log(new_item);
                                } else {
                                    commentsList
                                    .prepend(new_item);
                                    //console.log(new_item);
                                }
                            });
                        });
                    }
                    $('.hj-comments-summary').has('.hj-ajax-loader').each(function() {$(this).hide()});
                }
            });
       
    }
    
    minds.comments.saveComment = function(e) {
    	        
       // $('body').off('submit', '.hj-ajaxed-comment-save');

        var     values = $(this).serialize(),
        action = $(this).attr('action'),
        data = new Object(),
        ref = new Array(),
        id,
        container, commentsList;

        data.pid = $('input[name="pid"]', $(this)).val();
        data.type = $('input[name="type"]', $(this)).val();

        ref.push(data);

        var input = $('textarea[name="comment"]', $(this));

        input.addClass('hj-input-processing');

        elgg.action(action + '?' + values, {
	    contentType : 'application/json',
            success : function(output) {
				//console.log('saving');
                var container = $('#minds-comments-'+ data.pid);
                var commentsList = container.find('ul.hj-syncable .comments .minds-comments:first');
				commentsList.append(output);

                input
                .removeClass('hj-input-processing')
                .val('')
                .parents('div.hj-comments-input:first')
                .toggle();
            }
        });
 		e.preventDefault();
    }

    elgg.register_hook_handler('init', 'system', minds.comments.init);
	
	
<?php if (FALSE) : ?></script><?php endif; ?>