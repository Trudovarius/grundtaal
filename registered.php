<?php
$db_connect = mysqli_connect("localhost", "root", "", "rp_web", "3306");

//Nacitanie riadku z databazy podla mena
$a = $_POST['Name'];
$verify_name = mysqli_query($db_connect,"SELECT * FROM user WHERE Name = '$a'");
$b = mysqli_fetch_array($verify_name);
//Nacitanie pomocnej premennej pre overenie mailu
$email = $_POST['Email'];
$verify_mail = mysqli_query($db_connect,"SELECT * FROM user WHERE Email = '$email'");
$c = mysqli_fetch_array($verify_mail);
//Ak je vyplneny cely fornular
if( ( $_POST['Name'] == '' ) || ( $_POST['Password'] == '' ) || ($_POST['Email'] == '' ) )
{
	$text = "The Form is not filled correctly.";
} else
//Overenie ci sa uz nenachadza rovnaky uzivatel v databaze
if($_POST['Name'] == $b['Name'])
{
	$text = "This Name is already in use.";	
} else
//Ak je meno nove vykona sa test emailu ci je napisany spravne (meno@domena.sk):
if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email))
{
	$text = "Wrong format of the e-mail adress.";
}else
//Ak je spravny format, skontroluje sa ci uz zadany mail v databaze existuje
if($_POST['Email'] == $c['Email'])
{
	$text = "This E-mail is already in use.";
}else
//Ak je vsetko spravne pokracuje sa v kode
 if (isset($_POST["Name"]))
	{
	//Posle data do databazy
	$Name = $_POST['Name'];
	$Password = sha1($_POST['Password']);
	$Email = $_POST['Email'];	
	$sql_prikaz = "INSERT INTO user (Name, Password, Email) VALUES ('$Name', '$Password', '$Email')";	
	$user = mysqli_query($db_connect, $sql_prikaz);
	print_r($user);
	
	//vypocet okruhu v ktorom sa budu dediny generovat, aby bolo aj malo hracov blizko pri sebe
	$dediny = mysqli_query($db_connect, "SELECT COUNT(*) FROM city");
	$cities = mysqli_fetch_array($dediny);
	if($cities[0] % 2 == 0){
	$count = $cities[0]/2;
	} else {
	$count = $cities[0];	
	}
	//nacita nahodne suradnice dediny + overenie ci je prazdne miesto
	while(true)
	{
	$X = rand(0,$count);
	$Y = rand(0,$count);
	$je_tam = mysqli_query($db_connect, "SELECT * FROM city WHERE X = '$X' AND Y = '$Y'");
	$je_tam_X = mysqli_fetch_array($je_tam);
	if ($je_tam_X['X'] == NULL && $je_tam_X['Y'] == NULL)
		break;			
	}

	//vytvori dedinu v databaze a priradi k pouzivatelovy
	$sql_prikaz = "INSERT INTO city( Name, Owner, X, Y, MapImg) VALUES ('Village', '$Name', '$X', '$Y', 'Img/map/mesto.png' )";
	$city = mysqli_query($db_connect, $sql_prikaz);
	
	//kazdej novej dedine priradi suroviny, ktore ma na zaciatku
	$sql = "INSERT INTO resources( Food, Glass, Iron, Wood, Stone) VALUES ('50000', '50000', '50000', '50000', '50000' )";
	$resources = mysqli_query($db_connect, $sql);
	
	//zaisti aby id v tabulkach city a buildings bolo rovnake a to ich vlastne bude spajat
	$idA = mysqli_query($db_connect," SELECT * FROM city WHERE X = '$X' AND Y = '$Y'");
	$idB = mysqli_fetch_assoc($idA);
	$id = $idB['id'];
	//nastavi v databaze zakladne urovne jednotlivych budov v dedine
	$buildings = mysqli_query($db_connect," INSERT INTO buildings (id,Castle,Houses,Mine,Field,Woodcutter,StonePit,Glassworks) VALUES ('$id','1','1','1','1','1','1','1') ");
	//vytvori udaj v tabulke bojov
	$troops = mysqli_query($db_connect," INSERT INTO war (id,Troops, Archers, Swordmen, Cavalry, AttackArrive, AttX, AttY) VALUES ('$id','0','0','0','0','0000-00-00 00:00:00','0','0') ");
	

	$text = "You have been registered successfully.<br />Thank You for playing this game.";

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Grundtaal</title>
<link href="css.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
include("layout1.php");
?>

</body>
</html>