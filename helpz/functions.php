<?php
// Fonction qui affiche les posts d'un blog
//--------------------------------------------------------------------------------
function DisplayBlog($blogID, $isMyBlog, $nbClickLoad){
    include("connexion-base.php");
    $offset = $nbClickLoad*10;
	$req = $pdo->prepare("SELECT id_post FROM `post` WHERE `id_utilisateur` =? ORDER BY date_post DESC LIMIT 10 OFFSET ?");
    $req->bindParam(1, $blogID, PDO::PARAM_STR);
	$req->bindParam(2, $offset, PDO::PARAM_INT);
	$req->execute();

    $nbPost = $req->rowCount();

    if($nbPost > 0){
        if ($isMyBlog && $nbClickLoad <= 0){   //Si c'est mon blog et que c'est le haut de l'affichage
?>
        <div class = creer>
        <form action="editPost.php" method="POST">
            <input type="hidden" name="newPost" value="1">
            <button type="submit">Ajouter un nouveau post!</button>
        </form>
        </div>

        <?php
        }

        while($donnee=$req->fetch()){
            DisplayPost($donnee["id_post"]);
        }
    }
    else if($nbPost <= 0 && $nbClickLoad <= 0){
        echo '<p>Il n\'y a pas de post dans ce blog.</p>';

        if ($isMyBlog){
?>
            <form action="editPost.php" method="POST">
                <input type="hidden" name="newPost" value="1">
                <button type="submit">Ajouter un premier post!</button>
            </form>
        <?php
        }
    }
}

// Fonction qui affiche un post avec les informations données en paramètres
//--------------------------------------------------------------------------------
function DisplayPost($id_post){
    include("connexion-base.php");

    $reqUser = $pdo->prepare("SELECT utilisateur.id_utilisateur, utilisateur.pseudo FROM post INNER JOIN utilisateur ON utilisateur.id_utilisateur=post.id_utilisateur WHERE id_post =?");
    $reqUser->execute(array($id_post));
    $utilisateur = $reqUser->fetch();

    $reqPost = $pdo->prepare("SELECT * FROM post WHERE id_post=?");
    $reqPost->execute(array($id_post));
    $donnee = $reqPost->fetch();

    $reqComm = $pdo->prepare("SELECT count(id_post) AS nbCommentaire FROM `commentaire` WHERE `id_post` =?");
	$reqComm->execute(array($id_post));
	$resultCount = $reqComm->fetch();

    $timestamp = strtotime($donnee["date_post"]);

    $isMyPost = isset($_SESSION["id"]) && $utilisateur["id_utilisateur"] == $_SESSION["id"];

    if (isset($utilisateur)){
        echo '
        <section class="articles">
            <div class="article">
                <div class="left">
                    <img src="'.$donnee["imgPresentation"].'" alt"image jeu">
                </div>

                <div class="right">
                    <p class="date">dernière modification le '.date("d/m/y à H:i:s", $timestamp).'
                    <h3 class = "title">'.$donnee["titre"].'</h3>
                    <p class="contenu">'.$donnee["contenu"].'</p>

                    <div class="auteur">par '.$utilisateur["pseudo"].'</div>   <!-- selection d une valeur spécifique du tableau -->';

		if($isMyPost)
		{
			echo '
                <div class="modifier">
                    <form action="editPost.php" method="GET">
                        <input type="hidden" name="postID" value="'.$id_post.'">
                        <button type="submit">Modifier/effacer</button>
                    </form>
                </div>';
		}
		if($resultCount["nbCommentaire"] === 1)
		{
            echo '<button id="btn-affiche-commentaire'.$id_post.'" class="btn-voir-commentaire" onclick="commentaire('.$id_post.')">Voir le commentaire</button>';
		}
		else if($resultCount["nbCommentaire"] > 1)
		{
            echo '<button id="btn-affiche-commentaire'.$id_post.'" class="btn-voir-commentaire" onclick="commentaire('.$id_post.')">Voir les '.$resultCount["nbCommentaire"].' commentaires</button>';
		}
        else
		{
            echo '<button id="btn-affiche-commentaire'.$id_post.'" class="btn-voir-commentaire" onclick="commentaire('.$id_post.')">Pas encore de commentaire</button>';
		}
        echo '<div id="affichage-commentaire'.$id_post.'"></div>'; //Pour afficher les commentaires en javascript

        if(!$isMyPost && isset($_SESSION["id"]))    //Si c'est pas le post de l'utilisateur connecté
	    {
            echo '<div id="commentaire'.$id_post.'" class="hidden">
                    <form id="form-commentaire'.$id_post.'" action"commenter.php" method="POST">
                        <label for="commentaire">Votre commentaire :</label>
                        <textarea id="text-commentaire'.$id_post.'" name="commentaire" placeholder="Tapez votre commentaire ici..."></textarea>
                        <input type="hidden" name="id_post" value="'.$id_post.'">
                        <input class="submit-form" type="submit" value="Envoyer" />
                    </form>
                </div>';
            echo '<button id="btn-commenter'.$id_post.'" class="btn-commenter" onclick="clickCommentaire('.$id_post.')">Commenter</button>';
            echo '<button id="annuler-commenter'.$id_post.'" class="hidden" onclick="clickAnnulerCommenter('.$id_post.')">Annuler</button>';
		}
          echo '</div>
            </div>
        </section>
        ';
    }
}

// Function to check login. returns an array with 2 booleans
// Boolean 1 = is login successful, Boolean 2 = was login attempted
//--------------------------------------------------------------------------------
function CheckLogin(){
    global $conn, $username, $userID;

    $error = NULL;
    $loginSuccessful = false;

    //Données reçues via formulaire?
	if(isset($_POST["pseudo"]) && isset($_POST["mdp"])){
		$username = SecurizeString_ForSQL($_POST["pseudo"]);
		$password = md5($_POST["mdp"]);
		$loginAttempted = true;
	}
	//Données via le cookie?
	elseif ( isset( $_COOKIE["pseudo"] ) && isset( $_COOKIE["mdp"] ) ) {
		$username = $_COOKIE["pseudo"];
		$password = $_COOKIE["mdp"];
		$loginAttempted = true;
	}
	else {
		$loginAttempted = false;
	}

    //Si un login a été tenté, on interroge la BDD
    if ($loginAttempted){
        $query = "SELECT * FROM login WHERE pseudo = '".$username."' AND mdp ='".$password."'";
        $result = $conn->query($query);

        if ( $result ){
            $row = $result->fetch_assoc();
            $userID = $row["id_utilisateur"];
            $loginSuccessful = true;
        }
        else {
            $error = "Ce couple login/mot de passe n'existe pas. Créez un Compte";
        }
    }

    return array($loginSuccessful, $loginAttempted, $error, $userID);
}

//Retire les caractères spéciaux d'une chaine de caractères pour la sécuriser et éviter les injections SQL
//--------------------------------------------------------------------------------
function SecurizeString_ForSQL($string) {
    $string = trim($string);
    $string = stripcslashes($string);
    $string = addslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

// Fonction pour récupérer l'url de la page
//--------------------------------------------------------------------------------
function GetUrl() {
    $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
    $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
    $url .= dirname($_SERVER["REQUEST_URI"]);
    return $url;
}

//Fonction pour récupérer le lien de l'image avatar de l'utilisateur
//-------------------------------------------------------------------------------
function getAvatarLink($id) {
    try
    {
        include("connexion-base.php");
        $req = $pdo->prepare("SELECT avatar FROM utilisateur WHERE id_utilisateur=?");
        $req->execute(array($id));
        $donnee = $req->fetch();
        return $donnee["avatar"];
    }
    catch(PDOException $e)
    {
	    $sql=$e;
    }
    return null;
}
?>