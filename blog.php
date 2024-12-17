<?php
if(session_status() != PHP_SESSION_ACTIVE)  //On vérifie si la session existe déjà
{
	session_start();
}

include_once("initialize.php");
include("connexion-base.php");
include_once("helpz/functions.php");
include_once(__ROOT__."/PageParts/header.php");
include_once(__ROOT__."/PageParts/menu-bar.php");
include_once(__ROOT__."/PageParts/connexion.php");
include_once(__ROOT__."/PageParts/inscription.php");

$blogOwnerName = "";
$isMyOwnBlog = false;

//On vérifie si l'utilisateur est connecté
if ( isset($_GET["userID"]) ){

    //On vérifie si l'utilisateur connecté est le propriétaire du blog
    if ( isset($_GET["userID"]) && isset($_SESSION["id"]) && $_GET["userID"] == $_SESSION["id"] ){
        $isMyOwnBlog = true;
        $blogOwnerName = $_SESSION["login"];
    }
    else {
        include("connexion-base.php");
        //On récupère le pseudo du propriétaire du blog
        $req = $pdo->prepare("SELECT `pseudo` FROM `utilisateur` WHERE `id_utilisateur` =?");
        $req->execute(array($_GET["userID"]));
        $result = $req->fetchAll();
        if ( isset($result[0]["pseudo"]) ){ // On vérifie si le résultat est non vide
            $blogOwnerName = $result[0]["pseudo"];
        }
    }

    if ($blogOwnerName != ""){
        if ($isMyOwnBlog){
            echo "<h1>Bienvenue sur votre blog, ".$blogOwnerName."</h1>";
        }
        else {
            echo "<h1>Bienvenue sur le blog de ".$blogOwnerName."</h1>";
        }
        //Affichage du blog
        echo '<div id="list-post">';
        DisplayBlog($_GET["userID"], $isMyOwnBlog, 0);  //On affiche le blog de l'utilisateur qui correspond au lien
        echo '</div>';

        echo '<form id="form-load-blog">
                    <input type="hidden" name="id-blog" value="'.$_GET["userID"].'" />
                    <input type="hidden" name="isMyBlog" value="'.$isMyOwnBlog.'" />
                  </form>';
        //Charger plus de posts si nécessaire
		echo '<div id="encore-blog" class="charger-plus"><button id="btn-encore-blog" class="hidden">Charger plus de posts</button></div>';
    }
    else {
        echo "<h1>Erreur! Cette ID ne correspond à aucun utilisateur actif!</h1>";
    }
}
else {
  echo "<h1> Connexion failed,".$blogOwnerName."</h1>";
}
?>