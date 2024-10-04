<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    // $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    // $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

    $select = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND password = '$password'") or die('connexion échoué');

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $_SESSION['users_id'] = $row['id'];
        header('location: index.php');
    } else {
        $message[] = 'Vos informations sont incorrecte!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>akwaba_shops/connexion</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>
    <div class="form-container">
        <form action="" method="post">
            <h2>Connexion</h2>
            <!-- <input type="text" class="box" name="nom" placeholder="Votre Nom" required> -->
            <input type="text" class="box" name="email" placeholder="Votre Email" required>
            <input type="password" class="box" name="password" placeholder="Votre Mot de passe" required>
            <!-- <input type="password" class="box" name="cpassword" placeholder="Confirmer le Mot de passe" required> -->
            <input type="submit" name="submit" class="btn" value="Se connecter">
            <p>Vous n'avez pas de compte? <a href="inscription.php">Inscrivez Vous</a></p>
        </form>
    </div>
</body>

</html>