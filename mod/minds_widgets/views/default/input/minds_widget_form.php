<div id="minds-widget-<?=$vars['tab'];?>" class="minds-widget-form">

    <p><?= elgg_echo("minds_widgets:{$vars['tab']}:comments");?></p>
    
    <form>
        <?= $vars['form-body']; ?>
        
        <input id="minds-widget-<?=$vars['tab'];?>-submit" type="submit" value="Get the code..." />
    </form>
    
    <div class="get-the-code" style="display:none; padding:20px; margin-top: 30px; background-color: #ddd;" title="<?= elgg_echo('minds_widgets:tab:'.$vars['tab']); ?>">
        <textarea></textarea>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#minds-widget-<?=$vars['tab'];?> form').submit(function() {
            
            $('#minds-widget-<?=$vars['tab'];?> textarea').load("<?= current_page_url() ?>/getcode", $(this).serialize());
            
             $('#minds-widget-<?=$vars['tab'];?> div.get-the-code').fadeIn();
            
            return false;
        });
    });
</script>