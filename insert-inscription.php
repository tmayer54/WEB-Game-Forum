<?php
//L'objectif est d'ajouter l'utilisateur qui vient de s'inscrire dans la base de données
//On vérifie si la session existe déjà
if(session_status() != PHP_SESSION_ACTIVE)	//On vérifie si la session existe déjà
{
	session_start();
}

//On vérifie qu'il y a une donnée entrée dans le champ de recherche
if(isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST["prenom"]) && isset($_POST["nom"]) && isset($_POST["pseudo"]) && isset($_POST["mdp-confirm"]) && isset($_POST['date-naissance']))
{
    $existe=true;
    if(!empty($_POST['email']))
    {
        $mail=true;
    }
    else
    {
        $mail = 'Le champ email est obligatoire.';
    }


    if(!empty($_POST['mdp']))
    {
        if(preg_match( '~[A-Z]~', $_POST["mdp"]) && preg_match( '~[a-z]~', $_POST["mdp"]) && preg_match( '~\d~', $_POST["mdp"]) && (strlen( $_POST["mdp"]) > 7))
		{
			if(!empty($_POST['mdp-confirm']))
			{
				if($_POST['mdp-confirm'] == $_POST['mdp'])
				{
					$mdp=true;
				}
				else
				{
					$mdp='Le mot de passe doit être le même que sa confirmation.';
				}
			}
			else
			{
				$mdp='Veuillez confirmer le mot de passe.';
			}
		}
        else
		{
			$mdp='Le mot de passe doit contenir au moins 8 caractères avec au moins un chiffre, une majuscule, une minuscule.';
		}

    }
    else
    {
        $mdp='Le champ mot de passe est obligatoire.';
    }


    if(!empty($_POST['prenom']))
    {
        $prenom=true;
    }
    else
    {
        $prenom='Le champ prenom est obligatoire.';
    }


    if(!empty($_POST['nom']))
    {
        $nom=true;
    }
    else
    {
        $nom='Le champ nom est obligatoire.';
    }

    if(!empty($_POST['date-naissance']))
    {
        $date=true;
    }
    else
    {
        $date='Le champ date de naissance est obligatoire.';
    }

    if(!empty($_POST['pseudo']))
    {
        try
        {
            include("connexion-base.php");

            $req = $pdo->prepare("SELECT count('pseudo') AS nombre FROM utilisateur WHERE pseudo=?");
            $req->execute(array($_POST['pseudo']));
            $donnee=$req->fetch();
            if ($donnee['nombre'] >= 1)
            {
                $pseudo="Ce pseudo existe déjà.";
            }
            else
            {
                $pseudo=true;
            }
        }
        catch(PDOException $e)
        {
	        $sql=$e;
            $pseudo='Problème serveur, veuillez réessayer plus tard';
        }
    }
    else
    {
        $pseudo='Le champ nom est obligatoire.';
    }
}
else
{
    $existe = 'Des valeurs ne sont pas envoyées';
}

if($existe===true && $mail===true && $date===true && $mdp===true && $prenom===true && $nom===true && $pseudo===true)   ///triple = test la valeur et le type
{
    $sql=true;
    try
    {
        include("connexion-base.php");
        //Ajout du nouvel utilisateur dans la table
        $req = $pdo->prepare("INSERT INTO utilisateur (prenom, nom, email, pseudo, mdp, date_naissance) VALUES (?,?,?,?,PASSWORD(?),?)");
        $req->execute(array($_POST["prenom"],$_POST["nom"],$_POST["email"],$_POST["pseudo"],$_POST["mdp"], $_POST["date-naissance"]));

        //On récupère l'id de ce nouvel utilisateur
        $req = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE pseudo=?");
        $req->execute(array($_POST['pseudo']));
        $donnee = $req->fetch();
        $id = $donnee['id_utilisateur'];
        $_SESSION['id'] = $id;
        $_SESSION['login'] = $_POST['pseudo'];
        setcookie("login", $_SESSION["login"], time() + 24*3600); //cookies enregistrés pour 24h
        setcookie("id", $_SESSION['id'], time() + 24*3600); //cookies enregistrés pour 24h"])

        //On vérifie si l'utilisateur a uploadé un avatar, Si oui, on l'upload dans le dossier avatar et on met le lien dans la bdd   
        if(isset($_FILES["avatar"]["name"]) && !empty($_FILES["avatar"]["name"]))
        {
            $avatarDir = "images/avatar";
            $infoFile = pathinfo($_FILES["avatar"]["name"]);
            $extension = $infoFile["extension"];
            $lienAvatar = $avatarDir . "/" . $id . "." . $extension;    //Le nom de l'image est l'id de l'utilisateur pour être sûr qu'il n'y ai pas 2 fois le même nom de fichier
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $lienAvatar);    //On met l'avatar de l'utilisateur dans le dossier avatar avec comme nom l'id

            $req = $pdo->prepare("UPDATE utilisateur SET avatar = ? WHERE id_utilisateur = ?"); //On upload le lien de l'avatar dans la bdd
            $req->execute(array($lienAvatar, $id));
        }
    }
    catch(PDOException $e)
    {
	    $sql=$e;
    }
}
else
{
    $sql='Des valeurs ne sont pas bonnes';
}

echo json_encode(array('existe' => $existe, 'mail' => $mail,'date' => $date, 'pseudo' => $pseudo, 'mdp' => $mdp, 'prenom' => $prenom, 'nom' => $nom, 'sql' => $sql));
?>