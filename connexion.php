<?php
include_once('include/init.php');
// le code PHP du fichier se situera entre init et header

// Sécurité
// la page connexion est accessible seulement si un utilisateur n'est pas connecté
if (membreConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}


// s'il y a le paramètre "compte" dans l'url et que ce paramètre soit égal à "enregistre" : je rentre dans la condition
if (isset($_GET['compte']) && ($_GET['compte'] == "enregistre")) {
    $notification .= "<div class='col-md-6 mx-auto alert alert-success text-center'>
                            Votre inscription a été enregistrée
                        </div>";
}




if (isset($_POST['connexion']) && $_POST['connexion']) {
    if (!empty($_POST['pseudo'])) {

        // Vérification de l'existance du pseudo

        //1e étape 
        $pdoStatement = $pdoObject->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");

        // 2e étape 
        $pdoStatement->bindValue(":pseudo", $_POST['pseudo'], PDO::PARAM_STR);

        // 3e étape 
        $pdoStatement->execute();

        $membreArray = $pdoStatement->fetch(PDO::FETCH_ASSOC);



        // 1e condition pour la connexion : vérifier que l'email existe en bdd
        if (!empty($membreArray)) // si le tableau $membreArray n'est pas vide
        {
            // Comparer les mdp 

            if (password_verify($_POST['mdp'], $membreArray['mdp'])) {
                // pour être connecté, il faut rajouter le tableau $membreArray (c'est-à-dire les infos personnelles de l'utilisateur) dans un tableau qui se trouve dans la superglobale $_SESSION

                foreach ($membreArray as $key => $value) {
                    $_SESSION['membre'][$key] = $value;
                }

                //client
                if ($_SESSION['membre']['statut'] == 1) {
                    header("Location:" . URL . "profil.php?action=afficher");
                    //echo "<meta http-equiv='refresh' content='0'>";
                    $notification .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <strong>  Bienvenue sur Annonceo ! </strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button> </div>';

                    header("Location:" . URL . "profil.php?action=afficher");
                    exit;
                
                } else //admin
                {
                    header("Location:" . URL . "admin/admin.php");
                    //echo "<meta http-equiv='refresh' content='0'>";
                    exit;
                }
            } else //  le mdp ne correspond pas
            {
                $erreur .= '<div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
                    <strong>  Mot de passe incorrect ! </strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button> </div>';
            }
        } else //pas de pseudo en bdd
        {
            $erreur .= '<div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
            <strong> Le pseudo n\'est pas associé à un compte  ,Veuillez vous inscrire  </strong> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button> </div>';
        }
    } else //input pseudo VIDE
    {
        $erreur .='<div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
        <strong>   Veuillez saisir votre pseudo ! </strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button> </div>';
    }
} 
include_once('include/header.php');
?>

<?= $notification ?>
<?= $erreur ?>


<form method="post" class="col-md-4 mx-auto bg-secondary">
    <h1 class="text-center m-3 ">Connexion</h1>
    <div class="form-group m-3 ">
        <label for="pseudo"></label>
        <input type="text" class="form-control bg-white" id='pseudo' name="pseudo" placeholder="Votre pseudo">
    </div>

    <div class="form-group m-3">
        <label for="mdp"></label>
        <input type="text" class="form-control bg-white" id='mdp' name="mdp" placeholder="Votre mot de passe">
    </div>

    <div class="form-group m-3">
        <input type="submit" class="btn btn-primary col-md-12 mt-4 mb-5" name="connexion" value='Connexion'>
    </div>
</form>




<?php
include_once('include/footer.php'); ?>