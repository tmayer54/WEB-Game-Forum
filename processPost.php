<?php
if(session_status() != PHP_SESSION_ACTIVE)	//On vérifie si la session existe déjà
{
	session_start();
}

include("connexion-base.php");
include_once("initialize.php");
include_once("helpz/functions.php");

$loginStatus = CheckLogin();
$reqSuccess = false;

if( isset($_POST["action"])){
    if ($_POST["action"] == "edit"){
        if (isset($_POST["titre"]) && isset($_POST["contenu"])){
            $req = $pdo->prepare("UPDATE post SET titre = ?, contenu = ? WHERE id_post = ?");
            $tmpSuccess = $req->execute(array($_POST["titre"],$_POST["contenu"], $_POST["postID"])); //reqSuccess true si requete à fonctionnée

            $id = $_POST["postID"]; //On récupère l'ID du post que l'on veut modifier

            if(isset($_FILES["imgPresentation"]["name"]) && !empty($_FILES["imgPresentation"]["name"]))
            {
                $imgDir = "images/post";
                $infoFile = pathinfo($_FILES["imgPresentation"]["name"]);
                $extension = $infoFile["extension"];
                $lienImgSansExt = $imgDir . "/" . $id;  
                $lienImg = $imgDir . "/" . $id . "." . $extension;    //Le nom de l'image est l'id du post pour être sûr qu'il n'y ai pas 2 fois le même nom de fichier

                if($lienASuppr = glob($lienImgSansExt.".*")) {  //On cherche si un fichier a déjà le nom de l'id du post
					unlink($lienASuppr[0]); //On supprime le fichier qui a le même nom
				}

                move_uploaded_file($_FILES["imgPresentation"]["tmp_name"], $lienImg);    //On met l'image du post dans le dossier post avec comme nom l'id

                $req = $pdo->prepare("UPDATE post SET imgPresentation = ? WHERE id_post = ?"); //On upload le lien de l'image dans la bdd
                $reqSuccess = $req->execute(array($lienImg, $id));  //reqSuccess true si requete à fonctionnée
            }
            else
			{
                $reqSuccess = $tmpSuccess;
			}
        }
    }
    elseif ($_POST["action"] == "new"){
        if (isset($_POST["titre"]) && isset($_POST["contenu"])){
            $req = $pdo->prepare("INSERT INTO `post` (titre, contenu, id_utilisateur) VALUES (?,?,?)");
            $tmpSuccess = $req->execute(array($_POST["titre"],$_POST["contenu"],$_SESSION["id"]));

            $id = $pdo->lastInsertId(); //On récupère l'ID du post créé

            if(isset($_FILES["imgPresentation"]["name"]) && !empty($_FILES["imgPresentation"]["name"]))
            {
                $imgDir = "images/post";
                $infoFile = pathinfo($_FILES["imgPresentation"]["name"]);
                $extension = $infoFile["extension"];
                $lienImgSansExt = $imgDir . "/" . $id;
                $lienImg = $imgDir . "/" . $id . "." . $extension;    //Le nom de l'image est l'id du post pour être sûr qu'il n'y ai pas 2 fois le même nom de fichier

                if($lienASuppr = glob($lienImgSansExt.".*")) {  //On cherche si un fichier a déjà le nom de l'id du post
					unlink($lienASuppr[0]); //On supprime le fichier qui a le même nom
				}

                move_uploaded_file($_FILES["imgPresentation"]["tmp_name"], $lienImg);    //On met l'image du post dans le dossier post avec comme nom l'id

                $req = $pdo->prepare("UPDATE post SET imgPresentation = ? WHERE id_post = ?"); //On upload le lien de l'image dans la bdd
                $reqSuccess = $req->execute(array($lienImg, $id));  //reqSuccess true si requete à fonctionnée
            }
            else
			{
                $reqSuccess = $tmpSuccess;
			}
		}
    }
    elseif ($_POST["action"] == "delete"){
        $req = $pdo->prepare("DELETE FROM `post` WHERE `id_post` = ?");
        $reqSuccess = $req->execute(array($_POST["postID"]));   //reqSuccess true si requete à fonctionnée
    }

    if ($reqSuccess){
        //$redirect = "Location:".GetURL()."/blog.php?userID=".$_SESSION['id'];
        $redirect = GetURL()."/blog.php?userID=".$_SESSION['id'];
        echo json_encode(array('redirect' => $redirect));
        //header($redirect);
    }
}
?>