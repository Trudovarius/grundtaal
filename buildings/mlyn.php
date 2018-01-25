<?php 
$urovenBudov = mysqli_query($db_connect,"SELECT * FROM buildings WHERE id = '$id'");
$urovenBudovy = mysqli_fetch_array($urovenBudov);
$casStavania = 30 * 60; //v sekundach
$naklady = 60000;
$naklady *= $urovenBudovy['Field'];
?>
<div id="navigation">
<h2>Field</h2>
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
<form action="construction.php?duration=<?= $casStavania ?>&cost=<?= $naklady?>&type=Field" method="post">
<input type="submit" value="Upgrade" />
</form>
</p>
</div>
<div id="local-info">
<img id="buildImg" src="Img/mlyn.png" />
</div>