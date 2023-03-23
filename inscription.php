<?php
include_once('include/init.php');


// Sécurité
// la page inscription est accessible seulement si un utilisateur n'est pas connecté
if (membreConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}


if ($_POST) // si j'ai cliqué sur le bouton submit
{

    if (!empty($_POST['email'])) {

        // vérifier si l'email existe dans le champ "email" de la table membre

        // 1e étape : préparation de la requête
        $pdoStatement = $pdoObject->prepare('SELECT * FROM membre WHERE email = :email');
        // 2e étape : association du marqueur :email à sa valeur et son type
        $pdoStatement->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        // 3e étape : exécution de la requête
        $pdoStatement->execute();

        $membreArray = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        //1e condition pour s'inscrire: vérifier que l'email n'existe pas en bdd : vérifier le tableau $membreArray
        if (empty($membreArray)) // si le tableau $membreArray est vide (email non existant) : 
        {

            // 2e condition : si le mdp n'est pas vide 
            if (!empty($_POST['mdp'])) {


                // HASHAGE DU MOT DE PASSE

                $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);


                // PREPARATION DE LA REQUETE 

                // 1e étape : Préparation de la requête 
                $pdoStatement = $pdoObject->prepare('INSERT INTO membre (pseudo,  mdp, nom, prenom, email, telephone, statut, civilite, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :telephone, :statut, :civilite, :date_enregistrement)');

                // 2e étape : Association des marqueurs

                foreach ($_POST as $key => $value) {

                    if (gettype($value) == "string") {
                        $type = PDO::PARAM_STR;
                    } else {
                        $type = PDO::PARAM_INT;
                    }

                    $pdoStatement->bindValue(":$key", $value, $type);
                }

                $pdoStatement->bindValue(":statut", 1, PDO::PARAM_STR);
                $pdoStatement->bindValue(":date_enregistrement", date('Y-m-d H:i:s'), PDO::PARAM_STR);
                $pdoStatement->bindValue(":civilite", $_POST['civilite'], PDO::PARAM_STR);

                // 3e étape :

                $pdoStatement->execute();
                

                // redirection sur la page connexion

                header("Location:" . URL . "connexion.php?compte=enregistre");
                exit;
            } else // else de la 2e condition : le mdp est vide
            {
                $erreur .= '<div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
                <strong>  veuillez saisir un mot de passe ! </strong> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button> </div>';
            }
        } else // else de la 1e condition : l'email existe en bdd donc pas d'inscription possible avec cet email
        {
            $erreur .= '<div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
            <strong> Le pseudo n\'est pas associé à un compte  ,Veuillez vous inscrire  </strong> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button> </div>';
        }
    } else // email VIDE
    {
        $erreur .= '<div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
        <strong>  veuillez saisir un email ! </strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button> </div>';
    }
}



include_once('include/header.php');
?>


<?= $erreur ?>
<?= $notification ?>

<form method="post" class="col-md-4 mx-auto bg-secondary">
    <h1 class="text-center m-4">Inscription</h1>
    <div class="form-group m-3 ">
        <label for="pseudo"></label>
        <input type="text" class="form-control bg-white" id='pseudo' name="pseudo" placeholder="Votre pseudo">
    </div>

    <div class="form-group m-3">
        <label for="mdp"></label>
        <input type="text" class="form-control bg-white" id='mdp' name="mdp" placeholder="Votre mot de passe">
    </div>

    <div class="form-group m-3">
        <label for="nom"></label>
        <input type="text" class="form-control bg-white" id='nom' name="nom" placeholder="Votre nom">
    </div>

    <div class="form-group m-3">
        <label for="prenom"></label>
        <input type="text" class="form-control bg-white" id='prenom' name="prenom" placeholder="Votre prénom">
    </div>

    <div class="form-group m-3">
        <label for="email"></label>
        <input type="text" class="form-control bg-white" id='email' name="email" placeholder="Votre email">
    </div>

    <div class="form-group m-3">
        <label for="telephone"></label>
        <input type="text" class="form-control bg-white" id="telephone" name="telephone" placeholder="Votre Téléphone">
    </div>

    <div class="form-group m-3">
        <label for="civilite"></label>
        <select class="form-control bg-white" name="civilite" id="civilite">
            <option value="m">Homme</option>
            <option value="f">Femme</option>
        </select>
    </div>

    <div class="form-group m-3">
        <input type="submit" class="btn btn-primary col-md-12 mt-3 mb-5" value='Inscription'>
    </div>



</form>




<?php

include_once('include/footer.php'); ?>