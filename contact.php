<?php
include_once('include/init.php');


include_once('include/header.php');

?>
<?php if ((membreConnecte() && $_SESSION['membre']['statut'] == 1) || (adminConnecte())) :

    if (isset($_POST['envoyer'])) {
        $to = "adonai.samuel@yandex.com";  // verifier spams si pas recu
        $from = $_SESSION['membre']['email'];
        $first_name = $_SESSION['membre']['prenom'];
        $last_name = $_SESSION['membre']['nom'];
        $subject = "Form submission";
        $message = $first_name . " " . $last_name . " wrote the following:" . "\n\n" . $_POST['message'];

        $headers = "From:" . $from;
        mail($to, $subject, $message, $headers);
        echo "<br><div class='text-center text-success'>Message envoyé. Merci " . $first_name . ", Nous vous contacterons sous peu.</div>";
    }
?>

    <form method="post" class="col-md-6 mx-auto" name="form_envoyer_message">
        <br>
        <h1 class="text-center">Envoyer un message</h1>


        <div class="form-group m-3">
            <label for="message"></label>
            <textarea class="form-control" name="message" id="message" rows="3" placeholder="Votre message"></textarea>
        </div>


        <div class="form-group m-3">
            <input type="submit" name="envoyer" class="btn btn-primary col-md-12 mt-3 mb-5" value='Envoyer'>
        </div>

    </form>




<?php else :
    if (isset($_POST['envoyer'])) {
        $to = "adonai.samuel@yandex.com";  // verifier spams si pas recu
        $from = $_POST['email'];
        $first_name = $_POST['prenom'];
        $last_name = $_POST['nom'];
        $subject = "Form submission";
        $message = $first_name . " " . $last_name . " wrote the following:" . "\n\n" . $_POST['message'];

        $headers = "From:" . $from;
        mail($to, $subject, $message, $headers);
        echo "<br><div class='text-center text-success'>Message envoyé. Merci " . $first_name . ", Nous vous contacterons sous peu.</div>";
    }
?>
    <form method="post" class="col-md-6 mx-auto" name="form_envoyer_message">
        <br>
        <h1 class="text-center">Envoyer un message</h1>

        <div class="form-group m-3">
            <label for="prenom"></label>
            <input type="text" class="form-control bg-white" id='prenom' name="prenom" placeholder="Votre prénom">
        </div>

        <div class="form-group m-3">
            <label for="nom"></label>
            <input type="text" class="form-control bg-white" id='nom' name="nom" placeholder="Votre nom">
        </div>


        <div class="form-group m-3">
            <label for="email"></label>
            <input type="email" class="form-control bg-white" id='email' name="email" placeholder="Votre email">
        </div>
        <div class="form-group m-3">
            <label for="message"></label>
            <textarea class="form-control" name="message" id="message" rows="3" placeholder="Votre message"></textarea>
        </div>


        <div class="form-group m-3">
            <input type="submit" name="envoyer" class="btn btn-primary col-md-12 mt-3 mb-5" value='Envoyer'>
        </div>

    </form>



<?php endif; ?>
<?php

include_once('include/footer.php');
