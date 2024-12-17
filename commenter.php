<?php
//Ajout d'un commentaire sous un post
if(session_status() != PHP_SESSION_ACTIVE)	//On vérifie si la session existe déjà
{
	session_start();
}

$existe=false;
$sql=false;

if(isset($_POST['commentaire']) && isset($_POST['id_post']))
{
    if (!empty($_POST['commentaire']))
    {
		$existe=true;
		try
		{
			include("connexion-base.php");

			$req = $pdo->prepare("INSERT INTO commentaire (id_post, id_utilisateur, contenu) VALUES (?,?,?)");
			$req->execute(array($_POST["id_post"], $_SESSION["id"], $_POST["commentaire"]));

			$sql=true;
		}
		catch(PDOException $e)
		{
			$sql=$e;
		}
	}
	else
	{
		$existe=false;
	}
}
else
{
	$existe=false;
}

echo json_encode(array('existe' => $existe, 'sql' => $sql));
?>