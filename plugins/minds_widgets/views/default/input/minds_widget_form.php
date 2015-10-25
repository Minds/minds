<div id="minds-widget-<?php echo $vars['tab'];?>" class="minds-widget-form">

    <p><?php echo  elgg_echo("minds_widgets:{$vars['tab']}:comments");?></p>
    
    <form>
        <?php echo  $vars['form-body']; ?>
        
        <input id="minds-widget-<?php echo $vars['tab'];?>-submit" class="elgg-button" type="submit" value="Get the code..." />
    </form>
    
    <div class="get-the-code" style="display:none; padding:20px; margin-top: 30px; background-color: #ddd;" title="<?php echo  elgg_echo('minds_widgets:tab:'.$vars['tab']); ?>">
        <textarea></textarea>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#minds-widget-<?php echo $vars['tab'];?> form').submit(function() {
            
            /*$('#minds-widget-<?php echo $vars['tab'];?> textarea').load(elgg.config.wwwroot + "widgets/<?php echo $vars['tab']; ?>/getcode", $(this).serialize()); */
            
            $.ajax({  
                type: "GET",  
                url: elgg.config.wwwroot + "widgets/<?php echo $vars['tab']; ?>/getcode",
                data: $(this).serialize(),  
                dataType: "text",  

                success: function(msg, status, jqXHR){  
                    $('#minds-widget-<?php echo $vars['tab'];?> div.get-the-code textarea').val(jqXHR.responseText);
                    $('#minds-widget-<?php echo $vars['tab'];?> div.get-the-code').fadeIn();
                    $('#minds-widget-<?php echo $vars['tab'];?> textarea').select();
                },  
            });  
            
            
            
            return false;
        });
    });
</script>