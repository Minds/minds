<?php
header("Content-type: text/html; charset=UTF-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo $vars['title']; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <?php echo $vars['head']; ?>
    </head>
    <body style="background:white;">
        <div style="font-size:12px;width:100%;max-width:650px;margin:0 auto;">
        <?php
            echo $vars['body'];
        ?>
        </div>
    </body>
</html>