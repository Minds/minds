/*  vim: set filetype=javascript: */

elgg.provide('oauth2');

oauth2.init = function() {

};

oauth2.toggleSecret = function(e) {

    var a = $(e);
    var s = a.parent().next().children(':first-child');

    s.toggle();

    if (a.is(':visible')) {
        a.text('hide');
    } else {
        a.text('show');
    }
}

oauth2.regenerateSecret = function() {

    if (!confirm('<?php echo elgg_echo('oauth2:regenerate:confirm'); ?>')) {
        return false;
    }

    $.get(elgg.config.wwwroot + 'oauth2/regenerate', function(data) {
        $('#oauth2-secret-td').text(data);
        $('#oauth2-secret-input').val(data);
    });
}

elgg.register_hook_handler('init', 'system', oauth2.init);
