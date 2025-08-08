<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PHP Error</title>
	<style type="text/css">
		body { background-color: #fff; margin: 40px; font: 13px/20px normal Helvetica, Arial, sans-serif; color: #4F5155; }
		h1 { color: #990000; font-size: 19px; font-weight: normal; margin: 0 0 14px 0; }
		p { margin: 12px 15px 12px 15px; }
	</style>
</head>
<body>
<h1><?php echo isset($heading) ? $heading : 'PHP Error'; ?></h1>
<p><?php echo isset($message) ? $message : ''; ?></p>
</body>
</html>
