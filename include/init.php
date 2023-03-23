<?php
// PATH=$PATH:/Applications/MAMP/Library/bin
// export PATH (à mettre si besoin)
// mysql -u root -p
// mot de passe : root

// ce fichier init.php est le premier fichier à inclure dans les pages, il contient toutes les bases du projet


/*
// EN LOCAL
// ------------ CONNEXION A LA BDD

$pdoObject = new PDO('mysql:host=localhost; dbname=annonceo', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));


// ------------ CHEMINS

define("URL", "http://localhost/annonceo/");
// define("URL", "WWWW.NOMDOMAINE.FR/");
// MISE EN LIGNE NE PAS OUBLIER LE CHEMIN DE L IMAGE BACKGROUND EN CSS
// VIDER LE CACHE CTRL MAJ R



define("RACINE_DOSSIER", $_SERVER['DOCUMENT_ROOT'] . "/annonceo/images/imagesUpload/");

*/



// EN LIGNE

// ------------ CONNEXION A LA BDD

$pdoObject = new PDO('mysql:host=cl1-sql12; dbname=qgj74851', 'qgj74851', 'ZVVVSSr3rPBm', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));


// ------------ CHEMINS

define("URL", "https://back.devadon.fr/");
// define("URL", "WWWW.NOMDOMAINE.FR/");
// MISE EN LIGNE NE PAS OUBLIER LE CHEMIN DE L IMAGE BACKGROUND EN CSS
// VIDER LE CACHE CTRL MAJ R



// chemin pour recuperer les photos
define("RACINE_IMAGES", "images/imagesUpload/");







// ------------ VARIABLES

$notification = '';
$erreur = '';
$sql = ['1 = 1'];


// ------------ OUVERTURE DE LA SESSION

session_start();


// ------------ FAILLES XSS

// sécurité des formulaires // pour éviter des scripts envoyés en bdd

foreach ($_POST as $key => $value) {
    $_POST[$key] = strip_tags(trim($value));
}

// strip_tags() permet de supprimer les balises
// trim() permet de supprimer les espaces en début et fin de chaîne


// ------------- INCLUSIONS

require_once('fonctions.php');
