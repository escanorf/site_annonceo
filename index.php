<?php
include_once('include/init.php');
// recupere les annonces dans la base de donnees
$arrayAnnonce = '';
$pdoStatement = '';
$add = '';
$arrayTest[] = '';
$pdoStatement3 = $pdoObject->query('SELECT * FROM categorie');
$pdoStatement4 = $pdoObject->query('SELECT DISTINCT ville FROM annonce');
$pdoStatement5 = $pdoObject->query('SELECT * FROM membre');
$pdoStatement6 = $pdoObject->query('SELECT * FROM annonce');


// si l'annonce n'existe pas

if (isset($_GET['annonce']) && $_GET['annonce'] == "inexistant") {
    $erreur .=  '<div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
                    <strong>  Annonce inexistante ! </strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button> </div>';
}



include_once('include/header.php');

?>

 <br><br>
    <div data-aos="fade-right"
     data-aos-offset="300"
     data-aos-easing="ease-in-sine">
    <h1>Le meilleur site de petite annonce à votre service.</h1>
    </div>
<!-- PARTIE INPUT CHOIX MULTIPLE -->
<form method="post" action="" name="form_filtres">
    <br><br>
    <div class="row d-flex">
        <!-- Filtres de recherche -->
        <div class="col-sm-4 col-12">
            <label for="ordre">Trier</label>
            <select name="ordre" id="ordre" class="form-control col-sm-10">
                <option>Trier</option>
                <option value="prix_ascendant">Du - cher au + cher</option>
                <option value="prix_descendant">Du + cher au - cher</option>
                <option value="date_ascendant">Du - ancien au + ancien</option>
                <option value="date_descendant">Du + ancien au - ancien</option>
            </select>
            <!-- Categorie -->
            <label for="categorie">Catégorie</label>
            <select name="categorie" id="categorie" class="form-control col-sm-10">
                <option value="" disabled selected>Toutes les categories</option>
                <?php while ($arrayCategorie = $pdoStatement3->fetch(PDO::FETCH_ASSOC)) : ?>
                    <option value="<?= $arrayCategorie['id_categorie'] ?>" <?php if (isset($_POST['categorie']) && $_POST['categorie'] == $arrayCategorie['id_categorie']) echo "selected" ?>><?= $arrayCategorie['titre']  ?></option>
                <?php endwhile; ?>

            </select>
            <!-- Region -->
            <label for="region">Région</label>
            <select name="region" id="region" class="form-control col-sm-10">
                <option value="" disabled selected>Toutes les régions</option>
                <?php while ($arrayRegion = $pdoStatement4->fetch(PDO::FETCH_ASSOC)) : ?>
                    <option value="<?= $arrayRegion['ville'] ?>"><?= $arrayRegion['ville']  ?></option>
                <?php endwhile; ?>
            </select>
            <!-- Membre -->
            <label for="membre">Membre</label>
            <select name="membre" id="membre" class="form-control col-sm-10">
                <option value="" disabled selected>Tous les membres</option>
                <?php while ($arrayMembre = $pdoStatement5->fetch(PDO::FETCH_ASSOC)) : ?>
                    <option value="<?= $arrayMembre['id_membre'] ?>"><?= $arrayMembre['prenom']  ?></option>
                <?php endwhile; ?>
            </select>
            <div class="row pt-3">
                <div class="col-10 justify-content-center">
                    <input type="submit" value="valider" class="btn btn-primary">
                </div>
            </div>
        </div>

        <!-- Espacement -->
        <div class="col-sm-1"></div>

        <!-- Annonces -->
        <div class=" d-flex col-sm-6 col-12">

</form>




<br><br>
<!-- Affichage des annonces -->
<?php

$sql = array();

if ($_POST) {

    if (!empty($_POST['categorie'])) {
        $categorie = $_POST['categorie'];

        $sql[] = " categorie_id IN (" . $categorie . ") ";
    }
    if (!empty($_POST['region'])) {
        $text = "'";
        $region = $_POST['region'];

        $sql[] = " ville IN (" . $text . $region . $text . ") ";
    }
    if (!empty($_POST['membre'])) {
        $membre = $_POST['membre'];

        $sql[] = " membre_id IN (" . $membre . ") ";
    }
    if (!empty($_POST['prix'])) {
        $prix = $_POST['prix'];

        $sql[] = " prix < " . $prix . " ";
    }

    if (isset($_POST['ordre']) && $_POST['ordre'] == 'prix_ascendant') {
        $add = 'ORDER BY prix ASC';
    }
    if (isset($_POST['ordre']) && $_POST['ordre'] == 'prix_descendant') {
        $add = 'ORDER BY prix DESC';
    }
    if (isset($_POST['ordre']) && $_POST['ordre'] == 'date_ascendant') {
        $add = 'ORDER BY date_enregistrement ASC';
    }
    if (isset($_POST['ordre']) && $_POST['ordre'] == 'date_descendant') {
        $add = 'ORDER BY date_enregistrement ASC';
    }
}

$condition = "";
if (!empty($sql)) {
    $condition = ' AND ';
}


$req = 'SELECT * FROM annonce WHERE 1 ' . $condition . implode(' AND ', $sql) . $add;
// echo $req; //// Pour voir la requete 
$pdoStatement = $pdoObject->query($req);
?>



<?php
while ($arrayAnnonce = $pdoStatement->fetch(PDO::FETCH_ASSOC)) :

    $pdoStatement2 = $pdoObject->prepare('SELECT prenom FROM membre WHERE id_membre = :id_membre');
    $pdoStatement2->bindValue(':id_membre', $arrayAnnonce['membre_id'], PDO::PARAM_INT);
    $pdoStatement2->execute();

    $arrayMembre = $pdoStatement2->fetch(PDO::FETCH_ASSOC);

    $pdoStatement21 = $pdoObject->prepare('SELECT * FROM photo WHERE id_photo = :id_photo');
    $pdoStatement21->bindValue(':id_photo', $arrayAnnonce['photo_id'], PDO::PARAM_INT);
    $pdoStatement21->execute();

    $arrayPhotos = $pdoStatement21->fetch(PDO::FETCH_ASSOC);


?>
    <!-- pagination -->

    <!-- fin pagination -->


 <!-- AFFICHAGE DES ANNONCES  -->
        <div class=" border-top ">
            <div class="card-body d-flex">
                <div class="p-3">
                    <div class="">
                        <!-- image annonce -->
                    <?php if ($arrayPhotos['photo1'] != "") :  ?>
                        <img src="images/imagesUpload/<?= $arrayPhotos['photo1'] ?>" style='width:100px' class="img-fluid rounded" alt="...">
                        <?php else :  ?>
                         <img class='img-fluid rounded' style='width:100px' src="images/default.jpg" alt="" title="Image par défaut">
                    </div>
                    <?php endif;  ?>
                </div>
                <!-- description annonce -->
                <div >
                    <h5 class="card-title"><?= $arrayAnnonce['titre'] ?></h5>
                    <p class="card-text"><?= $arrayAnnonce['description_courte'] ?></p>
                    <p class="text-left"><?= $arrayMembre['prenom'] ?> <?php echo note($arrayAnnonce['membre_id']) ?><i class="bi bi-star-fill" style="color: #FFD700"></i></p>
                    <p class="card-text"><?= $arrayAnnonce['prix'] ?>€</p>
                    <a href="ficheAnnonce.php?id_annonce=<?= $arrayAnnonce['id_annonce'] ?>" class="btn btn-primary">Voir l'annonce<i class=" p-2 bi bi-search"></i></a>
                </div>
            </div>
        </div>

    <?php endwhile;

    ?>
    </div>
    <br><br>

    </div>
    </div>

    <?php

    include_once('include/footer.php');
