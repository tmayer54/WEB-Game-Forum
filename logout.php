<?php
//Deconnexion de l'utilisateur
if(session_status() != PHP_SESSION_ACTIVE)	//On vérifie si la session existe déjà
{
	session_start();
}

include_once("helpz/functions.php");

if(isset($_SESSION["id"]))
{
	session_unset();	//Supprime toutes les variables de session
	session_destroy();	//Detruit la session
	unset($_COOKIE["id"]);
	unset($_COOKIE["login"]);
	//setcookie("id", null, -1, '/');	//Pour supprimer totalement le cookie
}

if(!isset($_SESSION["login"]) && !isset($_SESSION["id"]))
{
	//Redirection
	header("Location:".GetURL()."/index.php");
}
else
{
	echo "Les variables de session n'ont pas été supprimé correctement.";
}
?>