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
        
        $('body').on('click', '.hj-comments-summary', minds.comments.more);
       
       $('body').on('submit', '.hj-ajaxed-comment-save', minds.comments.saveComment);
       
      /* if($.cookie('_minds_comment') && elgg.is_logged_in()){
       		 minds.comments.saveCachedComment();
       }*/

    };
    
    minds.comments.more = function(e){
    	e.preventDefault();
    	 var ref = new Array();
         var data = new Object();
               	
         var form = $(this).parents('.minds-comments-bar').parent().find('form');

      	data.pid = $('input[name="pid"]', form).val();
        data.type = $('input[name="type"]', form).val();
       
        ref.push(data);
        console.log(data);
        
        var bar_loader = '<div class="hj-ajax-loader hj-loader-bar"></div>';
		$(this).css({'height':'16px'}).html(bar_loader);
        minds.comments.refresh(ref, 'old');
    }

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
        data.comment = encodeURIComponent($('textarea[name="comment"]', $(this)).val());

        ref.push(data);

        var input = $('textarea[name="comment"]', $(this));

        input.addClass('hj-input-processing');
        
        /*
         * If the user is not logged in the send them to the login page
         */
        if(!elgg.is_logged_in()){
       		//create a cookie with the comment info
       		var url = window.location.href;
       		//if homepage then we redirect to news (we will presume the user is not already on news for the time being as we dont have a site link)
			if(url == elgg.get_site_url()){
				data.redirect_url = url;
			} else if(url.indexOf(elgg.get_site_url()+'news') > -1) {
				data.redirect_url = url + '/news/single/?id='+data.pid;
			} else {
				data.redirect_url = url;
			}
       		$.cookie('minds_comment',	JSON.stringify(data), { path: '/'});
       		elgg.register_error('You must login or create and account before your comments can be saved');
       		setTimeout("elgg.forward('login')", 1000)
       		return true;
        }

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
                
              	$('.elgg-list.mason').masonry('reloadItems').masonry();
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