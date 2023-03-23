<?php

include_once('include/init.php');

//Sécurité 
//la page deconnexion est accessible seulement si un utilisateur est connecté 
if (!membreConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}


//déstruction de la session

session_destroy();

// redirection 

header("Location:" . URL . "index.php");
exit;
