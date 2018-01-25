<?php
session_start();
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome <?= $_SESSION['username']?></title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">!window.jQuery && document.write('<script src="Js/jquery-1.11.1.js"><\/script>')</script>
<script type="text/javascript" src="Js/js3.js"></script>
<script type="text/javascript" src="Js/js2.js"></script>
<link href="css.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
switch($_GET['view'])
{
	case 'village': include('village.php'); break;
	case 'map': include('map.php'); break;
	default: echo "Error"; break;
}
?>
</body>
</html>
<script type="text/javascript" src="Js/js2.js"></script>