<?php
//Affichage de posts supplémentaire pour un blog
include_once("helpz/functions.php");

if(isset($_POST["id-blog"]) && isset($_POST["isMyBlog"]) && isset($_POST["nbClick"]))
{
	if($_POST["nbClick"] >= 1)
	{
		DisplayBlog($_POST["id-blog"], $_POST["isMyBlog"], $_POST["nbClick"]);
	}
}
?>