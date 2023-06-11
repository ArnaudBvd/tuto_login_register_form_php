<?php
session_start();

// On vérifie si on a une session
if(!array_key_exists('email', $_SESSION)){
    header('Location: login.php?message=error-login');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Page avec accès restreint !!!</h1>
    <h2>Bonjour <?php echo ($_SESSION['email']) ?></h2>
    <a href="logout.php">Me déconnecter</a>
</body>

</html>