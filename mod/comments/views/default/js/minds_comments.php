<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
    elgg.provide('minds.comments');
    
    minds.comments.submitFlag = false;

    minds.comments.init = function() {
	
	var submitFlag = false;
	
    	/**
    	 * Autosize the comments box..
    	 */
        $(document).on('focus', '.comments-input',function(e){ 
        	$(this).autosize();
        });
        
        /**
         * Ajax comments input
         */
        $(document).on('keyup', '.comments-input',function(e){
			e = e || event;
			if (e.keyCode === 13 && !e.ctrlKey && minds.comments.submitFlag==false) {
			    // start your submit function
			    minds.comments.submitFlag = true;
			    $(this).submit();
				$(this).height('25px');
			}
			return true;
		});
		minds.comments.ignoreLoggedOut = false;
		/**
		 * Handler the automatic login of users from popup
		 */
		$(document).on('submit', '#comments-signup #login', function(e){
			e.preventDefault();
			elgg.post('action/login',
				{
					data: { 
						username:$(this).find('input[name=u]').val(),
						password: $(this).find('input[name=p]').val()
					},
					success: function(data){
						$.fancybox.close();
						minds.comments.ignoreLoggedOut = true;
						$('.minds-comments-form').submit();
						location.reload();
					}
				}
			);
		});
		
		$(document).on('submit', '#comments-signup #signup', function(e){
			e.preventDefault();
			elgg.post('action/register',
				{
					data: { 
						u:$(this).find('input[name=u]').val(),
						e:$(this).find('input[name=e]').val(),
						p: $(this).find('input[name=p]').val(),
						tcs: $(this).find('input[name=tcs]').val()
					},
					success: function(data){
						data = $.parseJSON(data);
						if(!data.error){
							$.fancybox.close();
							minds.comments.ignoreLoggedOut = true;
							$('.minds-comments-form').submit();
							location.reload();
						} else {
							alert(data.error.message);
						}
					}
				}
			);
		});
        
		$(document).on('click', '.show-more', minds.comments.more);
       
		$(document).on('submit', '.minds-comments-form', minds.comments.saveComment);
       
		$(document).on('click', '.minds-comment-delete', function(e){
			e.preventDefault();
			_this = $(this);
			$.ajax(elgg.get_site_url() + 'comments/'+$(this).attr('data-guid'), {
				type: 'DELETE',
				success: function(output){
					_this.parents('.minds-comment').remove();
				}
			});
		});
       
      /* if($.cookie('_minds_comment') && elgg.is_logged_in()){
       		 minds.comments.saveCachedComment();
       }*/

    };
    
    minds.comments.more = function(e){
    	e.preventDefault();

		var _this = $(this);
    	var parent_guid = $(this).attr('data-parent-guid');
    	var offset = $(this).parent().attr('data-offset');
    
    	elgg.get('comments/entity/'+parent_guid+'?limit=6&offset='+offset, 
    	{
    		success: function(output){
    			_this.parent().find('.minds-comments').prepend($(output).contents());
    		//	console.log($(output).find('li:first'));
    			_this.parent().attr('data-offset', $(output).find('li:first').attr('data-guid'));
    			$('.elgg-list.mason').masonry().masonry('reloadItems');
    		},
		error: function() {
		    $('#minds-comments-' + parent_guid + ' .show-more').fadeOut();
		}
    	});
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
    	
    	if(!elgg.is_logged_in() && !minds.comments.ignoreLoggedOut){
    		$.fancybox("#comments-signup");
    		return;
    	}
    	
       // $(document).off('submit', '.hj-ajaxed-comment-save');
		_this = $(this);
        var     values = $(this).serialize(),
        action = $(this).attr('action'),
        data = new Object(),
        ref = new Array(),
        id;

 		var input = $('textarea[name="comment"]', $(this));
        data.comment = input.val();

        ref.push(data);

        var input = $('textarea[name="comment"]', $(this));

        input.addClass('minds-processing');

        elgg.post(action, {
        	data: elgg.security.addToken(data),
	    	//contentType : 'application/json',
            success : function(output) {
				//console.log('saving');
                _this.parent().find('.minds-comments').append(output);

                input.removeClass('minds-processing').val('');
                
              	$('.elgg-list.mason').masonry().masonry('reloadItems');
		
		minds.comments.submitFlag = false;
            },
            error: function(out){
            	console.log(out);
            }
        });
 		e.preventDefault();
    }
    
    minds.comments.saveCachedComment = function(e){
	
        if($.cookie('_minds_comment')){
        	//elgg.forward(data.redirect_url);
        	data = JSON.parse($.cookie('_minds_comment'));
        	var action = elgg.normalize_url('action/comment/save');
			elgg.action(action + '?' + 'comment='+data.comment+'&pid='+data.pid + '&type='+data.type, {
				    //contentType : 'application/json',
			            success : function(output) {
							//console.log('saving');
			               var container = $('#minds-comments-'+ data.pid);
			               var commentsList = container.find('ul.hj-syncable .comments .minds-comments:first');
						   commentsList.append(output);
						   //elgg.system_message('minds_comments:save:success');
			            },
			        });
        	$.removeCookie('minds_comment', { path: '/'});
        }    
        return false;	
    }

    elgg.register_hook_handler('init', 'system', minds.comments.init);
	
	
<?php if (FALSE) : ?></script><?php endif; ?>
