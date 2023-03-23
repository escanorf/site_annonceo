<?php


// ----- FONCTION MEMBRE CONNECTÉ 


function membreConnecte()
{

    if (isset($_SESSION['membre'])) // true
    {
        return true;
    } else // false
    {
        return false;
    }
}

// ----- FONCTION ADMIN CONNECTÉ


function adminConnecte()
{

    if (membreConnecte() && $_SESSION['membre']['statut'] == 2) {
        return true;
    } else {
        return false;
    }
}





/*

// ----- FONCTION Top 5 des membres les mieux notés en Local
// *****************************************************************
// ********************************************************************
function Note($inputMembreID)
{
    $i = 0;
    $arrayNotes = [];
    $pdoObject = new PDO('mysql:host=localhost; dbname=annonceo', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $pdoStatement = $pdoObject->prepare('SELECT note FROM note WHERE note IS NOT NULL AND membre_id2=:membre_id2 ');
    $pdoStatement->bindValue(":membre_id2",  $inputMembreID, PDO::PARAM_INT);
    $pdoStatement->execute();


    $arrayNote = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    while ($i < count($arrayNote)) {
        array_push($arrayNotes, $arrayNote[$i]['note']);
        $i++;
    }
    if (count($arrayNotes) > 0) {

        $resultatNote = @array_sum($arrayNotes) / count($arrayNotes);

        $resultatarroundi = round($resultatNote, 1);
        if (is_nan($resultatarroundi)) {
            return;
        } else {

            return $resultatarroundi;
        }
    } else {
        return;
    }
}
// ----- FONCTION Top 5 des membres les plus actifs

function Annonce($inputMembreID)
{

    $i = 0;
    $arrayCategorie = [];
    $pdoObject = new PDO('mysql:host=localhost; dbname=annonceo', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $pdoStatement = $pdoObject->prepare('SELECT * FROM annonce WHERE membre_id=:membre_id ');
    $pdoStatement->bindValue(":membre_id",  $inputMembreID, PDO::PARAM_INT);
    $pdoStatement->execute();


    $resultatnombreAnnonces = $pdoStatement->rowCount();


    if (is_nan($resultatnombreAnnonces)) {
        return;
    } else {

        return $resultatnombreAnnonces;
    }
}

// ----- FONCTION Top 5 des catégories contenant le plus d’annonces


function Categorie($inputCategorieID)
{

    $pdoObject = new PDO('mysql:host=localhost; dbname=annonceo', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $pdoStatement = $pdoObject->prepare('SELECT * FROM annonce WHERE categorie_id=:categorie_id ');
    $pdoStatement->bindValue(":categorie_id",  $inputCategorieID, PDO::PARAM_INT);
    $pdoStatement->execute();


    $resultatCategorie = $pdoStatement->rowCount();


    if (is_nan($resultatCategorie)) {
        return;
    } else {

        return $resultatCategorie;
    }
}


*/





// ----- FONCTION Top 5 des membres les mieux notés en ligne
// ********************************************************************
// ********************************************************************

function Note($inputMembreID)
{
    $i = 0;
    $arrayNotes = [];
    $pdoObject = new PDO('mysql:host=cl1-sql12; dbname=qgj74851', 'qgj74851', 'ZVVVSSr3rPBm', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $pdoStatement = $pdoObject->prepare('SELECT note FROM note WHERE note IS NOT NULL AND membre_id2=:membre_id2 ');
    $pdoStatement->bindValue(":membre_id2",  $inputMembreID, PDO::PARAM_INT);
    $pdoStatement->execute();


    $arrayNote = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    while ($i < count($arrayNote)) {
        array_push($arrayNotes, $arrayNote[$i]['note']);
        $i++;
    }
    if (count($arrayNotes) > 0) {

        $resultatNote = @array_sum($arrayNotes) / count($arrayNotes);

        $resultatarroundi = round($resultatNote, 1);
        if (is_nan($resultatarroundi)) {
            return;
        } else {

            return $resultatarroundi;
        }
    } else {
        return;
    }
}
// ----- FONCTION Top 5 des membres les plus actifs

function Annonce($inputMembreID)
{

    $i = 0;
    $arrayCategorie = [];
    $pdoObject = new PDO('mysql:host=cl1-sql12; dbname=qgj74851', 'qgj74851', 'ZVVVSSr3rPBm', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $pdoStatement = $pdoObject->prepare('SELECT * FROM annonce WHERE membre_id=:membre_id ');
    $pdoStatement->bindValue(":membre_id",  $inputMembreID, PDO::PARAM_INT);
    $pdoStatement->execute();


    $resultatnombreAnnonces = $pdoStatement->rowCount();


    if (is_nan($resultatnombreAnnonces)) {
        return;
    } else {

        return $resultatnombreAnnonces;
    }
}

// ----- FONCTION Top 5 des catégories contenant le plus d’annonces


function Categorie($inputCategorieID)
{

    $pdoObject = new PDO('mysql:host=cl1-sql12; dbname=qgj74851', 'qgj74851', 'ZVVVSSr3rPBm', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $pdoStatement = $pdoObject->prepare('SELECT * FROM annonce WHERE categorie_id=:categorie_id ');
    $pdoStatement->bindValue(":categorie_id",  $inputCategorieID, PDO::PARAM_INT);
    $pdoStatement->execute();


    $resultatCategorie = $pdoStatement->rowCount();


    if (is_nan($resultatCategorie)) {
        return;
    } else {

        return $resultatCategorie;
    }
}



function debug($var,$mode = 1){
    // fonction predefinie qui retourne entre autre le nom du fichier dans lequel on code , la ligne concerneé
    $trace = debug_backtrace();
    // array_shift , fonction predefinie qui permet de contourner une dimension d'un tableau (pas de besoin de code $trace[0][file]
    // mais directement $trace[file])
    $trace = array_shift($trace);

    echo "debug demande a la ligne <strong>$trace[line]</strong> , dans le fichier <strong>$trace[file]</strong> ";
    
    // si on ne donne pas de deuxieme parametre , debug() va utiliser un print_r pour analyser notre variable
    if($mode ==1){
        echo "<pre>"; print_r($var); echo "</pre>";
    }
    else{
        // si on lui donne un second parametre , elle fera un var_dump
        echo "<pre>"; var_dump($var); echo "</pre>";
    }
}
