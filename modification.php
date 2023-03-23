<?php
include_once('include/init.php');
// le code PHP du fichier se situera entre init et header

// Sécurité
// le fichier modification.php est accessible seulement si un utilisateur est connecté
if (!membreConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}

// s'il y a un paramètre "modification" dans l'URL
if (isset($_GET['modification'])) {
?>



    <!-- PAGE WEB : MODIFIER LE PROFIL -->
    <!-- s'il y a le paramètre "modification" dans l'URL et qu'il a pour valeur "profil" : on rentre dans la condition -->
    <?php if (isset($_GET['modification']) && ($_GET['modification'] == "profil")) :

        // on boucle les valeurs du tableau "membre" de $_SESSION
        foreach ($_SESSION['membre'] as $key => $value) {
            // [id_membre] => 9
            // [email] => bldlr170289@gmail.com
            // [mdp] => $2y$10$3JB632.wyOEJM5g3WlgfceUDFdIYOqrM/TaWW93.iQ3HpmyA245Ym
            // [nom] => LORD
            // [prenom] => bart
            // [statut] => 1

            // Si l'indice est défini et donc que la valeur est différente de null
            if (isset($_SESSION['membre'][$key])) {


                $$key = $_SESSION['membre'][$key];
                // premier $ : c'est le dollar pour la syntaxe de la création d'une variable
                // accole à la variable $key
                // $ + $key
                // 1 tour : $ + id_membre
                // 2e tour : $ + email
                // 3e tour : $ + mdp
                // => $id_membre $email $nom $prenom..
                // on est dans une boucle donc seule façon de générer des noms de variables différents à chaque tour

                // $nom = "lord";
                // $prenom = "bart";

            } else // si la valeur de la $key est vide 
            {
                $$key = "";
            }

            // $email 
            // $nom
            // $prenom
            // => les variables sont dans tous les cas définies (vides ou non)



        } // fin du foreach

        if ($_POST) // si j'ai cliqué sur le bouton type submit de mon formulaire
        {
            //echo "<pre>";print_r($_POST);echo "</pre>";

            // Vérification de l'email s'il existe en bdd
            // Requête de préparation

            $pdoStatement = $pdoObject->prepare("SELECT * FROM membre WHERE email = :email");
            $pdoStatement->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
            $pdoStatement->execute();

            // $membreArray est un tableau vide ou pas
            $membreArray = $pdoStatement->fetch(PDO::FETCH_ASSOC);
            //echo "<pre>";print_r($membreArray);echo "</pre>";

            // si le tableau $membreArray n'est pas vide ça veut qu'une ligne de la table membre a été retournée
            // donc l'email existe en bdd je ne peux l'utiliser
            // sauf si l'email est celui qui vient de mon tableau 'membre' de la $_SESSION

            if (!empty($membreArray) && $membreArray['email'] != $_SESSION['membre']['email']) {
                $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center disparition'>
                                    L'email " . $_POST['email']  .  " est déjà associé à un compte
                                </div>";
            } else // le tableau $membreArray est vide ou alors il retourne la ligne concernant l'email dans la $_SESSION
            {
                // requête de préparation pour la modification

                // 1e étape : on prépare la requête avec les marqueurs
                $pdoStatement = $pdoObject->prepare('UPDATE membre SET email =:email, nom = :nom, prenom = :prenom, telephone=:telephone WHERE id_membre = :id_membre');

                // 2e étape : on associe les marqueurs à leurs valeurs
                $pdoStatement->bindValue(":id_membre", $_SESSION['membre']['id_membre'], PDO::PARAM_INT);

                foreach ($_POST as $key => $value) // boucle les 3 key de $_POST email  nom prenom 
                {

                    // condition pour déterminer de quel type est la valeur bouclée
                    // soit une string soit un int
                    // ici on donne une valeur à la variable $type
                    if (gettype($value) == "string") {
                        $type = PDO::PARAM_STR;
                    } else {
                        $type = PDO::PARAM_INT;
                    }


                    $pdoStatement->bindValue(":$key", $value, $type);
                    // $key est l'indice du tableau : email mdp nom prenom
                    // $value sa valeur associée


                } // fin du foreach


                //3e étape  : on exécute la requête
                $pdoStatement->execute();


                // changement des valeurs du tableau 'membre' de la $_SESSION

                $_SESSION['membre']['email'] = $_POST['email'];
                $_SESSION['membre']['telephone'] = $_POST['telephone'];
                $_SESSION['membre']['nom'] = $_POST['nom'];
                $_SESSION['membre']['prenom'] = $_POST['prenom'];


                // Création d'un tableau 'notification' dans $_SESSION
                $_SESSION['notification']['profil'] = "modifie";

                // Redirection sur le fiche profil.php

                header('Location:' . URL . "profil.php?action=afficher");
                exit();
            }
        }

        include_once('include/header.php');
    ?>

        <h1 class="text-center m-4">Modifier le profil</h1>

        <?= $erreur ?>

        <form method="post" class="col-md-6 mx-auto">

            <div class="form-group m-3">
                <label for="email">Email</label>
                <input type="text" class="form-control" id='email' name="email" placeholder="Modifier votre email" value="<?php if (isset($email)) echo $email ?>">
            </div>

            <div class="form-group m-3">
                <label for="telephone">Téléphone</label>
                <input type="number" class="form-control" id='telephone' name="telephone" placeholder="Modifier votre téléphone" value="<?php if (isset($telephone)) echo $telephone ?>">
            </div>


            <div class="form-group m-3">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id='nom' name="nom" placeholder="Modifier votre nom" value="<?php if (isset($nom)) echo $nom ?>">
            </div>


            <div class="form-group m-3">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id='prenom' name="prenom" placeholder="Modifier votre prénom" value="<?php if (isset($prenom)) echo $prenom ?>">
            </div>

            <div class="row justify-content-around ">
                <input type="submit" class="btn btn-success col-md-5 col-12 m-1" href="<?= URL ?>profil.php?action=afficher" value='Modifier'>
                <a class="btn btn-info col-md-5 col-12 m-1 " href="<?= URL ?>profil.php?action=afficher">Retour sur le profil</a>
            </div>

        </form>

    <?php endif; ?>



    <!-- PAGE WEB : MODIFIER LE MOT DE PASSE -->
    <!-- s'il y a le paramètre "modification" dans l'URL et qu'il a pour valeur "motdepasse" : on rentre dans la condition -->
    <?php if (isset($_GET['modification']) && ($_GET['modification'] == "mdp")) :

        if ($_POST) // si j'ai cliqué sur le bouton type submit
        {
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";

            // 1e condition : comparer le champ ancien mdp avec le mdp de la $_SESSION (pas besoin de faire une requête de Sélection car tout est dans le tableau "membre" de la $_SESSION)

            if (password_verify($_POST['old_mdp'], $_SESSION['membre']['mdp'])) // true
            {
                // 2e condition : comparer les 2 nouveaux mdp 
                if ($_POST['new_mdp'] == $_POST['confirm_mdp']) // s'ils sont identiques 
                {
                    // 3e condition : les nouveaux ne sont pas vides
                    if (!empty($_POST['new_mdp'])) {
                        // 4e condition : le nouveau mdp est différent de l'ancien
                        if ($_POST['new_mdp'] != $_POST['old_mdp']) {
                            // on peut modifier maintenant le mot de passe

                            // hash du nouveau mot de passe
                            $_POST['new_mdp'] = password_hash($_POST['new_mdp'], PASSWORD_DEFAULT);

                            // requête de préparation de modification : update 

                            // 1e étape : préparation de la requête avec les marqueurs
                            $pdoStatement = $pdoObject->prepare('UPDATE membre SET mdp = :mdp WHERE id_membre = :id_membre');

                            // 2e étape : association des marqueurs à leurs valeurs
                            $pdoStatement->bindValue(":id_membre", $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
                            $pdoStatement->bindValue(":mdp", $_POST['new_mdp'], PDO::PARAM_STR);

                            // 3e étape : exécution de la requête de préparation
                            $pdoStatement->execute();

                            // changer la valeur du mdp dans la $_SESSION
                            $_SESSION['membre']['mdp'] = $_POST['new_mdp'];

                            // Création d'un tableau "notification" dans la $_SESSION
                            $_SESSION['notification']['mdp'] = "modifie";

                            // redirection sur la page profil.php
                            header('Location:' . URL . 'profil.php?action=afficher');
                            exit;
                        } else // else de la 4e condition : le nouveau mdp est le même que l'ancien mdp ==> inutile
                        {
                            $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                                                Veuillez saisir un mot de passe différent de l'ancien
                                            </div>";
                        }
                    } else // else de la 3e condition : les champs des nouveaux mots de passe sont vides
                    {
                        $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                                            Veuillez saisir un mot de passe
                                        </div>";
                    }
                } else // else de la 2e condition : les mdp ne sont pas identiques 
                {
                    $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                                        les mots de passe ne sont pas identiques
                                    </div>";
                }
            } else // else de la 1e condition : false : ancien mdp incorrect
            {
                $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                                    Ancien mot de passe incorrect
                                </div>";
            }
        } // fin du if $_POST

        include_once('include/header.php');
    ?>

        <h1 class="text-center m-4">Modifier le mot de passe</h1>

        <?= $erreur ?>

        <form method="post" class="col-md-6 mx-auto">

            <div class="form-group m-3">
                <label for="old_mdp">Ancien mot de passe</label>
                <input type="text" class="form-control" id='old_mdp' name="old_mdp" placeholder="Saisir votre ancien mot de passe">
            </div>

            <div class="form-group m-3">
                <label for="new_mdp">Nouveau mot de passe</label>
                <input type="text" class="form-control" id='new_mdp' name="new_mdp" placeholder="Saisir votre nouveau mot de passe">
            </div>

            <div class="form-group m-3">
                <label for="confirm_mdp">Confirmation du nouveau mot de passe</label>
                <input type="text" class="form-control" id='confirm_mdp' name="confirm_mdp" placeholder="Confirmer votre nouveau mot de passe">
            </div>

            <div class="row justify-content-around">
                <input type="submit" class="btn btn-success col-md-5 col-8 m-1" value='Modifier'>
                <a class="btn btn-info col-md-5 col-8 m-1" href="<?= URL ?>profil.php?action=afficher">Retour sur le profil</a>
            </div>

        </form>

    <?php endif; ?>


<?php
    include_once('include/footer.php');
} else // sinon (pas de paramètre 'modification') => ERREUR
{
    header("Location:" . URL . "erreur.php?page=inexistante");
    exit;
}
