<?php
$topbar = elgg_view('page/elements/topbar', $vars);
?><!DOCTYPE html>
<html lang="en">
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body class="<?php echo $class;?>">
<div class="hero elgg-page elgg-page-default <?php echo $class;?>">
	<div class="topbar">
		<div class="inner">
			<?php echo $topbar; ?>
		</div>
	</div>
</div>
    <script>
        $(document).ready(function() {
            $("a").attr("target", "_blank");
            $("form").attr("target", "_blank");
        });
        </script>
</body>
</html>
