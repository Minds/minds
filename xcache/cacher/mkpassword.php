<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
<html>
	<head>
		<title>Simple MD5 password generator</title>
		<style stype="text/css">
			code pre { display: block; border: 1px dashed black; padding: 10px; }
		</style>
	</head>
	<body>
		<h1>Simple MD5 password generator</h1>
		<form method="post">
			<fieldset>
				Name:<br />
				<input type="text" name="username"><br />
				Password:<br />
				<input type="password" name="password"><br />
				<input type="submit"><br />
				The INI settings you need is:
				<code>
				<pre><?php
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					$md5 = md5(@ $_POST['password']);
					$offs = mt_rand(0 + 1, 31 - 1);
					$md5_1 = substr($md5, 0, $offs);
					$md5_2 = substr($md5, $offs);
					$username = htmlspecialchars($_POST['username']);
					echo "xcache.admin.user=\"$username\"\n";
					echo "xcache.admin.pass=\"<span>$md5_1</span><span>$md5_2</span>\"\n";
				}
				?></pre>
				</code>
			</fieldset>
		</form>
	</body>
</html>
