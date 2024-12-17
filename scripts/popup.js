function showPopup(objectName) {
    var objects = document.getElementsByClassName("popup");
    Array.from(objects).forEach((obj) => {
        obj.classList.remove("active"); //Suppression du active sur tous les autres objets (pour pas avoir plusieurs popup affichées)
    });
    document.getElementById(objectName).classList.add("active");    //Ajout de la classe active pour que le formulaire s'affiche (modification dans le css)
}

function hidePopup(objectName) {
    var objects = document.getElementsByClassName("popup");
    Array.from(objects).forEach((obj) => {
        obj.classList.remove("active"); //Suppression du active sur tous les autres objets (pour pas avoir plusieurs popup affichées)
    });
    document.getElementById(objectName).classList.remove("active"); //Suppression de la classe active pour que le formulaire se ferme (se cache avec modification dans le css)
}