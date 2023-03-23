<?php
include_once('../include/init.php');

if (!adminConnecte()) {
    header("Location:" . URL . "erreur.php?acces=interdit");
    exit;
}

include_once('adminheader.php');
?>

</div>
    
    <div class="d-flex" id="wrapper">
<!-- Sidebar -->
<div class="bg-secondary border-right" id="sidebar-wrapper">
  <div class=" p-3 sidebar-heading text-dark">Annonceo admin </div>
  <div class="list-group list-group-flush">
    <a href="<?= URL ?>admin/gestionDesAnnonces.php?action=afficher" class="list-group-item list-group-item-action bg-light text-light py-4"><button type="button" class="btn btn-outline-secondary text-dark">&nbspGestion &nbspdes&nbsp annonces&nbsp</button></a>
    <a href="<?= URL ?>admin/gestionDesMembres.php?action=afficher" class="list-group-item list-group-item-action bg-light text-light py-4"><button type="button" class="btn btn-outline-secondary text-dark">&nbspGestion &nbspdes&nbsp membres&nbsp</button></a>
    <a href="<?= URL ?>admin/gestionDesCategories.php?action=afficher" class="list-group-item list-group-item-action bg-light text-light py-4"><button type="button" class="btn btn-outline-secondary text-dark">&nbspGestion &nbspdes&nbsp categories&nbsp</button></a>
    <a href="<?= URL ?>admin/gestionDesCommentaires.php?action=afficher" class="list-group-item list-group-item-action bg-light text-light py-4"><button type="button" class="btn btn-outline-secondary text-dark">&nbspGestion &nbspdes&nbsp commentaires&nbsp</button></a>
    <a href="<?= URL ?>admin/gestionDesNotes.php?action=afficher" class="list-group-item list-group-item-action bg-light text-light py-4"><button type="button" class="btn btn-outline-secondary text-dark">&nbspGestion &nbspdes&nbsp notes&nbsp</button></a>
    <a href="<?= URL ?>admin/statistiques.php?action=afficher" class="list-group-item list-group-item-action bg-light text-light py-4"><button type="button" class="btn btn-outline-secondary text-dark">&nbspGestion &nbspdes&nbsp statisques&nbsp</button></a>
    <a href="<?= URL ?>deconnexion.php" class="list-group-item list-group-item-action bg-light text-light py-5"><button type="button" class="btn btn-outline-danger text-dark">Retour sur les annonces</button></a>
  </div>
</div>
<!-- /#sidebar-wrapper -->
  <div class="w-100 ">
  <canvas class="" id="myChart" ></canvas>
  </div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JS pour le graphique -->
<script>

  const ctx = document.getElementById('myChart');

  Chart.defaults.backgroundColor = '';
  Chart.defaults.borderColor = '#6A6A6A';
  

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['janvier', 'fevrier', 'mars', 'avril', 'mai', 'juin' ,'juillet', 'aout','septembre','octobre','novembre','decembre'],
      datasets: [{
        label: '# nombre de passage sur le site',
        data: [46, 56, 49, 71, 65, 68, 43],
        borderWidth: 1,
        backgroundColor:'#74F9FF',
        borderColor:"#6A6A6A",
       
      },
      { 
        label: '# nombre d\'annonce posté',
        data: [21, 39, 23, 10, 12, 23, 20],
        borderWidth: 1,
        backgroundColor:'#FF74CA',
        borderColor:"#6A6A6A",

      }
    ]
    },

    options: {
      scales: {
        y: {
          beginAtZero: true,
        }
      }
    }
  });

</script>
<!-- METTRE UN CONTENU -->

<?php
