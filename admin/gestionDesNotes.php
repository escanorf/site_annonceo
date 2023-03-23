<?php

include_once('../include/init.php');


ob_start();

// Sécurité
// la page admin est accessible seulement si un utilisateur est connecté
if (!adminConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}

$pdoStatement = $pdoObject->query('SELECT * FROM note');

// <!-- Fonction : SUPPRIMER Une note-->
if (isset($_GET['action']) && ($_GET['action'] == "supprimer")) {
    if (isset($_GET['id_note'])) {
        $pdoStatement = $pdoObject->prepare('DELETE FROM note WHERE id_note = :id_note');
        $pdoStatement->bindValue(":id_note", $_GET['id_note'], PDO::PARAM_INT);
        $pdoStatement->execute();
        header('Location:' . URL . "admin/gestionDesNotes.php?action=afficher");

        $notification .= "<div class='col-md-6 mx-auto alert alert-success text-center disparition'>
        Votre annonces à bien ete supprimer</div>";
    } else {
        header('Location:' . URL . "erreur.php?page=inexistante");
    }
}




include_once('adminheader.php');
?>
<!-- Affichage de toutes les notes -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "afficher")) :
 include_once('adminheader.php'); ?>

    
    <h1 class="text-center m-4">Gestion des notes</h1>
    <?= $notification ?>
    <h3 class="text-center m-4">
        Quantité :
        <span class="badge badge-dark">
            <?= $pdoStatement->rowCount() ?>
        </span>
    </h3>
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/admin.php">Retour</a>
    <table class=" table table-hover table-striped  text-center mt-2">
        <thead class='thead-dark'>
            <tr>
                <th>id note</th>
                <th>id membre1</th>
                <th>id membre2</th>
                <th>note</th>
                <th>avis</th>
                <th>date d'enregistrement</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($arrayNote = $pdoStatement->fetch(PDO::FETCH_ASSOC)) :
                $pdoStatement1 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre = :id_membre');

                $pdoStatement1->bindValue(":id_membre", $arrayNote['membre_id1'], PDO::PARAM_INT);
                $pdoStatement1->execute();

                $arrayMembre1 = $pdoStatement1->fetch(PDO::FETCH_ASSOC);

                $pdoStatement2 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre = :id_membre');

                $pdoStatement2->bindValue(":id_membre", $arrayNote['membre_id2'], PDO::PARAM_INT);
                $pdoStatement2->execute();

                $arrayMembre2 = $pdoStatement2->fetch(PDO::FETCH_ASSOC);

        
            ?>
                <tr class="text-center">
                    <th><?= $arrayNote['id_note'] ?></th>
                    <td><a href="<?= URL ?>admin/gestionDesMembres.php?action=voir&id_membre=<?= $arrayNote['membre_id1'] ?>"><?= $arrayNote['membre_id1'] . " - " . $arrayMembre1["email"] ?></a></td>
                    <td><a href="<?= URL ?>admin/gestionDesMembres.php?action=voir&id_membre=<?= $arrayNote['membre_id2'] ?>"><?= $arrayNote['membre_id2'] . " - " . $arrayMembre2["email"] ?></a></td>
                    <td><?= $arrayNote['note'] ?> <i class="bi bi-star-fill" style="color: #FFD700"></i></td>
                    <td style="overflow: hidden;"><?= $arrayNote['avis'] ?></td>
                    <td><?= $arrayNote['date_enregistrement'] ?></td>
                    <td>
                        <a href="<?= URL ?>admin/gestionDesNotes.php?action=voir&id_note=<?= $arrayNote['id_note'] ?>" style="margin-right: 10px;">
                            <i class="bi bi-zoom-out"></i></a>
                        <a href="<?= URL ?>admin/gestionDesNotes.php?action=supprimer&id_note=<?= $arrayNote['id_note'] ?>" onclick="return confirm('Confirmez-vous la suppression de cette note ?')">
                            <i class="bi bi-x-lg"></i> </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br><br><br><br>
<?php


endif; ?>


<!-- Affichage d'une seule note -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "voir")) :
    include_once('../include/header.php');
    // VOIR NOTE 
    $pdoStatement = $pdoObject->prepare('SELECT * FROM note WHERE id_note=:id_note');
    $pdoStatement->bindValue(":id_note",  $_GET['id_note'], PDO::PARAM_INT);
    $pdoStatement->execute();
   // VOIR NOTE MEMBRE 1
    $arrayNote = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    $pdoStatement1 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre = :id_membre');
    $pdoStatement1->bindValue(":id_membre", $arrayNote['membre_id1'], PDO::PARAM_INT);
    $pdoStatement1->execute();
 // VOIR NOTE MEMBRE 2
    $arrayMembre1 = $pdoStatement1->fetch(PDO::FETCH_ASSOC);
    $pdoStatement2 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre = :id_membre');
    $pdoStatement2->bindValue(":id_membre", $arrayNote['membre_id2'], PDO::PARAM_INT);
    $pdoStatement2->execute();

    $arrayMembre2 = $pdoStatement2->fetch(PDO::FETCH_ASSOC);

?>
<!-- AFFICHAGE NOTE -->
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesNotes.php?action=afficher">Retour</a>
    <h1 class="text-center m-4">ID de la note : <?php echo $arrayNote['id_note'] ?></h1>
    <br>
    <h3 class="text-center m-4">Note : <?php echo $arrayNote['note'] ?></h3>

    <br><br>
    <h3 class="text-center m-4">Ajouté le : <?php echo $arrayNote['date_enregistrement'] ?>
        <br>
        par : <?php echo $arrayMembre1['prenom'] ?>
        <br>
        pour : <?php echo $arrayMembre2['prenom'] ?>
    </h3>

    <br>
    <br>
    <h3 class="text-center m-4">Avis : <?php echo $arrayNote['avis'] ?></h3>
    <br>
    <br>
    <div class="d-flex justify-content-center">

    </div>

<?php endif; ?>


<!-- Modifier une note MAIS N'EST PAS INTEGRER  -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "modifier")) :
include_once('adminheader.php');
// MODIFIER NOTE
    $pdoStatement = $pdoObject->prepare('SELECT * FROM note WHERE id_note=:id_note');
    $pdoStatement->bindValue(":id_note",  $_GET['id_note'], PDO::PARAM_INT);
    $pdoStatement->execute();

    $arrayNote = $pdoStatement->fetch(PDO::FETCH_ASSOC);


    if ($_POST) {
        $pdoStatement2 = $pdoObject->prepare('UPDATE note SET note = :note, avis = :avis WHERE id_note=:id_note');
        $pdoStatement2->bindValue(":id_note",  $_GET['id_note'], PDO::PARAM_INT);
        $pdoStatement2->bindValue(":note",  $_POST['note'], PDO::PARAM_INT);
        $pdoStatement2->bindValue(":avis",  $_POST['avis'], PDO::PARAM_STR);
        $pdoStatement2->execute();
        header('Location:' . URL . "admin/gestionDesNotes.php?action=afficher");
    }

?>

<!-- AFFICHAGE NOTE -->
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesNotes.php?action=afficher">Retour</a>
    <h1 class="text-center m-4">Modifier la note</h1>
    <h6 class="text-center m-4">ID de la note : <?php echo $arrayNote['id_note'] ?></h6>
    <br>
    <h3 class="text-center m-4">Note : <?php echo $arrayNote['note'] ?></h3>
    <br>
    <h3 class="text-center m-4">Avis : <?php echo $arrayNote['avis'] ?></h3>

    <form method="post" class="col-md-6 mx-auto" name="form_modifier_note">
        <br>
        <h3 class="text-center">Modifier une note</h3>

        <div class="form-group m-3 d-flex justify-content-center">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="note" id="inlineRadio1" value="1">
                <label class="form-check-label" for="inlineRadio1">1</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="note" id="inlineRadio2" value="2">
                <label class="form-check-label" for="inlineRadio2">2</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="note" id="inlineRadio3" value="3">
                <label class="form-check-label" for="inlineRadio3">3</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="note" id="inlineRadio4" value="4">
                <label class="form-check-label" for="inlineRadio4">4</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="note" id="inlineRadio5" value="5">
                <label class="form-check-label" for="inlineRadio5">5</label>
            </div>

        </div>
        <div class="form-group m-3">
            <label for="avis"></label>
            <textarea class="form-control" name="avis" id="avis" rows="3" placeholder="Avis"></textarea>
        </div>

        <div class="form-group m-3">
            <input type="submit" name="modifiernote" class="btn btn-primary col-md-12 mt-3 mb-5" value='Modifier'>
        </div>

    </form>
   
<?php  ob_end_flush();
endif; ?>



