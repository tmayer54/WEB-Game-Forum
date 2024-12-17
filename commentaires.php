<?php
//Affichage des commentaires d'un post
if(session_status() != PHP_SESSION_ACTIVE)	//On vérifie si la session existe déjà
{
	session_start();
}

if(isset($_POST["idPost"]))
{
	try
	{
		include("connexion-base.php");

		//Avec cette requête sql, si le pseudo et/ou le titre sont vide elle fonctionnera, elle retournera juste tous les post (correspondant uniquement au pseudo ou au titre)
		$req = $pdo->prepare("SELECT * FROM commentaire WHERE id_post=?");
		$req->execute(array($_POST["idPost"]));

		$nbResult = $req->rowCount();
		if($nbResult <= 0)
		{
			echo '<p>Aucun post ne correspond à la recherche</p>';
		}

		$reqUser = $pdo->prepare("SELECT utilisateur.pseudo FROM commentaire INNER JOIN utilisateur ON utilisateur.id_utilisateur = commentaire.id_utilisateur WHERE id_post=?");
		$reqUser->execute(array($_POST["idPost"]));

		while($donnee=$req->fetch())	//On affiche les commentaires ligne par ligne
		{
			$utilisateur=$reqUser->fetch();

			$timestamp = strtotime($donnee["date_commentaire"]);
            echo '
            <section class="articles">
                <div class="article">
                    <div class="right">
                        <p class="date">Ecrit par '.$utilisateur["pseudo"].' le '.date("d/m/y à H:i:s", $timestamp ).'
                        <p class="contenu">'.$donnee["contenu"].'</p>
					</div>
				</div>
			</section>
                        ';
		}
	}
	catch(PDOException $e)
	{
		$sql=$e;
	}
}
?>