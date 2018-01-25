<?php
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$name = $_SESSION['username'];
?>
<link href="file:///C|/Users/uzivatel/Desktop/RP WEb/map.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="Js/js3.js"></script>
<div id="mapControl">
<img id="arr_top" src="Img/map/arow top.jpg" onclick="move(true,<?= $x ?> - 5,<?= $y ?>, '<?= $_SESSION['username'] ?>' )" />
<img id="arr_left" src="Img/map/arow left.jpg" onclick="move(true,<?= $x ?>,<?= $y ?> - 5, '<?= $_SESSION['username'] ?>' )" />
<img id="arr_right" src="Img/map/arow right.jpg" onclick="move(true,<?= $x ?>,<?= $y ?> + 5, '<?= $_SESSION['username'] ?>' )" />
<img id="arr_bot" src="Img/map/arow bot.jpg" onclick="move(true,<?= $x ?> + 5,<?= $y ?>, '<?= $_SESSION['username'] ?>' )" />
</div>
