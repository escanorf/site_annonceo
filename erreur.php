<?php
include_once('include/init.php');
// le code PHP du fichier se situera entre init et header

// si le paramètre "acces" est défini dans l'URL et si ce paramètre "acces" a pour valeur "interdit"
if (isset($_GET['acces']) && $_GET['acces'] == "interdit") {
    $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                        Accès interdit
                    </div>";
}


// si le paramètre "page" est défini dans l'URL et si ce paramètre "page" a pour valeur "inexistante"
if (isset($_GET['page']) && $_GET['page'] == "inexistante") {
    $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                            Page inexistante
                        </div>";
}




include_once('include/header.php');
?>

<h1 class="text-center m-4">ERREUR</h1>

<?= $erreur ?>
<?php
include_once('include/footer.php');
