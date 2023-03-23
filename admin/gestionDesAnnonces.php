<?php
include_once('../include/init.php');

// Sécurité
// la page admin est accessible seulement si un utilisateur est connecté
if (!adminConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}


// <!-- Fonction : SUPPRIMER UNE ANNONCE-->
// *****************************************************

if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id_annonce'])) {
    $id_annonce = $_GET['id_annonce'];

    // Supprimer tous les commentaires liés à l'annonce
    $pdoStatement = $pdoObject->prepare('DELETE FROM commentaire WHERE annonce_id=:id_annonce');
    $pdoStatement->bindValue(':id_annonce', $id_annonce);
    $pdoStatement->execute();

    // Supprimer l'annonce elle-même
    $pdoStatement = $pdoObject->prepare('DELETE FROM annonce WHERE id_annonce=:id_annonce');
    $pdoStatement->bindValue(':id_annonce', $id_annonce);
    $pdoStatement->execute();

    header('Location:' . URL . "/admin/gestionDesAnnonces.php?action=afficher");
    $notification .= "<div class='col-md-6 mx-auto alert alert-danger text-center disparition'>
    Votre annonces à bien ete supprimer</div>";
}



// <!-- Affichage de toutes les annonces -->
// *****************************************************
if (isset($_GET['action']) && ($_GET['action'] == "afficher")) :
    include_once('adminheader.php');
    $pdoStatement = $pdoObject->query('SELECT * FROM annonce');

    if ($_POST) {

        if (isset($_POST['ordre']) && $_POST['ordre'] == 'prix_ascendant') {
            $pdoStatement = $pdoObject->query('SELECT * FROM annonce ORDER BY prix ASC');
        }
        if (isset($_POST['ordre']) && $_POST['ordre'] == 'prix_descendant') {
            $pdoStatement = $pdoObject->query('SELECT * FROM annonce ORDER BY prix DESC');
        }
        if (isset($_POST['ordre']) && $_POST['ordre'] == 'date_ascendant') {
            $pdoStatement = $pdoObject->query('SELECT * FROM annonce ORDER BY date_enregistrement ASC');
        }
        if (isset($_POST['ordre']) && $_POST['ordre'] == 'date_descendant') {
            $pdoStatement = $pdoObject->query('SELECT * FROM annonce ORDER BY date_enregistrement DESC');
        }
    }

?>

    <!-- AFFICHAGE FORMULAIRE -->
    <!-- *************************************** -->
    <h1 class=" text-center m-4">Gestion des annonces</h1>
    <h3 class="text-center m-4">
        Quantité :
        <span class="badge badge-dark">
            <?= $pdoStatement->rowCount() ?>
        </span>
    </h3>
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/admin.php">Retour</a>
    <form action="" method="post" class="col-sm-4">
        <label for="ordre"></label>
        <select name="ordre" id="ordre" class="form-control col-sm-10" onchange="this.form.submit()">
            <option value="">Trier</option>
            <option value="prix_ascendant">Trier par prix (du - cher au + cher)</option>
            <option value="prix_descendant">Trier par prix (du + cher au - cher)</option>
            <option value="date_ascendant">Trier par date (du - ancien au + ancien)</option>
            <option value="date_descendant">Trier par date (du + ancien au - ancien)</option>
        </select>
        <?= $notification ?>
    </form>
    <br><br>
  
    <!-- fermeture du container que pour cette page  -->
    </div>
    <div></div>
    <div class="container-fluid col-sm-8">
        <div class=" table table-responsive">
            <table class="table ">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th scope="col">id annonce</th>
                        <th scope="col">titre</th>
                        <th scope="col">description courte</th>
                        <th scope="col">description longue</th>
                        <th scope="col">prix</th>
                        <th scope="col">photo</th>
                        <th scope="col">pays</th>
                        <th scope="col">ville</th>
                        <th scope="col">adresse</th>
                        <th scope="col">code postal</th>
                        <th scope="col">membre</th>
                        <th scope="col">catégorie</th>
                        <th scope="col">date d'enregistrement</th>
                        <th scope="col">actions</th>

                    </tr>
                </thead>
                <tbody>

                    <?php
                    while ($arrayannonce = $pdoStatement->fetch(PDO::FETCH_ASSOC)) :
                        $pdoStatement1 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre=:id_membre');
                        $pdoStatement1->bindValue(":id_membre",  $arrayannonce['membre_id'], PDO::PARAM_INT);
                        $pdoStatement1->execute();
                        $arrayMembre = $pdoStatement1->fetch(PDO::FETCH_ASSOC);
                        $pdoStatement2 = $pdoObject->prepare('SELECT * FROM categorie WHERE id_categorie=:id_categorie');
                        $pdoStatement2->bindValue(":id_categorie",  $arrayannonce['categorie_id'], PDO::PARAM_INT);
                        $pdoStatement2->execute();
                        $arrayCategorie = $pdoStatement2->fetch(PDO::FETCH_ASSOC);
                        $pdoStatement3 = $pdoObject->prepare('SELECT * FROM photo WHERE id_photo=:id_photo');
                        $pdoStatement3->bindValue(":id_photo",  $arrayannonce['photo_id'], PDO::PARAM_INT);
                        $pdoStatement3->execute();
                        $arrayphotos = $pdoStatement3->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <tr class="text-center">
                            <th scope="row"><?= $arrayannonce['id_annonce'] ?></th>
                            <td><?= $arrayannonce['titre'] ?></td>
                            <td>
                                <div style="overflow:hidden; width:100%; height:100px"><?= $arrayannonce['description_courte'] ?></div>
                            </td>
                            <td>
                                <div style="overflow:hidden; width:100%; height:100px"><?= $arrayannonce['description_longue'] ?></div>
                            </td>
                            <td><?= $arrayannonce['prix'] ?> €</td>
                            <td><a href="<?= URL ?>admin/gestionDesAnnonces.php?action=voir&id_annonce=<?= $arrayannonce['id_annonce'] ?>" style="margin-right: 10px;"><img class="d-block w-100" src="../images/imagesUpload/<?= $arrayphotos['photo1'] ?>" alt="<?= $arrayannonce['titre'] ?>"><i class="bi bi-eye-fill"></i></a></td>
                            <td><?= $arrayannonce['pays'] ?></td>
                            <td><?= $arrayannonce['ville'] ?></td>
                            <td><?= $arrayannonce['adresse'] ?></td>
                            <td><?= $arrayannonce['cp'] ?></td>
                            <td> <a href="<?= URL ?>admin/gestionDesMembres.php?action=voir&id_membre=<?= $arrayannonce['membre_id'] ?>"><?= $arrayMembre['prenom'] ?></a></td>
                            <td><a href="<?= URL ?>admin/gestionDesCategories.php?action=voir&id_categorie=<?= $arrayannonce['categorie_id'] ?>"><?= $arrayCategorie['titre'] ?></a></td>
                            <td><?= $arrayannonce['date_enregistrement'] ?></td>
                            <td>
                                <a href="<?= URL ?>admin/gestionDesAnnonces.php?action=voir&id_annonce=<?= $arrayannonce['id_annonce'] ?>" style="margin-right: 10px;">
                                    <i class="bi bi-zoom-out"></i></a>
                                <a href="<?= URL ?>admin/gestionDesAnnonces.php?action=modifier&id_annonce=<?= $arrayannonce['id_annonce'] ?>" style="margin-right: 10px;">
                                    <i class="bi bi-pencil"></i> </a>
                                <a href="<?= URL ?>admin/gestionDesAnnonces.php?action=delete&id_annonce=<?= $arrayannonce['id_annonce'] ?>" onclick="return confirm('Confirmez-vous la suppression de cette annonce ?')">
                                    <i class="bi bi-x-lg"></i> </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                </tbody>
            </table>
        </div>
    <?php endif; ?>


    <!-- Affichage d'une seule annonce -->
    <?php if (isset($_GET['action']) && ($_GET['action'] == "voir")) :
      include_once('adminheader.php');
        $pdoStatement = $pdoObject->prepare('SELECT * FROM annonce WHERE id_annonce=:id_annonce');
        // AFFICHAGE DES ANNONCE
        $pdoStatement->bindValue(":id_annonce",  $_GET['id_annonce'], PDO::PARAM_INT);
        $pdoStatement->execute();
        $arrayannonce = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        // AFFICHAGE DES MEMBRES
        $pdoStatement1 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre=:id_membre');
        $pdoStatement1->bindValue(":id_membre",  $arrayannonce['membre_id'], PDO::PARAM_INT);
        $pdoStatement1->execute();
        $arrayMembre = $pdoStatement1->fetch(PDO::FETCH_ASSOC);
        // AFFICHAGE DES PHOTOS
        $pdoStatement2 = $pdoObject->prepare('SELECT * FROM photo WHERE id_photo=:id_photo');
        $pdoStatement2->bindValue(":id_photo",  $arrayannonce['photo_id'], PDO::PARAM_INT);
        $pdoStatement2->execute();
        $arrayphoto = $pdoStatement2->fetch(PDO::FETCH_ASSOC);
        // AFFICHAGE DES CATEGORIES
        $pdoStatement3 = $pdoObject->prepare('SELECT * FROM categorie WHERE id_categorie=:id_categorie');
        $pdoStatement3->bindValue(":id_categorie",  $arrayannonce['categorie_id'], PDO::PARAM_INT);
        $pdoStatement3->execute();
        // AFFICHAGE DES COMMENTAIRES
        $arraycategorie = $pdoStatement3->fetch(PDO::FETCH_ASSOC);
        $pdoStatement4 = $pdoObject->prepare('SELECT * FROM commentaire WHERE annonce_id=:annonce_id');
        $pdoStatement4->bindValue(":annonce_id",  $arrayannonce['id_annonce'], PDO::PARAM_INT);
        $pdoStatement4->execute();


    ?>

        <!-- AFFICHAGE DE LA FICHE -->
        <!-- ***************************************** -->
        <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesAnnonces.php?action=afficher">Retour</a>
        <h6 class="text-center m-4">Id de l'annonce : <?php echo $arrayannonce['id_annonce'] ?></h6>
        <br>
        <h6 class="text-center m-4">Titre : <?php echo $arrayannonce['titre'] ?></h6>
        <br>
        <h6 class="text-center m-4">Description courte : <?php echo $arrayannonce['description_courte'] ?></h6>
        <br>
        <h6 class="text-center m-4">Description longue : <?php echo $arrayannonce['description_courte'] ?></h6>
        <br>
        <h6 class="text-center m-4">Annonce postée par : <?php echo $arrayMembre['prenom'] ?> le : <?php echo $arrayannonce['date_enregistrement'] ?> dans la catégorie : <?php echo $arraycategorie['titre'] ?></h6>
        <br>
        <h6 class="text-center m-4">Prix : <?php echo $arrayannonce['prix'] ?> €</h6>
        <br>
        <h6 class="text-center m-4">Adresse : <?php echo $arrayannonce['adresse'] ?> <?php echo $arrayannonce['cp'] ?> <?php echo $arrayannonce['ville'] ?></h6>
        <br>
        <h3 class="text-center">Commentaires : </h3>
        <?php while ($arrayCommentaire = $pdoStatement4->fetch(PDO::FETCH_ASSOC)) : ?>
            <p class="text-center text-primary"><?php echo $arrayCommentaire['commentaire'] ?> </p>
            <br>
        <?php endwhile; ?>
        <br>
        <br>
        <h6 class="text-center">Photos : </h6>
        <br>
        <div class="d-flex justify-content-center">
            <div class="col-6">
                <!-- carrousel photos -->
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
                    </ol>
                    <div class="carousel-inner">

                        <div class="carousel-item active">
                            <img class="d-block w-100" src="../images/imagesUpload/<?= $arrayphoto['photo1'] ?>" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../images/imagesUpload/<?= $arrayphoto['photo2'] ?>" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../images/imagesUpload/<?= $arrayphoto['photo3'] ?>" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../images/imagesUpload/<?= $arrayphoto['photo4'] ?>" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../images/imagesUpload/<?= $arrayphoto['photo5'] ?>" alt="Second slide">
                        </div>

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>

            </div>
        </div>

        <br><br><br><br>
    <?php endif; ?>

    <!-- Modifier une annonce -->
    <?php if (isset($_GET['action']) && ($_GET['action'] == "modifier")) :
     include_once('adminheader.php');

        $pdoStatement = $pdoObject->prepare('SELECT * FROM annonce WHERE id_annonce=:id_annonce');
        $pdoStatement->bindValue(":id_annonce",  $_GET['id_annonce'], PDO::PARAM_INT);
        $pdoStatement->execute();
        $arrayAnnonce = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if ($_POST) {
            $pdoStatement1 = $pdoObject->prepare('UPDATE annonce SET titre = :titre, description_courte = :description_courte, description_longue = :description_longue, prix = :prix WHERE id_annonce=:id_annonce');
            $pdoStatement1->bindValue(":id_annonce",  $_GET['id_annonce'], PDO::PARAM_INT);
            $pdoStatement1->bindValue(":titre",  $_POST['titre'], PDO::PARAM_STR);
            $pdoStatement1->bindValue(":description_courte",  $_POST['description_courte'], PDO::PARAM_STR);
            $pdoStatement1->bindValue(":description_longue",  $_POST['description_longue'], PDO::PARAM_STR);
            $pdoStatement1->bindValue(":prix",  $_POST['prix'], PDO::PARAM_INT);
            $pdoStatement1->execute();
            header('Location:' . URL . "admin/gestionDesAnnonces.php?action=afficher");
            $notification .= "<div class='col-md-6 mx-auto alert alert-success text-center disparition'>
            Votre annonces à bien ete modifier</div>";
        }

    ?>
        <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/gestionDesAnnonces.php?action=afficher">Retour</a>
        <h1 class="text-center m-4">Modifier l'annonce: <?php echo $arrayAnnonce['titre'] ?></h1>
        <br>
        <form method="post" class="m-5 mb-5 " name="form_for_modification_annonce">
            <section class="row g-2">
            <?= $notification ?>
                <!-- titre -->
                <label for="titre" class="mt-4">Titre</label>
                <div class="input-group flex-nowrap">
                    <input type="text" name="titre" class="form-control" placeholder="Titre de l'annonce">
                </div>

                <!-- Description courte -->
                <label for="description_courte" class="mt-4">Description courte</label>
                <div class="input-group flex-nowrap">
                    <textarea class="form-control" placeholder="Entrez une description courte de l'annonce." id="description_courte" name="description_courte" rows="3"></textarea>
                </div>
                <!-- Description longue -->
                <label for="description_longue" class="mt-4">Description longue</label>
                <div class="input-group flex-nowrap">
                    <textarea class="form-control" placeholder="Entrez une description longue de l'annonce." id="description_longue" name="description_longue" rows="3"></textarea>
                </div>
                <!-- Prix -->
                <label for="prix" class="mt-4">Prix</label>
                <div class="input-group flex-nowrap">
                    <input type="number" name="prix" id="prix" class="form-control" placeholder="Prix">
                </div>

            </section>

            <div class="text-center mt-4 mb-6">
                <button class="btn btn-primary" type="submit" name="modifier_annonce">Modifier Annonce</button>
            </div>
        </form>
    <?php endif; ?>