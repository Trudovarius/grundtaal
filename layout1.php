<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Grundtaal</title>
<link href="css.css" rel="stylesheet" type="text/css" />

<!-- Nacitanie kniznice jQuery -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">!window.jQuery && document.write('<script src="Js/jquery-1.11.1.js"><\/script>')</script>
<script type="text/javascript" src="Js/js1.js"></script>
</head>

<body>
<div id="content">
    <div id="logo"></div>
  <div id="login">
      <h1>Login</h1>
      <form action="login.php" method="post">
      <input class="login" name="Name" type="text" value="Name"/>
      <br/>
      <input style="margin-top:20px;" class="login" name="Password" type="password" value="Password" />
      <br/>
      <input style="margin-top:20px;" type="submit" value="Login" />
    </form>
    </div>
  <div id="text">
  	<h2><?= $text ?></h2>
    	<?php
			$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
			$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
			$Players = mysqli_query($db_connect, "SELECT * FROM  `user` ORDER BY  `user`.`Points` DESC LIMIT 0,5");
			$count = 1;
			echo '<table width="100%" id="highscores">';
			echo '<tr><td colspan="3">Player Rankings</td></tr>';
			echo '<tr><td>Position:</td><td>Name:</td><td>Points:</td></tr>';
			foreach($Players as $Player){
				echo '<tr><td>',$count,'</td><td>',$Player["Name"],'</td><td>',$Player["Points"],'</td></tr>';
				$count++;
			}
			echo '</table>';
		?>
  </div>
    <div id="registration">
    <img src="Img/register_img.png" id="register_img" />
  </div>
</div>
</body>
</html>