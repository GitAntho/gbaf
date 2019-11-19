var com = document.getElementById("addCom");
var comOn = false;
var comElt = document.getElementById("formCom");
com.addEventListener("click", function () {
    if (!comOn){
        comElt.style.display = "block";

        com.textContent = "Fermer l'ajout de commentaire";
    }
    else {
        comElt.style.display = "none";

        com.textContent = "Ajouter un commentaire";
    }
    comOn = !comOn;
});