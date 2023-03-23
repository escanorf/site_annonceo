<?php
include_once('../include/init.php');


// Sécurité
// la page admin est accessible seulement si un utilisateur est connecté
if (!adminConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}

// <!-- SUPPRIMER UNe categorie-->
// <!-- ******************************************** -->
if (isset($_GET['action']) && ($_GET['action'] == "supprimer")) {

    if (isset($_GET['id_categorie'])) {
        $pdoStatement = $pdoObject->prepare('DELETE FROM categorie WHERE id_categorie = :id_categorie');
        $pdoStatement->bindValue(":id_categorie", $_GET['id_categorie'], PDO::PARAM_INT);
        $pdoStatement->execute();
        header('Location:' . URL . "admin/gestionDesCategories.php?action=afficher");
    } else {
        header('Location:' . URL . "erreur.php?page=inexistante");
    }
}

// <!-- Affichage de toutes les categories -->
// <!-- ******************************************** -->
if (isset($_GET['action']) && ($_GET['action'] == "afficher")) :
    include_once('adminheader.php');
    $pdoStatement = $pdoObject->query('SELECT * FROM categorie');

    if ($_POST) {

        $pdoStatement1 = $pdoObject->prepare('INSERT INTO categorie (titre, motscles) 
        VALUES (:titre, :motscles) ');
        $pdoStatement1->bindValue(":titre",  $_POST['titre'], PDO::PARAM_STR);
        $pdoStatement1->bindValue(":motscles",  $_POST['motscles'], PDO::PARAM_STR);
        $pdoStatement1->execute();
    }
?>
    <h1 class="text-center m-4">Gestion des catégories</h1>
    <h3 class="text-center m-4">
        Quantité :
        <span class="badge badge-dark">
            <?= $pdoStatement->rowCount() ?>
        </span>
    </h3>
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/admin.php">Retour</a>
    <a href="?action=ajouter" class="btn btn-primary">Ajouter catégorie</a>
    <table class="col-sm-12 table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">id catégorie</th>
                <th scope="col">titre</th>
                <th scope="col">mots clés</th>
                <th scope="col">actions</th>

            </tr>
        </thead>
        <tbody>
            <?php
            while ($arrayCategorie = $pdoStatement->fetch(PDO::FETCH_ASSOC)) :?>
                <tr>
                    <th scope="row"><?= $arrayCategorie['id_categorie'] ?></th>
                    <td><?= $arrayCategorie['titre'] ?></td>
                    <td><?= $arrayCategorie['motscles'] ?></td>
                    <td>
                        <a href="<?= URL ?>admin/gestionDesCategories.php?action=voir&id_categorie=<?= $arrayCategorie['id_categorie'] ?>" style="margin-right: 10px;">
                            <i class="bi bi-zoom-out"></i>
                        </a>
                        <a href="<?= URL ?>admin/gestionDesCategories.php?action=modifier&id_categorie=<?= $arrayCategorie['id_categorie'] ?>" style="margin-right: 10px;">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= URL ?>admin/gestionDesCategories.php?action=supprimer&id_categorie=<?= $arrayCategorie['id_categorie'] ?>" onclick="return confirm('Confirmez-vous la suppression de cette annonce ? Cela supprimera toutes les annonces de cette catégorie')">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><br>
<?php endif; ?>


<!-- AJOUTER une categorie -->
<!-- ********************************** -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "ajouter")) :
   include_once('adminheader.php');
    if ($_POST) {
        $pdoStatement1 = $pdoObject->prepare('INSERT INTO categorie (titre , motscles) VALUES (:titre , :motscles)');
        $pdoStatement1->bindValue(":titre",  $_POST['titre'], PDO::PARAM_STR);
        $pdoStatement1->bindValue(":motscles",  $_POST['motscles'], PDO::PARAM_STR);
        $pdoStatement1->execute();
        header('Location:' . URL . "admin/gestionDesCategories.php?action=afficher");
        $notification .= "<div class='col-md-6 mx-auto alert alert-success text-center disparition'>
        Votre categorie à bien ete ajouter</div>";
    }

?>

    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesCategories.php?action=afficher">Retour</a>
    <h3 class="text-center">Ajouter une nouvelle catégorie :</h3>
    <form method="post" class="m-5 mb-5 " name="form_for_nouvelle_categorie">
    <?= $notification ?>
        <section class="row g-2">
            <!-- titre -->
            <label for="titre" class="mt-4">Titre</label>
            <div class="input-group flex-nowrap">
                <input type="text" name="titre" class="form-control" placeholder="Titre de categorie">
            </div>
            <!-- Mots cles -->
            <label for="motscles" class="mt-4">Mots clés</label>
            <div class="input-group flex-nowrap">
                <textarea class="form-control" placeholder="Entrez les mots clés votre catégorie séparés par des virgules." id="motscles" name="motscles" rows="3"></textarea>
            </div>
        </section>
        <div class="text-center mt-4 mb-6">
            <button class="btn btn-primary" type="submit" name="ajouter_categorie">Ajouter Categorie</button>
        </div>
    </form>

    </div>

<?php

endif; ?>

<!-- Affichage d'une seule categorie -->
<!-- ******************************************** -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "voir")) :
   include_once('adminheader.php');
    $pdoStatement = $pdoObject->prepare('SELECT * FROM categorie WHERE id_categorie=:id_categorie');
    $pdoStatement->bindValue(":id_categorie",  $_GET['id_categorie'], PDO::PARAM_INT);
    $pdoStatement->execute();
    $arrayCategorie = $pdoStatement->fetch(PDO::FETCH_ASSOC);

?>
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesCategories.php?action=afficher">Retour</a>
    <h1 class="text-center m-4"><?php echo $arrayCategorie['titre'] ?></h1>
    <br>
    <h3 class="text-center m-4">Mots clés :</h3>

    <br>
    <p class="text-center text-primary"><?php echo $arrayCategorie['motscles'] ?></p>
    <br>
    <div class="d-flex justify-content-center">
    </div>

<?php

endif; ?>
<!-- Modifier une categorie -->
<!-- ******************************************** -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "modifier")) :
 include_once('adminheader.php');
    $pdoStatement = $pdoObject->prepare('SELECT * FROM categorie WHERE id_categorie=:id_categorie');
    $pdoStatement->bindValue(":id_categorie",  $_GET['id_categorie'], PDO::PARAM_INT);
    $pdoStatement->execute();
    $arrayCategorie = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    if ($_POST) {
        $pdoStatement1 = $pdoObject->prepare('UPDATE categorie SET titre = :titre, motscles = :motscles WHERE id_categorie=:id_categorie');
        $pdoStatement1->bindValue(":id_categorie",  $_GET['id_categorie'], PDO::PARAM_INT);
        $pdoStatement1->bindValue(":titre",  $_POST['titre'], PDO::PARAM_STR);
        $pdoStatement1->bindValue(":motscles",  $_POST['motscles'], PDO::PARAM_STR);
        $pdoStatement1->execute();
        header('Location:' . URL . "admin/gestionDesCategories.php?action=afficher");
    }

?>
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesCategories.php?action=afficher">Retour</a>
    <h1 class="text-center m-4">Modifier la categorie: <?php echo $arrayCategorie['titre'] ?></h1>
    <br>
    <form method="post" class="m-5 mb-5 " name="form_for_modification_categorie">
        <section class="row g-2">
            <!-- titre -->
            <label for="titre" class="mt-4">Titre</label>
            <div class="input-group flex-nowrap">
                <input type="text" name="titre" class="form-control" placeholder="Titre de categorie">
            </div>
            <!-- Mots cles -->
            <label for="motscles" class="mt-4">Mots clés</label>
            <div class="input-group flex-nowrap">
                <textarea class="form-control" placeholder="Entrez les mots clés votre catégorie séparés par des virgules." id="motscles" name="motscles" rows="3"></textarea>
            </div>
        </section>
        <div class="text-center mt-4 mb-6">
            <button class="btn btn-primary" type="submit" name="modifier_categorie">Modifier Categorie</button>
        </div>
    </form>

<?php
endif; ?>