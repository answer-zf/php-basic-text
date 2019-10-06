<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
</head>
<body>
	
	<?php if (empty($_COOKIE['close']) || $_COOKIE['close']!== '1'): ?>
		<div class="bg-warning" style="height: 200px">
			<a href="close.php" class="close mr-5 pt-3">&times;</a>
		</div>		
	<?php endif ?>
	
</body>
</html>
