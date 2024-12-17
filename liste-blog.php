<?php
include_once("initialize.php");
include("connexion-base.php");
include_once("helpz/functions.php");
include_once(__ROOT__."/PageParts/header.php");
include_once(__ROOT__."/PageParts/menu-bar.php");
include_once(__ROOT__."/PageParts/connexion.php");
include_once(__ROOT__."/PageParts/inscription.php");
include("connexion-base.php");

// Récupération de tous les blogs disponibles
$req = $pdo->prepare("SELECT `id_utilisateur`,`pseudo` FROM `utilisateur`
                      ORDER BY `id_utilisateur` ASC");
$req->execute();
$result = $req->fetchAll();

// Affichage des résultats
if (count($result) > 0) {
    
    foreach ($result as $row) {
        echo '<div class="blog-list">';
        echo '<a href="./blog.php?userID='.$row["id_utilisateur"].'">';
        echo '<li><p>Découvrir le blog de <strong class="jaune">'.$row["pseudo"].'</strong></p></li>';
        echo '</a>';
        echo '</div>';
    }

} else {
    echo '<p class="warning"> Aucun utilisateur/blog n\'existe dans le système pour l\'instant!</p>';
}
?>