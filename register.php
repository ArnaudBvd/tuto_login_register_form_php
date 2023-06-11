<?php
require 'connect.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Email saisi
    // on vérifie si un email a été saisi
    if (empty($_POST['email'])) {
        $errors['email'] = 'Veuillez saisir un email !';
    }

    // Email valide
    // on vérifie si l'email n'est pas valide
    elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'email n\'est pas valide';
    }

    // Email qui n'existe pas déjà
    $request = $pdo->prepare('SELECT * FROM user WHERE email = :email');
    $request->bindParam("email", $_POST["email"]);
    $request->execute();
    $res = $request->fetchAll();

    if (count($res) > 0) {
        $errors['email'] = 'Impossible, ce compte existe déjà';
    }

    // Password saisi
    // on vérifie si un mot de passe a été saisi
    if(empty($_POST["password"])){
        $errors['password'] = "Veuillez saisir un mot de passe";
    }

    // on vérifie si la confirmation du mot de passe a été saisie
    if(empty($_POST["password2"])){
        $errors['password2'] = "Veuillez confirmer votre mot de passe";
    }

    // Le mot de passe fait 4 caractères
    if(strlen($_POST['password'])<4){
        $errors['password'] = "Le mot de passe doit au minimum faire 4 caractères";
    }

    // Password confirmé
    if($_POST['password'] != $_POST['password2']){
        $errors['password2'] = 'Les mots de passe ne sont pas identiques';
    }

    // Si aucune erreur n'a été comptabilisé, on enregistre l'utilisateur et on le redirige
    if(count($errors) == 0){
        // Enregistrer un utilisateur
        $request = $pdo->prepare('INSERT INTO user (email, password) VALUES (:email, :password)');
        $request->bindParam(':email', $_POST['email']);
        $request->bindParam(':password', password_hash($_POST['password'], PASSWORD_DEFAULT));
        $request->execute();

        // Redirection de l'utilisateur vers le login
        header('Location: login.php?message=success-login');
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <h1 class="mb-5 text-center">Créer un compte</h1>

        <form action="" method="post" class="ms-5">
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="text"                       
                value="<?php
                // Permet de garder la saisie de l'utilisateur même si le formulaire est mal renseigné
                 echo (isset($_POST['email'])?$_POST['email']:"") ?>"
                name="email" placeholder="email" class="form-control
                                
                <?php
                // Afficher les classes Bootstrap en fonction de la saisie
                // Bien se placer dans la classe
                if (array_key_exists("email", $errors)) {
                echo ('is-invalid');
                } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                echo ('is-valid');
                } ?>" >

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
                <input type="password" name="password"
                value="<?php
                // Permet de garder la saisie de l'utilisateur même si le formulaire est mal renseigné
                 echo (isset($_POST['password'])?$_POST['password']:"") ?>"
                 id="password" placeholder="Mot de passe"
                 class="form-control

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

                <label for="password2" class="mt-2">Confirmation du mot de passe</label>
                <input type="password" name="password2"
                value="<?php
                // Permet de garder la saisie de l'utilisateur même si le formulaire est mal renseigné
                 echo (isset($_POST['password2'])?$_POST['password2']:"") ?>"
                 id="password2" placeholder="Confirmez le mot de passe" class="form-control

                <?php
                // Afficher les classes Bootstrap en fonction de la saisie
                // Bien se placer dans la classe
                if (array_key_exists("password2", $errors)) {
                echo ('is-invalid');
                } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                echo ('is-valid');
                } ?>">

                <?php
                // Afficher un message après l'input
                if (array_key_exists("password2", $errors)) {
                    echo ('<div id="validationServerUsernameFeedback" class="invalid-feedback">
                ' . $errors['password2'] . '
                </div>');
                } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                    echo ('<div class="valid-feedback">
                    Confirmation valide !
                </div>');
                }
                ?>
            </div>

            <input type="submit" class="btn btn-success my-4">
        </form>

        <a href="login.php">Me connecter</a>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    </div>
</body>

</html>