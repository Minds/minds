<?php if (FALSE) : ?>
<script type="text/javascript">
<?php endif; ?>
    
    elgg.provide('hj.framework.fieldcheck');
    
    hj.framework.fieldcheck.init = function(form_container) {
        var check = true;
        $('[mandatory="1"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[mandatory="true"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[mandatory="yes"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[mandatory="mandatory"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        if (!check) alert(elgg.echo('hj:framework:formcheck:fieldmissing'));
        return check;
    }
    
    elgg.register_hook_handler('init', 'system', hj.framework.fieldcheck.init);
    elgg.register_hook_handler('success', 'hj:framework:ajax', hj.framework.fieldcheck.init);
    
<?php if (FALSE) : ?></script><?php endif; ?>