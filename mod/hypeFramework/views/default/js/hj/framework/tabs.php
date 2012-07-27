<?php
/**
 * Javascript library to AJAXify tabs
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category AJAX
 * @category Tabs
 * @category Javascript
 *
 */
if (FALSE) :
    ?>
    <script type="text/javascript">
<?php endif; ?>
    elgg.provide('hj.framework.tabs');

    hj.framework.tabs.init = function() {
        $('.hj-ajax-tab-load > a')
        .unbind('.ajaxTabLoad')
        .bind('click.ajaxTabLoad', hj.framework.tabs.load);

        $('li.elgg-state-selected > a')
        .trigger('click.ajaxTabLoad');
    }

    hj.framework.tabs.load = function(event) {

        var action = $(this).attr('href'),
            values = $(this).data('options'),
            target = '#'+values.params.target;
        var loader = '<div class="hj-ajax-loader hj-loader-circle"></div>';

        $(target).show().html(loader);

        $(this)
        .parent()
        .siblings('li')
        .removeClass('elgg-state-selected');

        $(this)
        .parent()
        .addClass('elgg-state-selected');

        elgg.action(action, {
            data : values,
            success : function(output) {
                $(target).html(output.output.data);
                elgg.trigger_hook('success', 'hj:framework:ajax');

            }
        });

        event.preventDefault();

    }

    elgg.register_hook_handler('init', 'system', hj.framework.tabs.init);
    // elgg.register_hook_handler('success', 'hj:framework:ajax', hj.framework.tabs.init);

<?php if (FALSE) : ?></script><?php endif; ?>