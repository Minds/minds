<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
elgg.provide('hj.framework.relationshiptags');

hj.framework.relationshiptags.init = function() {

    var source_entities = hj.framework.relationshiptags.sourceentities;

    $('.relationship-tag-remove')
    .die()
    .live('click', function(event) {
        event.preventDefault();
        $(this).parents('li:first').remove();
    });

    $('.hj-relationship-tags-autocomplete')
    .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "autocomplete" ).menu.active ) {
            event.preventDefault();
        }
    })
    .autocomplete({
        source: function( request, response ) {
            // delegate back to autocomplete, but extract the last term
            response( $.ui.autocomplete.filter(
            source_entities, request.term ) );
        },
        focus: function() {
            // prevent value inserted on focus
            return false;
        },
        select: function( event, ui ) {
            var tag = '<li class="relationship-tag hj-padding-ten clearfix">\n\
                            <span class="hj-left hj-padding-ten"><img src="' + ui.item.icon + '" /></span>\n\
                            <span class="hj-left hj-padding-ten">' + ui.item.value + '</span>\n\
                            <a class="relationship-tag-remove hj-right">' + elgg.echo('remove') + '</a>\n\
                            <input type="hidden" name="relationship_tag_guids[]" value="' + ui.item.guid + '" />\n\
                        </li>';
            $('ul#relationship-tags').prepend(tag);
            this.value = '';
            return false;
        }
    });

};

elgg.register_hook_handler('init', 'system', hj.framework.relationshiptags.init);
elgg.register_hook_handler('success', 'hj:framework:ajax', hj.framework.relationshiptags.init, 500);
<?php if (FALSE) : ?></script><?php endif; ?>