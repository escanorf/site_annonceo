<?php
include_once('../include/init.php');

ob_start();
// Sécurité
// la page admin est accessible seulement si un utilisateur est connecté
if (!adminConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}

$id_membre = "";

include_once('adminheader.php');

if ((isset($_GET['action']) && ($_GET['action'] == "afficher")) || !isset($_GET['action'])) :

    $pdoStatement = $pdoObject->query('SELECT * FROM membre');

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

                    $pdoStatement->bindValue(":statut", $_POST['statut'], PDO::PARAM_INT);
                    $pdoStatement->bindValue(":date_enregistrement", date('Y-m-d H:i:s'), PDO::PARAM_STR);
                    $pdoStatement->bindValue(":civilite", $_POST['civilite'], PDO::PARAM_STR);

                    // 3e étape :

                    $pdoStatement->execute();

                    // redirection sur la page connexion

                    header("Location:" . URL . "admin/gestionDesMembres.php?action=afficher");
                    exit;
                } else // else de la 2e condition : le mdp est vide
                {
                    $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                                        Veuillez saisir un mot de passe
                                    </div>";
                }
            } else // else de la 1e condition : l'email existe en bdd donc pas d'inscription possible avec cet email
            {
                $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                                L'email " . $_POST['email']  .  " est déjà associé à un compte
                            </div>";
            }
        } else // email VIDE
        {
            $erreur .= "<div class='col-md-6 mx-auto alert alert-danger text-center'>
                            Veuillez saisir un email
                        </div>";
        }
    }

?>
    <h1 class="text-center m-4">Gestion des membres</h1>
    <h3 class="text-center m-4">
        Quantité :
        <span class="badge badge-dark">
            <?= $pdoStatement->rowCount() ?>
        </span>
    </h3>
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/admin.php">Retour</a>
    <!-- Ajouter un membre -->
    <!-- <button class="btn btn-primary float-none"  type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><a href=""></a>
        Ajouter un membre
    </button> -->

    
    <div class="collapse" id="collapseExample">
        <form method="post" class="m-5 mb-5 ">
            <section class="row g-2">
         
                <article class="col-md-sm-6">

                    <!-- pseudo -->
                    <label for="pseudo" class="mt-4">Pseudo</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="pseudo"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" placeholder="pseudo" name="pseudo" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>

                    <!-- mdp -->
                    <label for="mdp" class="mt-4">Mot de passe</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="mdp"><i class="fas fa-suitcase"></i></span>
                        <input type="password" class="form-control" name="mdp" placeholder="Mot de passe" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>

                    <!-- nom -->
                    <label for="nom" class="mt-4">Nom</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="nom"><i class="fas fa-pen"></i></span>
                        <input type="text" class="form-control" name="nom" placeholder="Nom" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>

                    <!-- prenom -->
                    <label for="prenom" class="mt-4">Prénom</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="prenom"><i class="fas fa-pen"></i></span>
                        <input type="text" class="form-control" placeholder="Prénom" name="prenom" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>

                </article>

                <article class="col-md">

                    <!-- email -->
                    <label for="email" class="mt-4">Email</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="email"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Email" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>

                    <!-- telephone -->
                    <label for="telephone" class="mt-4">Telephone</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="telephone"><i class="fas fa-phone-square-alt"></i></span>
                        <input type="number" class="form-control" name="telephone" placeholder="Telephone" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>

                    <!-- civilite -->
                    <label for="civilite" class="mt-4">Civilite</label>
                    <div class="input-group flex-nowrap">
                        <select class="form-control" name="civilite" aria-label="Default select example">
                            <option value="m">Homme</option>
                            <option value="f">Femme</option>
                        </select>
                    </div>

                    <!-- statut -->
                    <label for="statut" class="mt-4">Statut</label>
                    <div class="input-group flex-nowrap">
                        <select class="form-control" name="statut" aria-label="Default select example">
                            <option value="1">Membre</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>

                </article>

            </section>

            <div class="text-center mt-4 mb-6">
                <button class="btn btn-primary" type="submit">Ajouter</button>
            </div>
        </form>
    </div>

    <!-- INPUT POUR RECUPERER LA VALEUR ID MEMBRE -->
    <!-- ********************************************* -->
<?php echo $notification?>

    <table class="table">
        <thead class="thead-dark">
            <tr>
          
                <th scope="col">id membre</th>
                <th scope="col">pseudo</th>
                <th scope="col">nom</th>
                <th scope="col">prenom</th>
                <th scope="col">email</th>
                <th scope="col">telephone</th>
                <th scope="col">civilite</th>
                <th scope="col">statut</th>
                <th scope="col">date d'enregistrement</th>
                <th scope="col">actions</th>
            </tr>
        </thead>
        <tbody>

            <?php
            while ($arrayMembre = $pdoStatement->fetch(PDO::FETCH_ASSOC)) :

            ?>
                <tr>

                    <th scope="row"><?= $arrayMembre['id_membre'] ?></th>
                    <td><?= $arrayMembre['pseudo'] ?></td>
                    <td><?= $arrayMembre['nom'] ?></td>
                    <td><?= $arrayMembre['prenom'] ?></td>
                    <td><?= $arrayMembre['email'] ?></td>
                    <td><?= $arrayMembre['telephone'] ?></td>
                    <td><?php

                        if ($arrayMembre['civilite'] == 'f') {
                            echo 'Femme';
                        } else {
                            echo 'Homme';
                        }

                        ?></td>
                    <td><?php

                        if ($arrayMembre['statut'] == 2) {
                            echo 'Admin';
                        } else {
                            echo 'Membre';
                        }

                        ?></td>
                    <td><?= $arrayMembre['date_enregistrement'] ?></td>
                    <td>
                        <a href="<?= URL ?>admin/gestionDesMembres.php?action=voir&id_membre=<?= $arrayMembre['id_membre'] ?>" style="margin-right: 10px;">
                            <i class="bi bi-zoom-out"></i></a>
                        <a href="<?= URL ?>admin/gestionDesMembres.php?action=modifier&id_membre=<?= $arrayMembre['id_membre'] ?>" style="margin-right: 10px;">
                            <i class="bi bi-pencil"></i> </a>
                        <a href="<?= URL ?>admin/gestionDesMembres.php?action=delete&id_membre=<?= $arrayMembre['id_membre'] ?>" onclick="return (confirm('Confirmez-vous la suppression de ce membre ?'))">
                            <i class="bi bi-x-lg"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php


endif; ?>

<!-- Afficher une fiche membre -->

<?php if (isset($_GET['action']) && ($_GET['action'] == "voir")) :
// VOIR MEMBRE
    $pdoStatement = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre = :id_membre');
    $pdoStatement->bindValue(":id_membre", $_GET['id_membre'], PDO::PARAM_INT);
    $pdoStatement->execute();
// VOIR ANNONCE
    $arrayMembre = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    $pdoStatement3 = $pdoObject->prepare('SELECT * FROM annonce WHERE membre_id = :membre_id');
    $pdoStatement3->bindValue(":membre_id", $_GET['id_membre'], PDO::PARAM_INT);
    $pdoStatement3->execute();
// VOIR MEMBRE 2
    $pdoStatement1 = $pdoObject->prepare('SELECT * FROM note WHERE membre_id2 = :id_membre');
    $pdoStatement1->bindValue(":id_membre", $_GET['id_membre'], PDO::PARAM_INT);
    $pdoStatement1->execute();
    $arrayMembreNote = $pdoStatement1->fetch(PDO::FETCH_ASSOC);
// VOIR MEMBRE1
    $pdoStatement2 = $pdoObject->prepare('SELECT * FROM commentaire WHERE membre_id = :id_membre');
    $pdoStatement2->bindValue(":id_membre", $_GET['id_membre'], PDO::PARAM_INT);
    $pdoStatement2->execute();
    $arrayCommentaire = $pdoStatement2->fetch(PDO::FETCH_ASSOC);
?>


<!-- AFFICHAGE -->
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesMembres.php?action=afficher">Retour</a>

    <h1 class="text-center m-4">Fiche Membre</h1>
    <br>
    <h3 class="text-center m-4">Membre inscrit le : <?php echo $arrayMembre['date_enregistrement'] ?>

        <br><br>
        Id du membre : <?php echo $arrayMembre['id_membre'] ?>
        <br><br>
        Pseudo : <?php echo $arrayMembre['pseudo'] ?>
        <br><br>
        Nombre d'annonces : <?php echo $pdoStatement3->rowCount(); ?>
        <br><br> Prénom : <?php echo $arrayMembre['prenom'] ?>
        <br><br> Nom : <?php echo $arrayMembre['nom'] ?>
        <br><br> Téléphone : <?php echo $arrayMembre['telephone'] ?>
        <br><br> Email : <?php echo $arrayMembre['email'] ?>
        <br><br> Civilité : <?php if ($arrayMembre['civilite'] == 'f') {
                                echo 'Femme';
                            } else {
                                echo 'Homme';
                            }
                            ?>
    </h3>

    <!-- VOIR AVIS -->
    <h3 class="text-center">Avis : </h3>
    <?php while ($arrayMembreNote = $pdoStatement1->fetch(PDO::FETCH_ASSOC)) : ?>

        <br>
        <p class="text-center text-primary"><?php echo $arrayMembreNote['avis'] ?></p>
        <br>

    <?php endwhile; ?>

    <h3 class="text-center">Note :

        <?php Note($arrayMembre['id_membre']) ?> <i class="bi bi-star-fill" style="color: #FFD700"></i>
    </h3>
    <br><br><br>

<?php endif; ?>

<?php
// SUPPRESSION D'UN MEMBRE
// ******************************************
if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    // Vérifier que l'id de l'annonce est défini
    if (isset($_GET['id_membre'])) {
        // Supprimer les commentaires du membre
        $pdoStatement = $pdoObject->prepare('DELETE FROM commentaire WHERE membre_id=:id_membre');
        $pdoStatement->bindValue(':id_membre', $id_membre);
        $pdoStatement->execute();

        // Suppression de l'utilisateur
        $id_membre = $_GET['id_membre'];
        $stmt = $pdoObject->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
        $stmt->bindParam(':id_membre', $id_membre);
        if ($stmt->execute()) {
            // Redirection vers la page de gestion des membres avec une notification de succès
            header('Location: gestionDesMembres.php?success=1');
                // NOTIFICATION
        //     $notification .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
        //     <strong>Félicitations !</strong> Modification de l`\'utilisateur '. $membre['pseudo'] .' réussie !
        //     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        //         <span aria-hidden="true">&times;</span>
        //     </button>
        // </div>';
          
            exit();
        } else {
            // Affichage d'un message d'erreur si la suppression a échoué
            echo "Erreur : la suppression du membre a échoué.";
        }
    } else {
        // Affichage d'un message d'erreur si l'id membre est manquant
        echo "Erreur : l'id membre est manquant.";
    }
}
?>

<!-- Modifier un membre -->
<!-- *************************************** -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "modifier")) :

    $pdoStatement = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre=:id_membre');
    $pdoStatement->bindValue(":id_membre",  $_GET['id_membre'], PDO::PARAM_INT);
    $pdoStatement->execute();
    $arrayMembre = $pdoStatement->fetch(PDO::FETCH_ASSOC);

    if ($_POST) {
        $pdoStatement1 = $pdoObject->prepare('UPDATE membre SET statut = :statut WHERE id_membre=:id_membre');
        $pdoStatement1->bindValue(":id_membre",  $_GET['id_membre'], PDO::PARAM_INT);
        $pdoStatement1->bindValue(":statut",  $_POST['statut'], PDO::PARAM_INT);
        $pdoStatement1->execute();
        header('Location:' . URL . "admin/gestionDesMembres.php?action=afficher");
                       // NOTIFICATION
        //     $notification .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
        //     <strong>Félicitations !</strong> Modification de l`\'utilisateur '. $membre['pseudo'] .' réussie !
        //     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        //         <span aria-hidden="true">&times;</span>
        //     </button>
        // </div>';
    }
  
?>

    <!-- POUR CHANGER LE STATUTS D'UN MEMBRE -->
    <!-- ******************************************* -->
    

    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesMembres.php?action=afficher">Retour</a>
    <h1 class="text-center m-4">Modifier le membre: <?php echo $arrayMembre['prenom'] ?></h1>
    <br>
    <form method="post" class="m-5 mb-5 " name="form_for_modification_membre">
        <section class="row g-2">
            <!-- statut -->
            <label for="statut" class="mt-4">Statut</label>
            <div class="input-group flex-nowrap">
                <select name="statut" class="form-control" aria-label="Default select example">
                    <option value="1">Membre</option>
                    <option value="2">Admin</option>
                </select>
            </div>
        </section>
        <div class="text-center mt-4 mb-6">
            <button class="btn btn-primary" type="submit" name="modifier_membre">Modifier Membre</button>
        </div>
    </form>

<?php

    ob_end_flush();
endif; ?>