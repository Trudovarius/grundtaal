<?php 
$urovenBudov = mysqli_query($db_connect,"SELECT * FROM buildings WHERE id = '$id'");
$urovenBudovy = mysqli_fetch_array($urovenBudov);
$casStavania = 50 * 60; //v sekundach
$naklady = 50000;
$naklady *= $urovenBudovy['Castle'];
?>
<div id="navigation">
<h2>Fortress</h2>
<p>
<table id="buildCost">
<tr>
	<td>The construction will take:</td>
	<td colspan="4">The construction will cost:</td>
</tr>
<tr>
	<td><?= $casStavania /60 ?> minutes</td>
	<td><?= $naklady?> Iron</td>
    <td><?= $naklady?> Wood</td>
    <td><?= $naklady?> Stone</td>
    <td><?= $naklady?> Glass</td>
</tr>
</table>
<form action="construction.php?duration=<?= $casStavania ?>&cost=<?= $naklady?>&type=Castle" method="post">
<input type="submit" value="Upgrade" />
</form>
</p>
<br />
<p>
This building allows you to own more cities. Amount of villages you can own equals to level of this building in al cities. If you have Fortress level 2, you can colonize a new city on the map. If you want a third city, you have to upgrade Fortress to level 3 in all villages you own.
</p>
</div>
<div id="local-info">
<img id="buildImg" src="Img/pevnost.png" />
</div>