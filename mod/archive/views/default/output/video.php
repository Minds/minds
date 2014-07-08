<video class="<?php $vars['class'];?>" width="<?= $vars['width'] ?>" height="<?= $vars['height'] ?>">
        <?php foreach($vars['sources'] as $uri => $type):?>
        <source src="<?= $uri?>" type="<?= $type ?>">
        <?php endforeach; ?>
</video>

