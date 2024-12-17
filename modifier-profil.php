<?php

if(session_status() != PHP_SESSION_ACTIVE)	//On vérifie si la session existe déjà
{
	session_start();
}

include("connexion-base.php");
include_once("initialize.php");
include_once("helpz/functions.php");

$pseudo = false;
$mdpOld = false;
$mdpNew = false;
// Vérification de la modification du pseudo
if (isset($_POST["pseudo"]) && !empty($_POST['pseudo'])) {
    $id_utilisateur = $_SESSION["id"];
    $req = $pdo->prepare("SELECT count(id_utilisateur) AS nbPseudo FROM utilisateur WHERE pseudo = ?");
    $req->execute(array($_POST["pseudo"]));
    $donnee = $req->fetch();

    if($donnee["nbPseudo"] <= 0)    //Si le pseudo n'existe pas dans la bdd
	{
        //On met à jour le pseudo dans la bdd
        $query = $pdo->prepare("UPDATE utilisateur SET pseudo = ? WHERE id_utilisateur = ?");
        $query->execute(array($_POST['pseudo'], $id_utilisateur));
        $_SESSION['login'] = $_POST['pseudo']; // Mise à jour du pseudo en session
        $pseudo = true;
	}
    else
	{
        $pseudo = "Ce pseudo existe déjà";
	}
}
else
{
    $pseudo = true; //L'utilisateur ne veut pas changer le pseudo
}

// Vérification de la modification du mot de passe
if (isset($_POST['mdp']) && !empty($_POST['mdp']) && isset($_POST['nouveau_mdp']) && !empty($_POST['nouveau_mdp']) && isset($_POST['nouveau_mdp2']) && !empty($_POST['nouveau_mdp2'])) {
    $nouveau_mdp = $_POST['nouveau_mdp'];
    $nouveau_mdp2 = $_POST['nouveau_mdp2'];
    $id_utilisateur = $_SESSION['id'];
    //On vérifie que le mot de passe actuel est correct
    $query = $pdo->prepare("SELECT count(nom) as mdp_count FROM utilisateur WHERE id_utilisateur = ? and mdp = PASSWORD(?)");
    $query->execute(array($id_utilisateur, $_POST['mdp']));
    $result = $query->fetch(); // On récupère le résultat de la requête

    if ($result['mdp_count'] >= 1) {    //Si le mot de passe actuel est bon
        $mdpOld = true;
        // Vérification de la concordance des nouveaux mots de passe
        if ($nouveau_mdp == $nouveau_mdp2) {
            // On hash le nouveau mot de passe avant de le stocker dans la base de données
            $query = $pdo->prepare("UPDATE utilisateur SET mdp = PASSWORD(?) WHERE id_utilisateur = ?");
            $query->execute(array($nouveau_mdp, $id_utilisateur));
            $mdpNew = true;
        } else {
            $mdpNew = "Les nouveaux mots de passe ne correspondent pas";
        }
    } else {
        $mdpOld = "Mot de passe actuel incorrect";
    }
}
else
{
    $mdpOld = true;
    $mdpNew = true;
}

if(isset($_FILES["avatar"]["name"]) && !empty($_FILES["avatar"]["name"])) {
    $avatarDir = "images/avatar";
	$infoFile = pathinfo($_FILES["avatar"]["name"]);
	$extension = $infoFile["extension"];
	$lienAvatar = $avatarDir . "/" . $_SESSION["id"] . "." . $extension;    //Le nom de l'image est l'id de l'utilisateur pour être sûr qu'il n'y ai pas 2 fois le même nom de fichier

    $lienImgSansExt = $avatarDir . "/" . $_SESSION["id"];
    if($lienASuppr = glob($lienImgSansExt.".*")) {  //On cherche si un fichier a déjà le nom de l'id du post
		unlink($lienASuppr[0]); //On supprime le fichier qui a le même nom
	}
	move_uploaded_file($_FILES["avatar"]["tmp_name"], $lienAvatar);    //On met l'avatar de l'utilisateur dans le dossier avatar avec comme nom l'id

	$req = $pdo->prepare("UPDATE utilisateur SET avatar = ? WHERE id_utilisateur = ?"); //On upload le lien de l'avatar dans la bdd
	$req->execute(array($lienAvatar, $_SESSION["id"]));
}

echo json_encode(array('pseudo' => $pseudo, 'mdpOld' => $mdpOld,'mdpNew' => $mdpNew));
?>