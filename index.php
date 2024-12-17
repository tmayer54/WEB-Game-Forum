<!DOCTYPE html>
<html lang="fr">
	<?php
        include_once("initialize.php");
        include_once(__ROOT__."/PageParts/header.php");
        include_once(__ROOT__."/PageParts/menu-bar.php");
        include_once(__ROOT__."/PageParts/connexion.php");
        include_once(__ROOT__."/PageParts/inscription.php");
        include_once("helpz/functions.php");
        include("connexion-base.php");
	?>
    
<body>
<?php

// Sélection de trois utilisateurs aléatoires
$req_utilisateurs = $pdo->query("SELECT DISTINCT utilisateur.id_utilisateur FROM utilisateur INNER JOIN post ON utilisateur.id_utilisateur = post.id_utilisateur ORDER BY RAND() LIMIT 3");
$result_utilisateurs = $req_utilisateurs->fetchAll();

// Sélection de trois posts aléatoires pour ces trois utilisateurs
$posts = array();
foreach ($result_utilisateurs as $row_utilisateur) {
    $req_posts = $pdo->prepare("SELECT id_post FROM post WHERE id_utilisateur = ? ORDER BY RAND() LIMIT 1");
    $req_posts->execute(array($row_utilisateur["id_utilisateur"]));
    $result_posts = $req_posts->fetchAll();
    if (count($result_posts) > 0) {
        $posts[] = $result_posts[0];
    }
}
// Affichage des résultats
if (count($posts) > 0) {
    foreach ($posts as $row) {
        DisplayPost($row["id_post"]);
    }
} else {
    echo '<p class="warning"> Aucun post n\'existe dans le système pour l\'instant!</p>';
}
?>
</body>
</html>
