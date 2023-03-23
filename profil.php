<?php
include_once('include/init.php');

require_once('include/fonctions.php');



$pdoStatement = $pdoObject->prepare('SELECT * FROM annonce WHERE membre_id =:membre_id');
$pdoStatement->bindValue(":membre_id",  $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
$pdoStatement->execute();
// le code PHP du fichier se situera entre init et header

// Sécurité
// la page profil est accessible seulement si un utilisateur est connecté
if (!membreConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}


// si le tableau notification est défini dans la $_SESSION; 
// que dans ce tableau il y a un indice "mdp"
// et que cet indice "mdp" = "modifie"
if (isset($_SESSION['notification']) && isset($_SESSION['notification']['mdp']) && $_SESSION['notification']['mdp'] == "modifie") {
    $notification .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
    <strong>Félicitations !</strong> Modification du mot de passe l`\'utilisateur '."" .' réussie !
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>';
}


// si le tableau notification est défini dans la $_SESSION; 
// que dans ce tableau il y a un indice "profil"
// et que cet indice "profil" = "modifie"
if (isset($_SESSION['notification']) && isset($_SESSION['notification']['profil']) && $_SESSION['notification']['profil'] == "modifie") {
    $notification .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
    <strong>Félicitations !</strong> Modification de l`\'utilisateur '."" .' réussie !
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>';
                       
}

?>



<?php if (isset($_GET['action']) && ($_GET['action'] == "afficher")) :
    include_once('include/header.php'); ?>
    <h1 class="text-center m-4">Profil de <?= $_SESSION['membre']['prenom'] ?></h1>



    <div class="m-4">
    <?= $notification ?>

        <h5 class="list-group-item text-center m-0">Email : <strong> <?= $_SESSION['membre']['email'] ?> </strong></h5>
        <h5 class="list-group-item text-center m-0">Téléphone : <strong> <?= $_SESSION['membre']['telephone'] ?> </strong></h5>
        <h5 class="list-group-item text-center m-0">Nom : <strong> <?= $_SESSION['membre']['nom'] ?> </strong></h5>
        <h5 class="list-group-item text-center m-0">Prénom : <strong> <?= $_SESSION['membre']['prenom'] ?> </strong></h5>

    </div>



    <div class='row justify-content-around'>
        <a class="btn btn-primary col-md-5 col-6 m-1" href="<?= URL ?>modification.php?modification=profil">Modifier le profil</a>
        <a class="btn btn-danger col-md-5 col-6 m-1" href="<?= URL ?>modification.php?modification=mdp">Modifier le mot de passe</a>
    </div>

    <h2 class="text-center m-4">Annonces de : <?= $_SESSION['membre']['prenom'] ?></h2>
    <h3 class="text-center m-4">
        Quantité :
        <span class="badge badge-dark">
            <?= $pdoStatement->rowCount() ?>
        </span>
    </h3>


    <?php if ($pdoStatement->rowCount() > 0) :
        include_once('include/header.php');

    
      
    ?>

<!-- AFFICHAGE DU  TABLEAU DANS PROFIL  -->
        <br><br>
        <div class=" table-responsive">
            <table class="table table-sm">
                <thead class="bg-dark text-white">
                    <tr>
                        <th scope="col">titre</th>
                        <th scope="col">description courte</th>
                        <th scope="col">description longue</th>
                        <th scope="col">prix</th>
                        <th scope="col">photo</th>
                        <th scope="col">pays</th>
                        <th scope="col">ville</th>
                        <th scope="col">adresse</th>
                        <th scope="col">code postal</th>
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

                        $pdoStatement4 = $pdoObject->prepare('SELECT * FROM photo WHERE id_photo=:id_photo');
                        $pdoStatement4->bindValue(":id_photo",  $arrayannonce['photo_id'], PDO::PARAM_INT);
                        $pdoStatement4->execute();
                        $arrayphotos = $pdoStatement4->fetch(PDO::FETCH_ASSOC);

                        $pdoStatement3 = $pdoObject->prepare('SELECT * FROM note WHERE membre_id2 = :id_membre');
                        $pdoStatement3->bindValue(":id_membre", $arrayannonce['membre_id'], PDO::PARAM_INT);
                        $pdoStatement3->execute();
                    ?>
                        <tr>
                            <td><?= $arrayannonce['titre'] ?></td>
                            <td><?= $arrayannonce['description_courte'] ?></td>
                            <td><?= $arrayannonce['description_longue'] ?></td>
                            <td><?= $arrayannonce['prix'] ?> €</td>
                            <td><a href="<?= URL ?>profil.php?action=voir&id_annonce=<?= $arrayannonce['id_annonce'] ?>" style="margin-right: 10px;"><img class="d-block w-100" src="images/imagesUpload/<?= $arrayphotos['photo1'] ?>" alt="<?= $arrayannonce['titre'] ?>"><i class="bi bi-eye-fill"></i>
                                </a></td>
                            <td><?= $arrayannonce['pays'] ?></td>
                            <td><?= $arrayannonce['ville'] ?></td>
                            <td><?= $arrayannonce['adresse'] ?></td>
                            <td><?= $arrayannonce['cp'] ?></td>

                            <td><?= $arrayCategorie['titre'] ?></td>
                            <td><?= $arrayannonce['date_enregistrement'] ?></td>
                            <td>
                                <a href="<?= URL ?>profil.php?action=voir&id_annonce=<?= $arrayannonce['id_annonce'] ?>">
                                    <i class="bi bi-zoom-out"></i>
                                </a>
                                <a href="<?= URL ?>profil.php?action=modifier&id_annonce=<?= $arrayannonce['id_annonce'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= URL ?>profil.php?action=delete&id_annonce=<?= $arrayannonce['id_annonce'] ?>" onclick="return confirm('Confirmez-vous la suppression de cette annonce ?')">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </td>
                        </tr>


                        
                    <?php endwhile; ?>

                </tbody>
            </table>
        </div>
        <br><br>
        <br>
        <h3 class="text-center"> <i class="bi bi-file-earmark-post"></i> Avis : </h3>
       
        <?php while ($arrayMembreNote = $pdoStatement3->fetch(PDO::FETCH_ASSOC)) : ?>

            <br>
            <p class="text-center text-primary"><?php echo $arrayMembreNote['avis'] ?></p>
            <br>

        <?php endwhile; ?>
        <br><br>
        <h3 class="text-center"> Note :

            <?php echo Note($_SESSION['membre']['id_membre']) ?> <i class="bi bi-star-fill" style="color: #FFD700"></i>
        </h3>
        <br><br><br>
    <?php endif; ?>
<?php

    include_once('include/footer.php');
endif; ?>

<?php
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

    header('Location:' . URL . "profil.php?action=afficher");
}

?>

<!-- Affichage d'une seule annonce -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "voir")) :
    include_once('include/header.php');
    $pdoStatement = $pdoObject->prepare('SELECT * FROM annonce WHERE id_annonce=:id_annonce');
    $pdoStatement->bindValue(":id_annonce",  $_GET['id_annonce'], PDO::PARAM_INT);
    $pdoStatement->execute();

    $arrayannonce = $pdoStatement->fetch(PDO::FETCH_ASSOC);

    $pdoStatement1 = $pdoObject->prepare('SELECT * FROM membre WHERE id_membre=:id_membre');
    $pdoStatement1->bindValue(":id_membre",  $arrayannonce['membre_id'], PDO::PARAM_INT);
    $pdoStatement1->execute();
    $arrayMembre = $pdoStatement1->fetch(PDO::FETCH_ASSOC);


    $pdoStatement2 = $pdoObject->prepare('SELECT * FROM photo WHERE id_photo=:id_photo');
    $pdoStatement2->bindValue(":id_photo",  $arrayannonce['photo_id'], PDO::PARAM_INT);
    $pdoStatement2->execute();
    $arrayphoto = $pdoStatement2->fetch(PDO::FETCH_ASSOC);


    $pdoStatement3 = $pdoObject->prepare('SELECT * FROM categorie WHERE id_categorie=:id_categorie');
    $pdoStatement3->bindValue(":id_categorie",  $arrayannonce['categorie_id'], PDO::PARAM_INT);
    $pdoStatement3->execute();
    $arraycategorie = $pdoStatement3->fetch(PDO::FETCH_ASSOC);

    $pdoStatement4 = $pdoObject->prepare('SELECT * FROM commentaire WHERE annonce_id=:annonce_id');
    $pdoStatement4->bindValue(":annonce_id",  $arrayannonce['id_annonce'], PDO::PARAM_INT);
    $pdoStatement4->execute();

?>

 
 <br>
 <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>profil.php?action=afficher">Retour</a>
<!-- AFFICHAGE PRODUIT  -->

<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">titre</th>
      <th scope="col">description courte</th>
      <th scope="col">description longue</th>
      <th scope="col">annonce postée</th>
      <th scope="col">prix</th>
      <th scope="col">adresse</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"><?=$arrayannonce['titre'] ?></th>
      <td><?=$arrayannonce['description_courte'] ?></td>
      <td><?=$arrayannonce['description_longue'] ?></td>
      <td><?=$arrayannonce['date_enregistrement'] ?> <br> catégorie : <?=$arraycategorie['titre'] ?></td>
      <td><?=$arrayannonce['prix'] ?> €</td>
      <td><?=$arrayannonce['adresse'] ?> <br> <?=$arrayannonce['ville'] ?> <br> <?= $arrayannonce['cp'] ?> </td>
    </tr>
  </tbody>
</table>

<h3 class="text-center">Commentaires : </h3> 
    <?php while ($arrayCommentaire = $pdoStatement4->fetch(PDO::FETCH_ASSOC)) : ?>
        <p class="text-center text-primary"><?php echo $arrayCommentaire['commentaire'] ?> </p>
        <br>
    <?php endwhile; ?>
    <br>
    <h6 class="text-center">Photos : </h6>
    <br>
    <div class="d-flex justify-content-center">
        <div class="col-6">
            <!-- carrousel photos  a revoir-->
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
                        <img class="d-block w-50" src="images/imagesUpload/<?= $arrayphoto['photo1'] ?>" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-50" src="images/imagesUpload/<?= $arrayphoto['photo2'] ?>" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-50" src="images/imagesUpload/<?= $arrayphoto['photo3'] ?>" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-50" src="images/imagesUpload/<?= $arrayphoto['photo4'] ?>" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-50" src="images/imagesUpload/<?= $arrayphoto['photo5'] ?>" alt="Second slide">
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
<?php

    include_once('include/footer.php');
endif; ?>



<!-- Modifier une annonce -->
<?php if (isset($_GET['action']) && ($_GET['action'] == "modifier")) :
    include_once('include/header.php');

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

        $notification .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
        <strong>Félicitations !</strong> Modification de l`\'utilisateur '. $membre['pseudo'] .' réussie !
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
    }

?>

<!-- AFFICHAGE DE LA PAGE MODIFICATION -->
<!-- *********************************************** -->
    <a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>profil.php?action=afficher">Retour</a>
    <h1 class="text-center m-4">Modifier l'annonce: <?php echo $arrayAnnonce['titre'] ?></h1>
    <br>
    <form method="post" class="m-5 mb-5 " name="form_for_modification_annonce">


        <?= $notification ?>
        <section class="row g-2">
             
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

<?php



endif; ?>



