<?php

    global $CONFIG;
    
    $current_theme = elgg_get_plugin_setting('activated_theme', 'minds_theme_selector');
    
    
    foreach ($CONFIG->available_themes as $theme) {
        
        $plugin = elgg_get_plugin_from_id($theme);
        if ($plugin) {
            
            $description = elgg_view('output/longtext', array('value' => $plugin->getManifest()->getDescription()));
            $author = '<span>' . elgg_echo('admin:plugins:label:author') . '</span>: '
                                    . elgg_view('output/text', array('value' => $plugin->getManifest()->getAuthor()));
            $version = htmlspecialchars($plugin->getManifest()->getVersion());
            $website = elgg_view('output/url', array(
                    'href' => $plugin->getManifest()->getWebsite(),
                    'text' => $plugin->getManifest()->getWebsite(),
                    'is_trusted' => true,
            ));
            
        ?>
        
<div class="theme theme-<?php echo $theme; ?> <?php if ($current_theme == $theme) echo 'selected'; ?>">
    
    <h1><?php echo $plugin->getFriendlyName(); ?></h1>
    
    <div class="preview_image">
        <?php echo elgg_view('theme/preview/'.$theme); ?>
    </div>
    
    <ul>
        <li><strong>Version: </strong><?php echo $version;?></li>
        <li><strong>By: </strong><?php echo $author;?></li>
        <li><strong>Website: </strong><?php echo $website;?></li>
    </ul>
    <p><?php echo $description; ?></p>
    
    <input type="radio" name="activated" value="<?php echo $theme; ?>" <?php if ($current_theme == $theme) echo 'checked'; ?> /> Activate "<?php echo $plugin->getFriendlyName(); ?>"
</div>
        
        <?php
        }
    }
    
    echo elgg_view('input/submit', array('value' => elgg_echo('save')));
