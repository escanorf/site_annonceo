<?php
include_once('include/init.php');

$pdoStatement2 = $pdoObject->query("SELECT * FROM categorie");

// condition pour la catégorie (FK = valeur obligatoire).............................

// sécurité sur les 5 photos max.....................................................

if ($_POST) {
    //echo "<pre>";print_r($_POST);echo "</pre>";die;
    // pour récupérer les fichiers dans un formulaire il ne faut pas oublier l'attribut enctype="multipart/form-data"

    $nomImage = "";

    $tabImages = [];

    if ($_FILES['image']['name'][0] != "") {

        for ($i = 0; $i < count($_FILES['image']['name']); $i++) {

            // 1e étape : Récupération du nom de l'image 
            $nomImage = date('YmdHis') . "-" . rand(1, 1000) . "-" . $_FILES['image']['name'][$i];

            //$tabImages[] = $nomImage;
            // fonction prédéfinie PHP array_push
            // permet d'intégrer une valeur à la fin d'un tableau
            // 2e argument
            // 1e : le tableau dans lequel on veut ajouter une nouvelle valeur
            // 2e : c'est la valeur
            array_push($tabImages, $nomImage);

            // 2e : Création de l'emplacement de mon image 
            $dossierImage = RACINE_IMAGES . $nomImage;

            // 3e étape : Enregistrer dans l'emplacement le fichier image

            copy($_FILES['image']['tmp_name'][$i], $dossierImage);
        }
    }
    //echo "<pre>"; print_r($tabImages);  echo "</pre>";die;

    $pdoStatement = $pdoObject->prepare('INSERT INTO photo (photo1 , photo2 , photo3 , photo4 , photo5 ) VALUES (:photo1 , :photo2 , :photo3 , :photo4 , :photo5 )');


    if (isset($tabImages[0])) {
        $pdoStatement->bindValue(":photo1", $tabImages[0], PDO::PARAM_STR);
    } else {
        $pdoStatement->bindValue(":photo1", "", PDO::PARAM_STR);
    }


    if (isset($tabImages[1])) {
        $pdoStatement->bindValue(":photo2", $tabImages[1], PDO::PARAM_STR);
    } else {
        $pdoStatement->bindValue(":photo2", "", PDO::PARAM_STR);
    }


    if (isset($tabImages[2])) {
        $pdoStatement->bindValue(":photo3", $tabImages[2], PDO::PARAM_STR);
    } else {
        $pdoStatement->bindValue(":photo3", "", PDO::PARAM_STR);
    }


    if (isset($tabImages[3])) {
        $pdoStatement->bindValue(":photo4", $tabImages[3], PDO::PARAM_STR);
    } else {
        $pdoStatement->bindValue(":photo4", "", PDO::PARAM_STR);
    }

    if (isset($tabImages[4])) {
        $pdoStatement->bindValue(":photo5", $tabImages[4], PDO::PARAM_STR);
    } else {
        $pdoStatement->bindValue(":photo5", "", PDO::PARAM_STR);
    }

    $pdoStatement->execute();



    $id_photo = $pdoObject->lastInsertId();


    // 1e étape : 
    $pdoStatement = $pdoObject->prepare('INSERT INTO annonce (titre, description_courte, description_longue, prix, photo, pays,	ville, 	adresse, cp, membre_id, photo_id, categorie_id, date_enregistrement) 
            VALUES (:titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :cp, :membre_id, :photo_id, :categorie_id, :date_enregistrement) ');

    // 2e étape : 
    foreach ($_POST as $key => $value) {

        if (gettype($value) == "string") {
            $type = PDO::PARAM_STR;
        } else {
            $type = PDO::PARAM_INT;
        }

        $pdoStatement->bindValue(":$key", $value, $type);
    }
    $pdoStatement->bindValue(":date_enregistrement", date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $pdoStatement->bindValue(":membre_id", $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
    $pdoStatement->bindValue(":photo_id", $id_photo, PDO::PARAM_INT);
    $pdoStatement->bindValue(":photo", "", PDO::PARAM_STR);


    // 3e étape
    $pdoStatement->execute();
    //header("Location:" . URL . "ficheAnnonce.php?compte=enregistre");exit;



    header('Location:' . URL . "profil.php?action=afficher");
    $notification .= "<div class='col-md-6 mx-auto alert alert-success text-center disparition'>
    Votre annonces à bien ete ajouter</div>";
}


include_once('include/header.php');
?>

<h1 class="text-center m-4 font-weight-bold ">Déposer une annonce</h1>
<?= $erreur ?>
<?= $notification ?>
<form method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6 col-sm-10 ">

            <div class="form-group m-3">
                <label for="titre" class="font-weight-bold">Titre</label>
                <input type="text" class="form-control" id='titre' name="titre" placeholder="Titre de l'annonce">
            </div>

            <div class="form-group m-3">
                <label for="description_courte" class="form-label font-weight-bold ">Description Courte</label>
                <textarea class="form-control" id="description_courte" placeholder="Description courte de votre annonce" name='description_courte' rows="3"></textarea>
            </div>

            <div class="form-group m-3">
                <label for="description_longue" class="form-label font-weight-bold">Description Longue</label>
                <textarea class="form-control" id="description_longue" placeholder="Description longue de votre annonce" name='description_longue' rows="3"></textarea>
            </div>


            <div class="form-group m-3">
                <label for="prix" class="font-weight-bold">Prix</label>
                <input type="text" class="form-control" id='prix' name="prix" placeholder="Saisissez votre prix">
            </div>


            <div class="form-group m-3">
                <label for="categorie" class="font-weight-bold">Catégorie</label>
                <select class="form-control" name="categorie_id" id="categorie">
                    <option value="" selected hidden>Toutes les catégories</option>
                    <?php while ($categorie = $pdoStatement2->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?= $categorie['id_categorie'] ?>"><?= $categorie['titre'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

        </div>
        <div class="col-sm-10 col-md-6 ">
            <div classe="form-group m-3">

                <label for="photo" class="font-weight-bold ">Photo - 5 photos max</label>
                <input type="file" class="form-control " multiple name="image[]" onchange="loadFile(event)">
                <div class="row" id="image">

                </div>

            </div>
            <div class="form-group m-3">
                <label for="pays" class="font-weight-bold">Pays</label>
                <select class="form-control" name="pays" id="pays">
                    <option value="france">France</option>
                    <option value="espagne">Espagne</option>
                    <option value="belgique">Belgique</option>
                    <option value="italie">Italie</option>
                    <option value="allemagne">Allemagne</option>
                    <option value="portugal">Portugal</option>
                </select>
            </div>

            <div class="form-group m-3">
                <label for="ville" class="font-weight-bold">Ville</label>
                <input type="ville" class="form-control" id='ville' name="ville" placeholder="Saisissez votre ville">
            </div>

            <div class="form-group m-3">
                <label for="adresse" class="form-label font-weight-bold">Adresse</label>
                <textarea class="form-control" id="adresse" name="adresse" rows="3" placeholder="Saisissez votre Adresse"></textarea>
            </div>

            <div class="form-group m-3">
                <label for="cp" class="font-weight-bold">Code Postal</label>
                <input type="text" class="form-control" id='cp' name="cp" placeholder="Saisissez votre code postal">
            </div>

        </div>
    </div>
    <div class="d-flex justify-content-center">
        <button type="submit" id="button" class=" btn btn-primary m-3">Enregister</button>
        <div id="message">
        </div>
    </div>

</form>


<?php
include_once('include/footer.php');
