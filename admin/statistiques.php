<?php
include_once('../include/init.php');


include_once('adminheader.php');
// <!--  Top 5 des membres les mieux notés  -->




$pdoStatement = $pdoObject->query('SELECT id_membre FROM membre');
$pdoStatement4 = $pdoObject->query('SELECT * FROM membre');
$testarray = $pdoStatement->fetchAll();
$testarray1 = $pdoStatement4->fetchAll(PDO::FETCH_ASSOC);




$y = 0;
$arrayNoteAll = [];
while ($y < count($testarray)) {
  $arrayNoteAll[$testarray1[$y]['prenom']] = Note($testarray[$y]['id_membre']);

  $y++;
}

arsort($arrayNoteAll);

/* foreach($arrayNoteAll as $x=>$x_value)
            {
            echo "Prénom : " . $x . ", Note : " . $x_value;
            echo "<br>";
            }  */

$arrayNoteAllfiltered = array_slice($arrayNoteAll, 0, 5, true);










// <!--  Top 5 des annonces les plus anciennes  -->
$pdoStatement1 = $pdoObject->query('SELECT * FROM annonce ORDER BY date_enregistrement LIMIT 5');

// <!--  Top 5 des catégories contenant le plus d’annonces  -->
$pdoStatement2 = $pdoObject->query('SELECT id_categorie FROM categorie');
$arrayCategorieseule = $pdoStatement2->fetchAll();
$pdoStatement3 = $pdoObject->query('SELECT * FROM categorie');
$arrayCategorieseule1 = $pdoStatement3->fetchAll(PDO::FETCH_ASSOC);

$x = 0;
$arrayCategoriesAll = [];
while ($x < count($arrayCategorieseule)) {
  $arrayCategoriesAll[$arrayCategorieseule1[$x]['titre']] = Categorie($arrayCategorieseule[$x]['id_categorie']);

  $x++;
}
arsort($arrayCategoriesAll);
$arrayCategoriesAllfiltered = array_slice($arrayCategoriesAll, 0, 5, true);
echo '<br>';
echo '<br>';


// <!--  Top 5 des membres les plus actifs par nombre d'annonce  -->


$pdoStatement5 = $pdoObject->query('SELECT id_membre FROM membre');
$arrayidmembre = $pdoStatement5->fetchAll();
$pdoStatement6 = $pdoObject->query('SELECT * FROM membre');
$arrayidmembres = $pdoStatement6->fetchAll(PDO::FETCH_ASSOC);

$z = 0;
$arrayMembresAll = [];
while ($z < count($arrayCategorieseule)) {
  $arrayMembresAll[$arrayidmembres[$z]['prenom']] = Annonce($arrayidmembre[$z]['id_membre']);

  $z++;
}
arsort($arrayMembresAll);
$arrayMembresAllfiltered = array_slice($arrayMembresAll, 0, 5, true);
echo '<br>';
echo '<br>';


?>

<?php

while ($arrayMembre = $pdoStatement4->fetch(PDO::FETCH_ASSOC)) :

?>
  <h3><?php echo $arrayMembre['prenom'] ?><?php echo Note($arrayMembre['id_membre'])  ?></h3>
<?php endwhile; ?>

<h1 class="text-center m-4">Aperçu des statistiques</h1>
<a class="btn btn-primary m-2 p-2 float-right" href="<?= URL ?>admin/admin.php">Retour</a>
<br><br><br><br>

<!--  Top 5 des membres les mieux notés  -->
<div id="accordion">
  <div class="card">
    <div class="card-header bg-dark" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link text-white" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Top 5 des membres les mieux notés
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body text-center">
        <?php
        $y = 1;
        foreach ($arrayNoteAllfiltered as $x => $x_value) {
          echo $y . " - Prénom : " . $x . ", Note : " . $x_value . ' <i class="bi bi-star-fill" style="color: #FFD700"></i>';
          echo "<br>";
          $y++;
        }
        ?>
      </div>
    </div>
  </div>

  <!--  Top 5 des membres les plus actifs  -->
  <div class="card">
    <div class="card-header bg-dark" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Top 5 des membres les plus actifs
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body text-center">
        <?php
        $y = 1;
        foreach ($arrayMembresAllfiltered as $x => $x_value) {
          echo $y . " - Prénom : " . $x . ", Nombre d'annonces : " . $x_value;
          echo "<br>";
          $y++;
        }
        ?>
      </div>
    </div>
  </div>
  <!-- Top 5 des annonces les plus anciennes -->
  <div class="card">
    <div class="card-header bg-dark" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Top 5 des annonces les plus anciennes
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body text-center">
        <?php $i = 1;
        while ($i < 2) :  $arrayAnnonce = $pdoStatement1->fetch(PDO::FETCH_ASSOC) ?>
          <?php echo $i ?> - <?php echo $arrayAnnonce['titre']; ?> postée le : <?php echo $arrayAnnonce['date_enregistrement']; ?><br><br>
        <?php $i++;
        endwhile; ?>
      </div>
    </div>
  </div>
</div>
<!--  Top 5 des catégories contenant le plus d’annonces -->
<div class="card">
  <div class="card-header bg-dark" id="headingThree">
    <h5 class="mb-0">
      <button class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
        Top 5 des catégories contenant le plus d’annonces
      </button>
    </h5>
  </div>
  <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
    <div class="card-body text-center">

      <?php
      $y = 1;
      foreach ($arrayCategoriesAllfiltered as $x => $x_value) {
        echo $y . " - Titre : " . $x . ", Nombre d'annonces : " . $x_value;
        echo "<br>";
        $y++;
      }
      ?>


    </div>
  </div>
</div>
</div>
<?php

include_once('../include/footer.php');
