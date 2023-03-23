<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonceo</title>
    <link rel="stylesheet" href="https://bootswatch.com/4/spacelab/bootstrap.min.css">
    <!-- ajax -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <!-- boostrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- css perso  -->
    <link rel="stylesheet" href="admin/include/css/style.css">
    <!-- ajax pour google map -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- chart.js -->

</head>

<body>

  
    <nav id="navadmin" class="col-sm-12 bg-dark navbar navbar-expand-lg  ">
    <i class="bi bi-bag text-light  p-2"></i><a class="navbar-brand text-white" href="<?= URL ?>index.php">Annonceo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item active">
                    <a class="nav-link text-white" href="<?= URL ?>presentation.php"><i class=" text-white p-2 "></i> </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="<?= URL ?>contact.php"> <i class=" text-white p-2 "></i></a>
                </li>
            </ul>
                    <!--ADMIN CONNECTÉ -->
                <?php if (adminConnecte()) : ?>
                    <li class="nav-item dropdown pr-5">
                        <a class="nav-link  text-white " href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i  class="p-2 bi bi-person-circle"></i>Admin</a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </nav> 


    <div class="container">