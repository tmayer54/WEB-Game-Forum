<?php
//Connexion à la base de données
$user = "root";
$password = "";
try
{
	$pdo=new PDO("mysql:host=localhost;dbname=rav_may_fan2jeu;charset=utf8",$user,$password);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}
?>