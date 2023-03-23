<?php
include_once('../include/init.php');


ob_start();
// Sécurité
// la page admin est accessible seulement si un utilisateur est connecté
if (!adminConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}


$pdoStatement = $pdoObject->query('SELECT * FROM commentaire');


// <!-- Fonction : SUPPRIMER UN COMMENTAIRE-->
// ********************************************************
if (isset($_GET['action']) && ($_GET['action'] == "supprimer")) {

    if (isset($_GET['id_commentaire'])) {


        $pdoStatement = $pdoObject->prepare('DELETE FROM commentaire WHERE id_commentaire = :id_commentaire');
        $pdoStatement->bindValue(":id_commentaire", $_GET['id_commentaire'], PDO::PARAM_INT);
        $pdoStatement->execute();


        header('Location:' . URL . "admin/gestionDesCommentaires.php?action=afficher");
        $notification .= "<div class='col-md-6 mx-auto alert alert-success text-center disparition'>
        Votre annonces à bien ete supprimer</div>";
    } else {
        header('Location:' . URL . "erreur.php?page=inexistante");
    }
}

include_once('adminheader.php');
?>


<!-- Affichage de tous les commentaires -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "afficher")) :
    include_once('adminheader.php'); ?>

   
    <h1 class="text-center m-4">Gestion des commentaires</h1>

    <h3 class="text-center m-4">
        Quantité :
        <span class="badge badge-dark">
            <?= $pdoStatement->rowCount() ?>
        </span>
    </h3>
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/admin.php">Retour</a>
    <?= $notification ?>
    <table class="col-sm-12 table table-hover table-striped  text-center mt-2">
        <thead class="thead-dark">
            <tr>
                <th>id commentaire</th>
                <th>id membre</th>
                <th>id annonce</th>
                <th>commentaire</th>
                <th>date d'enregistrement</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($arrayCommentaire = $pdoStatement->fetch(PDO::FETCH_ASSOC)) :
                $pdoStatement1 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre= :id_membre');
                $pdoStatement1->bindValue(":id_membre",  $arrayCommentaire['membre_id'], PDO::PARAM_INT);
                $pdoStatement1->execute();
                $arrayMembre = $pdoStatement1->fetch(PDO::FETCH_ASSOC);


                $pdoStatement2 = $pdoObject->prepare('SELECT * FROM annonce WHERE id_annonce= :id_annonce');
                $pdoStatement2->bindValue(":id_annonce",  $arrayCommentaire['annonce_id'], PDO::PARAM_INT);
                $pdoStatement2->execute();
                $arrayAnnonce = $pdoStatement2->fetch(PDO::FETCH_ASSOC);
            ?>
                <tr>
                    <td><?= $arrayCommentaire['id_commentaire'] ?></td>
                    <td><a href="<?= URL ?>admin/gestionDesMembres.php?action=voir&id_membre=<?= $arrayCommentaire['membre_id'] ?>"><?= $arrayCommentaire['membre_id'] . " - " . $arrayMembre['email'] ?></a></td>
                    <td><a href="<?= URL ?>admin/gestionDesAnnonces.php?action=voir&id_annonce=<?= $arrayCommentaire['annonce_id'] ?>"><?= $arrayCommentaire['annonce_id'] . " - " . $arrayAnnonce['titre'] ?></a></td>
                    <td><?= $arrayCommentaire['commentaire'] ?></td>
                    <td><?= $arrayCommentaire['date_enregistrement'] ?></td>
                    <td>
                        <a href="<?= URL ?>admin/gestionDesCommentaires.php?action=voir&id_commentaire=<?= $arrayCommentaire['id_commentaire'] ?>" style="margin-right: 10px;">
                            <i class="bi bi-zoom-out"></i> </a>
                        <a href="<?= URL ?>admin/gestionDesCommentaires.php?action=supprimer&id_commentaire=<?= $arrayCommentaire['id_commentaire'] ?>" onclick="return confirm('Confirmez-vous la suppression de ce commentaire ?')">
                            <i class="bi bi-x-lg"></i></a>
                    </td>
                </tr>
            <?php
            endwhile; ?>
        </tbody>
    </table>

<?php

endif; ?>


<!-- Affichage d'un seul commentaire -->
<!-- *************************************** -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "voir")) :
   include_once('adminheader.php');
    $pdoStatement = $pdoObject->prepare('SELECT * FROM commentaire WHERE id_commentaire=:id_commentaire');
    $pdoStatement->bindValue(":id_commentaire",  $_GET['id_commentaire'], PDO::PARAM_INT);
    $pdoStatement->execute();
    $arrayCommentaire = $pdoStatement->fetch(PDO::FETCH_ASSOC);

    $pdoStatement1 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre=:id_membre');
    $pdoStatement1->bindValue(":id_membre",  $arrayCommentaire['membre_id'], PDO::PARAM_INT);
    $pdoStatement1->execute();
    $arrayMembre = $pdoStatement1->fetch(PDO::FETCH_ASSOC);


?>

    <!-- AFFICHAGE COMMENTAIRE  -->
    <!-- **************************************** -->
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesCommentaires.php?action=afficher">Retour</a>
    <h1 class="text-center m-4">Commentaire</h1>
    <br>
    <h3 class="text-center m-4">Commentaire ajouté le : <?php echo $arrayCommentaire['date_enregistrement'] ?> <br> par : <?php echo $arrayMembre['prenom'] ?></h3>

    <br>
    <p class="text-center text-primary"><?php echo $arrayCommentaire['commentaire'] ?></p>
    <br>



<?php endif; ?>

<!-- Modifier un commentaire -->
<!-- ************************************** -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "modifier")) :
include_once('adminheader.php');
    // MODIF COMMENTAIRE
    $pdoStatement = $pdoObject->prepare('SELECT * FROM commentaire WHERE id_commentaire=:id_commentaire');
    $pdoStatement->bindValue(":id_commentaire",  $_GET['id_commentaire'], PDO::PARAM_INT);
    $pdoStatement->execute();
    $arrayCommentaire = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    // MODIF MEMBRE
    $pdoStatement1 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre=:id_membre');
    $pdoStatement1->bindValue(":id_membre",  $arrayCommentaire['membre_id'], PDO::PARAM_INT);
    $pdoStatement1->execute();
    $arrayMembre = $pdoStatement1->fetch(PDO::FETCH_ASSOC);
    if ($_POST) {
        $pdoStatement2 = $pdoObject->prepare('UPDATE commentaire SET commentaire = :commentaire WHERE id_commentaire=:id_commentaire');
        $pdoStatement2->bindValue(":id_commentaire",  $_GET['id_commentaire'], PDO::PARAM_INT);
        $pdoStatement2->bindValue(":commentaire",  $_POST['commentaire'], PDO::PARAM_STR);
        $pdoStatement2->execute();
        header('Location:' . URL . "admin/gestionDesCommentaires.php?action=afficher");
    }

?>

    <!-- AFFICHAGE -->
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesCommentaires.php?action=afficher">Retour</a>
    <h1 class="text-center m-4">Modifier le commentaire</h1>
    <h6 class="text-center">Commentaire numéro <?php echo $arrayCommentaire['id_commentaire'] ?> <br> ajouté le : <?php echo $arrayCommentaire['date_enregistrement'] ?> <br> par : <?php echo $arrayMembre['prenom'] ?></h6>
    <br>

    <form method="post" class="m-5 mb-5 " name="form_for_modification_commentaire">
        <section class="row g-2">
            <!-- Commentaire -->
            <label for="commentaire" class="mt-4">Commentaire</label>
            <div class="input-group flex-nowrap">
                <textarea class="form-control" placeholder="Entrez ici votre commentaire" id="commentaire" name="commentaire" rows="3"></textarea>
            </div>
        </section>
        <div class="text-center mt-4 mb-6">
            <button class="btn btn-primary" type="submit" name="modifier_commentaire">Modifier le commentaire</button>
        </div>
    </form>

<?php endif; ?>