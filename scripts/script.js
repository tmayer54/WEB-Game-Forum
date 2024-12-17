//--------------INSCRIPTION----------------------

window.addEventListener("load", (event) => {
    var formInscription = document.getElementById("form-inscription");
    if (formInscription) {  //S'il y a bien le formulaire dans la page
        formInscription.addEventListener("submit", function (e) {
            e.preventDefault();
            var data = new FormData(this);
            if (verifyImgSize(153600, "input-avatar")) {  //On vérifie que la taille de l'avatar n'est pas trop grande (ici 150Ko max)
                requete(data);
            }
        })
    }
})

function requete(data) {  
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = this.response;
            afficherMessage(res);
        }
        if (this.readyState == 4 && this.status == 404) {
            alert("Erreur 404");
        }
    };

    xhr.open("POST", "insert-inscription.php", true);
    xhr.responseType = "json";
    xhr.send(data);
}

function afficherMessage(res) {
    var objets = {  //La liste de tous les id pour pouvoir changer la couleur
        "mail": "input-inscription-email",
        "mdp": "input-inscription-mdp",
        "prenom": "input-prenom",
        "nom": "input-nom",
        "pseudo": "input-inscription-pseudo",
        "date": "input-inscription-date"
    };
    document.getElementById("retour-inscription").innerHTML = "";

    if (res.existe !== true) {
        ecrireRetour("retour-inscription", res.existe);
    }

    for (var value in res) {
        if (objets[value] !== undefined) {
            if (res[value] !== true) {
                ecrireRetour("retour-inscription", res[value]);
                mettreRouge(objets[value]);
            }
            else {
                supprimerRouge(objets[value]);
            }
        }
    }

    var nbTrue = 0;

    for (var value in res) {
        if (res[value] == true) {
            nbTrue++;
        }
    }
    if (nbTrue == (Object.keys(res).length - 1) && res.sql !== true) {  //Si tout est ok sauf la valeur $sql
        ecrireRetour("retour-inscription", "Erreur d'envoie des données");
    }

    let allValueTrue = Object.values(res).every((value) => {
        return value === true;
    })
    if (allValueTrue) { //Si tout est bon
        alert('Inscription OK');
        document.getElementById("retour-inscription").innerHTML = "";
        location.href = "index.php";
    }
}

function ecrireRetour(obj_id, retour) {
    document.getElementById(obj_id).innerHTML += "<li>" + retour + "</li>";
}

function mettreRouge(obj_id) {
    document.getElementById(obj_id).style.color = "red";
    document.getElementById(obj_id).style.borderColor = "red";
}

function supprimerRouge(obj_id) {
    document.getElementById(obj_id).style.removeProperty("color");
    document.getElementById(obj_id).style.removeProperty("border-color");
}

//--------------CONNEXION----------------------

window.addEventListener("load", (event) => {
    var formConnexion = document.getElementById("form-connexion");
    if (formConnexion) {  //S'il y a bien le formulaire dans la page
        formConnexion.addEventListener("submit", function (e) {
            e.preventDefault();
            var data = new FormData(this);
            requeteConnexion(data);
        })
    }
})

function requeteConnexion(data) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = this.response;
            afficherMessageConnexion(res);
        }
        if (this.readyState == 4 && this.status == 404) {
            alert("Erreur 404");
        }
    };

    xhr.open("POST", "login.php", true);
    xhr.responseType = "json";
    xhr.send(data);
}

function afficherMessageConnexion(res) {
    var objets = {  //La liste de tous les id pour pouvoir changer la couleur
        "mdp": "input-connexion-mdp",
        "login": "input-connexion-pseudo"
    };
    document.getElementById("retour-connexion").innerHTML = "";

    if (res.existe !== true) {
        ecrireRetour("retour-connexion", res.existe);
    }

    for (var value in res) {
        if (objets[value] !== undefined) {
            if (res[value] !== true) {
                ecrireRetour("retour-connexion", res[value]);
                mettreRouge(objets[value]); 
            }
            else {
                supprimerRouge(objets[value]);
            }
        }
    }

    var nbTrue = 0;

    for (var value in res) {
        if (res[value] == true) {
            nbTrue++;
        }
    }
    if (nbTrue == (Object.keys(res).length - 1) && res.sql !== true) {  //Si tout est ok sauf la valeur $sql
        ecrireRetour("retour-connexion", "Erreur d'envoie des données");
    }

    let allValueTrue = Object.values(res).every((value) => {
        return value === true;
    })
    if (allValueTrue) { //Si tout est bon
        alert('Connexion OK');
        document.getElementById("retour-connexion").innerHTML = "";
        location.href = "index.php";
    }
}

function verifyImgSize(maxSizeOctet, inputName) {
    var img = document.getElementById(inputName);
    if (img.value != "") {
        if (img.files && img.files.length == 1 && img.files[0].size > maxSizeOctet) {    //Si l'image a été upload et que sa taille est supérieure à celle voulue
            alert("Le fichier ne doit pas dépasser " + parseInt(maxSizeOctet / 1024) + "Ko");
            mettreRouge(inputName);
            return false;
        }
        else {
            supprimerRouge(inputName);
            return true;
        }
    }
    else {
        return true;    //Vrai aussi quand il n'y a pas de fichier
    }
}

//--------------PROCESS POSTS----------------------

window.addEventListener("load", (event) => {
    var formNewPost = document.getElementById("form-newPost");
    if (formNewPost) {  //S'il y a bien le formulaire dans la page
        formNewPost.addEventListener("submit", function (e) {
            e.preventDefault();
            var data = new FormData(this);
            if (verifyImgSize(1048576, "input-img")) {    //On vérifie que la taille de l'image n'est pas trop grande (ici 1Mo max)
                envoiPost(data);
            }
        })
    }
})

window.addEventListener("load", (event) => {
    var formModifPost = document.getElementById("form-editPost");
    var formSupprPost = document.getElementById("form-supprPost");
    if (formModifPost) {  //S'il y a bien le formulaire dans la page
        formModifPost.addEventListener("submit", function (e) {
            e.preventDefault();
            var data = new FormData(this);
            if (verifyImgSize(1048576, "input-img")) {    //On vérifie que la taille de l'image n'est pas trop grande (ici 1Mo max)
                envoiPost(data);
            }
        })
    }
    if (formSupprPost) {    //S'il y a bien le formulaire dans la page
        formSupprPost.addEventListener("submit", function (e) {
            if (confirm("Etes-vous sûr de vouloir supprimer ce post et tous ses commentaires ?")) {  //Popup de confirmation, si OK alors on fait l'action, sinon on fait rien
                e.preventDefault();
                var data = new FormData(this);
                envoiPost(data);
            } else {
                e.preventDefault();
            }
        })
    }
})

function envoiPost(data) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = this.response;
            location.href = res.redirect;
        }
        if (this.readyState == 4 && this.status == 404) {
            alert("Erreur 404");
        }
    };

    xhr.open("POST", "processPost.php", true);
    xhr.responseType = "json";
    xhr.send(data);
}

//--------------RECHERCHE----------------------

var nbClick = 0;
window.addEventListener("load", (event) => {
    var formRecherche = document.getElementById("form-recherche");
    if (formRecherche) {  //S'il y a bien le formulaire dans la page
        formRecherche.addEventListener("submit", function (e) {
            nbClick = 0;
            e.preventDefault();
            var data = new FormData(this);
            data.append("nbClick", nbClick);
            requeteRecherche(data);
        })
    }
})

function requeteRecherche(data) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = this.response;
            if (nbClick <= 0) {
                afficherRecherche(res);
            } else {
                appendRecherche(res);
            }
        }
        if (this.readyState == 4 && this.status == 404) {
            alert("Erreur 404");
        }
    };

    xhr.open("POST", "execRecherche.php", true);
    xhr.responseType = "text";
    xhr.send(data);
}

function afficherRecherche(res) {
    document.getElementById("resultat-recherche").innerHTML = res;
    if (document.getElementsByClassName("articles").length < 10) { //Si aucun post n'est affiché
        document.getElementById("btn-encore-recherche").classList.add("hidden");
    }
    else if (document.getElementsByClassName("articles").length >= 10) {
        document.getElementById("btn-encore-recherche").classList.remove("hidden");
    }
}

function appendRecherche(res) {
    document.getElementById("resultat-recherche").innerHTML += res;
    var parser = new DOMParser();
    var doc = parser.parseFromString(res, 'text/html'); //On converti le text reçu en html
    if (!res || doc.getElementsByClassName("articles").length < 10) { //Si rien n'est à afficher ou qu'il y a moins de 10 posts
        document.getElementById("btn-encore-recherche").classList.add("hidden");
    }
}

window.addEventListener("load", (event) => {
    var btnEncore = document.getElementById("btn-encore-recherche");
    if (btnEncore) {  //S'il y a bien le formulaire dans la page
        btnEncore.addEventListener("click", function (e) {
            e.preventDefault();
            nbClick++;  //La prochaine fois ça sera pour charger encore plus de post
            var data = new FormData(document.getElementById("form-recherche")); //On récupère les infos qu'il y a toujours dans le formulaire de recherche
            data.append("nbClick", nbClick);
            requeteRecherche(data);
        })
    }
})

//--------------BLOG----------------------

var nbClickBlog = 0;
window.addEventListener("load", (event) => {
    var btnEncore = document.getElementById("btn-encore-blog");
    if (btnEncore) {  //S'il y a bien le formulaire dans la page
        if (document.getElementsByClassName("articles").length <= 0) { //Si aucun post n'est affiché
            document.getElementById("btn-encore-blog").classList.add("hidden");
        }
        else if (document.getElementsByClassName("articles").length >= 10) {
            document.getElementById("btn-encore-blog").classList.remove("hidden");
        }
        btnEncore.addEventListener("click", function (e) {
            e.preventDefault();
            nbClickBlog++;  //La prochaine fois ça sera pour charger encore plus de post
            var data = new FormData(document.getElementById("form-load-blog")); //On récupère les infos qu'il y a toujours dans le formulaire de recherche
            data.append("nbClick", nbClickBlog);
            requeteLoadMoreBlog(data);
        })
    }
})

function requeteLoadMoreBlog(data) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = this.response;
            afficheMoreBlog(res);
        }
        if (this.readyState == 4 && this.status == 404) {
            alert("Erreur 404");
        }
    };

    xhr.open("POST", "charger-plus-blog.php", true);
    xhr.responseType = "text";
    xhr.send(data);
}

function afficheMoreBlog(res) {
    document.getElementById("list-post").innerHTML += res;
    var parser = new DOMParser();
    var doc = parser.parseFromString(res, 'text/html'); //On converti le text reçu en html
    if (!res || doc.getElementsByClassName("articles").length < 10) { //Si rien n'est à afficher ou moins de 10 posts
        document.getElementById("btn-encore-blog").classList.add("hidden");
    }
    else {
        document.getElementById("btn-encore-blog").classList.remove("hidden");
    }
}

//--------------COMMENTAIRES----------------------

var commentaireEvent = false;

function clickCommentaire(idPost) {
    document.getElementById("commentaire"+idPost).classList.remove("hidden");
    document.getElementById("btn-commenter"+idPost).classList.add("hidden");
    document.getElementById("annuler-commenter" + idPost).classList.remove("hidden");
    if (commentaireEvent == false) {
        commentaireEvent = true;
        document.getElementById("form-commentaire" + idPost).addEventListener("submit", function (e) {
            e.preventDefault();
            var data = new FormData(this);
            requeteCommentaire(data, idPost);
        })
    }
}

function clickAnnulerCommenter(idPost) {
    document.getElementById("commentaire"+idPost).classList.add("hidden");
    document.getElementById("annuler-commenter"+idPost).classList.add("hidden");
    document.getElementById("btn-commenter"+idPost).classList.remove("hidden");
}

function requeteCommentaire(data, idPost) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = this.response;
            resultatCommentaire(res, idPost, true);
        }
        if (this.readyState == 4 && this.status == 404) {
            alert("Erreur 404");
        }
    };

    xhr.open("POST", "commenter.php", true);
    xhr.responseType = "json";
    xhr.send(data);
}

function resultatCommentaire(res, idPost, forceShow) {
    if (res.sql === true && res.existe === true) { //Si tout est bon
        commentaire(idPost, forceShow);
        document.getElementById("text-commentaire" + idPost).value = "";
        clickAnnulerCommenter(idPost);
        var btnAfficheCommentaire = document.getElementById("btn-affiche-commentaire" + idPost);
        var text = btnAfficheCommentaire.innerHTML;
        var nbCommentaire = text.match(/\d/g);  //On récupère les chiffres de commentaire (le nombre qui est écrit)
        if (nbCommentaire != null) {    //Il y a plus qu'un commentaire
            nbCommentaire = nbCommentaire.join(""); //On rejoint les chiffres trouvés pour faire le nombre
            btnAfficheCommentaire.innerHTML = btnAfficheCommentaire.innerHTML.replace(nbCommentaire, parseInt(nbCommentaire) + 1);    //On change le nombre de commentaires
        }
        else {
            if (btnAfficheCommentaire.innerHTML.search("Pas encore de commentaire") === 0) {
                btnAfficheCommentaire.innerHTML = "Voir le commentaire";    //Il y a 1 commentaire car on en ajoute un et qu'il n'y en avait pas
            }
            else {
                btnAfficheCommentaire.innerHTML = "Voir les 2 commentaires";    //Il y a 2 commentaires car on en ajoute un et qu'il y en avait 1
            }
        }
    }
    else {
        alert("Une erreur est survenue");
    }
}

function commentaire(idPost, forceShow) {
    if (document.getElementById("btn-affiche-commentaire" + idPost).innerHTML.search("Pas encore de commentaire") !== 0) {
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var res = this.response;
                showHideCommentaire(res, idPost, forceShow);
            }
            if (this.readyState == 4 && this.status == 404) {
                alert("Erreur 404");
            }
        };

        xhr.open("POST", "commentaires.php", true);
        xhr.responseType = "text";
        var data = new FormData();
        data.append("idPost", idPost);
        xhr.send(data);   //On envoi idPost à la page commentaires.php
    }
}

function showHideCommentaire(res, idPost, forceShow) {
    var divAffichage = document.getElementById("affichage-commentaire" + idPost);
    var btnAfficherCommentaire = document.getElementById("btn-affiche-commentaire" + idPost);

    if (forceShow || divAffichage.childElementCount <= 0) { //Si on vient d'ajouter un commentaire ou que les commentaires ne sont pas affichés
        document.getElementById("affichage-commentaire" + idPost).innerHTML = res;  //On met le résultat de commentaires.php dans l'élement
        btnAfficherCommentaire.innerHTML = btnAfficherCommentaire.innerHTML.replace("Voir", "Masquer");
    }
    else if (divAffichage.childElementCount > 0) {   //Si les commentaires étaient déjà affichés
        divAffichage.innerHTML = "";    //On enlève l'affichage des commentaires
        btnAfficherCommentaire.innerHTML = btnAfficherCommentaire.innerHTML.replace("Masquer", "Voir");
    }
}

//--------------MODIF PROFIL----------------------

window.addEventListener("load", (event) => {
    var formInscription = document.getElementById("form-modif-profil");
    if (formInscription) {  //S'il y a bien le formulaire dans la page
        formInscription.addEventListener("submit", function (e) {
            e.preventDefault();
            var data = new FormData(this);
            if (verifyImgSize(153600, "input-img")) {   //Taille de l'avatar de 150Ko max
                requeteProfil(data);
            }
        })
    }
})

function requeteProfil(data) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = this.response;
            modifProfil(res);
        }
        if (this.readyState == 4 && this.status == 404) {
            alert("Erreur 404");
        }
    };

    xhr.open("POST", "modifier-profil.php", true);
    xhr.responseType = "json";
    xhr.send(data);
}

function modifProfil(res) {
    document.getElementById("retour-modifProfil").innerHTML = "";

    if (res.pseudo !== true) {
        mettreRouge("input-modif-pseudo");
        ecrireRetour("retour-modifProfil", res.pseudo);
    }
    else {
        supprimerRouge("input-modif-pseudo");
    }
    if (res.mdpOld !== true) {
        mettreRouge("input-mdpOld");
        ecrireRetour("retour-modifProfil", res.mdpOld);
    }
    else {
        supprimerRouge("input-mdpOld");
    }
    if (res.mdpNew !== true) {
        mettreRouge("input-mdpNew");
        ecrireRetour("retour-modifProfil", res.mdpNew);
    }
    else {
        supprimerRouge("input-mdpNew");
    }
    
    let allValueTrue = Object.values(res).every((value) => {
        return value === true;
    })
    if (allValueTrue) { //Si tout est bon
        alert('Les modifications du profil ont été effectuées');
        document.getElementById("retour-modifProfil").innerHTML = "";
        location.reload();
    }
}