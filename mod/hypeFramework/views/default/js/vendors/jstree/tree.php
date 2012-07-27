<?php
echo elgg_view('js/vendors/jstree/jquery.jstree.js');
?>
<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
    elgg.provide('hj.framework.tree');

    hj.framework.tree.init = function() {
        $('.hj-list-tree')
        .each(function() {

            $('#' + $(this).attr('id'))
            .jstree({
                // the `plugins` array allows you to configure the active plugins on this instance
                "plugins" : ["themes","html_data","ui","crrm"],
                // each plugin you have included can have its own config object
                "core" : { "initially_open" : [ "phtml_1" ] },
                "themes" : {
                    "icons" : false

                }

            }
            )
            // EVENTS
            // each instance triggers its own events - to process those listen on the container
            // all events are in the `.jstree` namespace
            // so listen for `function_name`.`jstree` - you can function names from the docs
            .bind("loaded.jstree", function (event, data) {
                // you get two params - event & data - check the core docs for a detailed description
            });
        });
    };

    elgg.register_hook_handler('init', 'system', hj.framework.tree.init);
    elgg.register_hook_handler('success', 'hj:framework:ajax', hj.framework.tree.init);
<?php if (FALSE) : ?></script><?php endif; ?>