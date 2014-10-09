<html>
<head>
<meta http-equiv="refresh"
	content="0;URL=http://<?php echo $_SERVER['HTTP_HOST'] . (isset($_REQUEST['redir']) ? base64_decode($_REQUEST['redir']) : ''); ?>" />
</head>
<body></body>
</html>
