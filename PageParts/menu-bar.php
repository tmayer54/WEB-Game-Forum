<?php
//Affichage de la barre de menu
if(session_status() != PHP_SESSION_ACTIVE)	//On vérifie si la session existe déjà
{
	session_start();
}
include_once(__ROOT__."/helpz/functions.php");
?>

<body>
<div>
<nav>
<ul class="dropdownmenu">
  <li class="elem-menu"><a href="index.php">Accueil</a>
  </li>
  <li class="elem-menu"><a href="liste-blog.php">Liste des blogs</a>
  </li>
  <li class="elem-menu"><a href="recherche.php">Rechercher</a></li>
	<?php 
		if(isset($_SESSION["id"]))
		{
			echo '<li id="ID_myblog" class="menu-deroulant elem-menu"><a href="./blog.php?userID=' . $_SESSION["id"] . '"><img src="' . getAvatarLink($_SESSION["id"]) . '" alt=avatar> ' . $_SESSION["login"] . '</a>';
			echo '<ul class="sous-menu">';
			echo '<li class="elem-menu"><a href="./blog.php?userID=' . $_SESSION["id"] . '">Mon Blog</a></li>';
			echo '<li class="elem-menu"><a href="profil.php">Mon Profil</a></li>';
			echo '<li class="elem-menu"><a href="./logout.php">Déconnexion</a></li>';
			echo '</ul>';
			echo '</li>';
		}
		else
		{
			echo '<li class="elem-menu">';
			echo '<a id="show-login-btn" href="#" onclick=\'showPopup("popup-login")\'>Connexion</a>';
			echo '</li>';
		}
	?>
</ul>
</nav>

</div>