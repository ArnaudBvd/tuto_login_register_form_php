<?php
session_start();
require 'connect.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Email saisi
    // on vérifie si un email a été saisi
    if(empty($_POST['email'])){
        $errors['email'] = 'Veuillez saisir un email !';
    }

    // Email valide
    // on vérifie si l'email n'est pas valide
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'email n\'est pas valide';
    }

    // Password saisi
    // on vérifie si un mot de passe a été saisi
    if(empty($_POST["password"])){
        $errors['password'] = "Veuillez saisir un mot de passe";
    }

    // On vérifie si l'utilisateur est enregistré sur la BDD
    // On vérifie sur la BDD seulement s'il n'y a pas d'erreur afin d'éviter l'envoie de requêtes inutiles
    if(count($errors) == 0){
        $request = $pdo->prepare('SELECT * FROM user WHERE email = :email');
        $request->bindParam(':email', $_POST['email']);
        $request->execute();
        $res = $request->fetch();
        // la variable $res prend pour valeur les emails des utilisateurs

        // Si un email n'existe pas, $res prend pour valeur false
        // et si le mot de passe ne correspond pas, on renvoie un message d'erreur
        if(!$res || !password_verify($_POST['password'], $res['password'])){
            $errors['password'] = 'Identifiants ou mot de passe incorrect';
        } else {
            // Sinon (un email existe) et donc on vérifie le mot de passe avec le hashage            
            // Le hash correspond, on ajoute la session et on redirige l'utilisateur
                $_SESSION['email'] = $res['email'];
                header('Location: restricted.php');
            }
    }
}
    

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Me connecter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <h1 class="mb-5 text-center">Me connecter</h1>

        <?php
        // Afficher un message en cas d'enregistrement réussi
        if(isset($_GET["message"]) && $_GET["message"] == 'success-login'){
        echo('<div class="alert alert-success" role="alert">
        Vous êtes enregistré !
        </div>');
        }
        ?>

        <?php
        // Afficher un message en cas de déconnexion
        if(isset($_GET["message"]) && $_GET["message"] == 'logout'){
        echo('<div class="alert alert-warning" role="alert">
         Vous êtes déconnecté
        </div>');
        }
        ?>

        <?php
        // Afficher un message si un utilisateur tente de se rendre sur la page restricted via l'URL
        if(isset($_GET["message"]) && $_GET["message"] == 'error-login'){
        echo('<div class="alert alert-danger" role="alert">
         Vous devez dabord vous connecter
        </div>');
        }
        ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Email" class="form-control

                <?php
                // Afficher les classes Bootstrap en fonction de la saisie
                // Bien se placer dans la classe
                if (array_key_exists("email", $errors)) {
                echo ('is-invalid');
                } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                echo ('is-valid');
                } ?>">

                <?php
                // Afficher un message après l'input
                if (array_key_exists("email", $errors)) {
                    echo ('<div id="validationServerUsernameFeedback" class="invalid-feedback">
                ' . $errors['email'] . '
                </div>');
                } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                    echo ('<div class="valid-feedback">
                    Email valide !
                </div>');
                }
                ?>
            </div>

            <div class="form-group mt-3">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" class="form-control
                <?php
                // Afficher les classes Bootstrap en fonction de la saisie
                // Bien se placer dans la classe
                if (array_key_exists("password", $errors)) {
                echo ('is-invalid');
                } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                echo ('is-valid');
                } ?>">

                <?php
                // Afficher un message après l'input
                if (array_key_exists("password", $errors)) {
                    echo ('<div id="validationServerUsernameFeedback" class="invalid-feedback">
                ' . $errors['password'] . '
                </div>');
                } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                    echo ('<div class="valid-feedback">
                    Mot de passe valide !
                </div>');
                }
                ?>
            </div>

            <input type="submit" class="btn btn-success my-3" value="Envoyer">
        </form>

        <a href="register.php">M'enregistrer</a>
    </div>

</body>

</html>