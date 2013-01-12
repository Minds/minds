<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
    elgg.provide('hj.likes');

    hj.likes.init = function() {
        if(window.ajaxlikessready === undefined) {
            window.ajaxlikesready = true;
        }
        hj.likes.triggerRefresh();

        var bar_loader = '<div class="hj-ajax-loader hj-loader-bar"></div>';

        // Show comment input on click
        $('.elgg-menu-item-like')
        .unbind('click')
        .bind('click', hj.likes.saveLike);

        $('.elgg-menu-item-unlike')
        .unbind('click')
        .bind('click', hj.likes.saveLike);
		
		 $('.elgg-menu-item-dislike')
        .unbind('click')
        .bind('click', hj.likes.saveDislike);
		
		$('.elgg-menu-item-undislike')
        .unbind('click')
        .bind('click', hj.likes.saveDislike);


        $('.hj-swap-value')
        .unbind('click')
        .bind('click', hj.likes.swapValue);

    };

    hj.likes.triggerRefresh = function() {
        var time = 25000;
        if (!window.likestimer) {
            window.likestimer = true;
            var refresh_likes = window.setTimeout(function(){
                var ref = new Array();
                // Let's get the timestamp of the first item in the list (newest comment)
                $('.hj-likes-summary')
                .each(function() {
                    var data = $(this).data('options');
                });
                if (window.ajaxlikesready) {
                    //elgg.system_message(elgg.echo('hj:comments:refreshing'));
                    hj.likes.refresh(ref);
                }
                window.likestimer = false;
            }, time);
        }
    }

    hj.likes.refresh = function(data) {
        if (!data.length) return true;
        if (window.ajaxlikesready) {
            window.ajaxlikesready = false;
            elgg.action('action/like/get', {
                data : {
                    data : data
                },
                success : function(data) {
                    if (data && data.output != 'null') {
                        $.each(data.output, function(key, val) {
                            var container = $('#hj-annotations-'+ val.id);
                            var likesList = container.find('div.likes:last');
                            if (val.likes) {
                                likesList.html(val.likes);
                                likesList.show();
                            } else {
                                likesList.hide();
                            }
                            var menu = container.find('.hj-annotations-menu');
                            if (val.self) {
                                $('.elgg-menu-item-like:first > a', menu)
                                .addClass('hidden');
                                $('.elgg-menu-item-unlike:first > a', menu)
                                .removeClass('hidden');
                            } else {
                                $('.elgg-menu-item-like:first > a', menu)
                                .removeClass('hidden');
                                $('.elgg-menu-item-unlike:first > a', menu)
                                .addClass('hidden');
                            }
                        });
                    }
                    window.ajaxlikesready = true;
                    elgg.trigger_hook('success', 'hj:framework:ajax');
                }
            });
        }
    }

    hj.likes.saveLike = function(event) {
        event.preventDefault();

        var action_type = $(this).find('a:first').attr('rel');
        var likesList = $(this)
        .parents('div.hj-annotations-menu')
        .siblings('ul:first')
        .find('div.likes:last')
        .find('.hj-likes-summary:last');

        var data = new Object();
        var ref = new Array();

        data = likesList.data('options');

        data.action_type = action_type;
        ref.push(data);

	//elgg.system_message(elgg.echo('hj:framework:processing'));
        elgg.action('action/like/save', {
            data : data,
            success : function(output) {
                hj.likes.refresh(ref);
            }
        });
    }
	
	 hj.likes.saveDislike = function(event) {
        event.preventDefault();

        var action_type = $(this).find('a:first').attr('rel');
        var likesList = $(this)
        .parents('div.hj-annotations-menu')
        .siblings('ul:first')
        .find('div.likes:last')
        .find('.hj-likes-summary:last');

        var data = new Object();
        var ref = new Array();

        data = likesList.data('options');

        data.action_type = action_type;
        ref.push(data);

	//elgg.system_message(elgg.echo('hj:framework:processing'));
        elgg.action('action/like/save-dislike', {
            data : data,
            success : function(output) {
                hj.likes.refresh(ref);
            }
        });
    }

    hj.likes.swapValue = function(event) {
        event.preventDefault();
        $(this).html($(this).data('options'));
    }

    elgg.register_hook_handler('init', 'system', hj.likes.init);
    elgg.register_hook_handler('success', 'hj:framework:ajax', hj.likes.init, 500);
<?php if (FALSE) : ?></script><?php endif; ?>