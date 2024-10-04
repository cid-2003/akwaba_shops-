<?php
include 'config.php';
session_start();


$users_id = $_SESSION['users_id'];

if (isset($users_id)) {
    header('location: ');
}
;

if (isset($_GET['logout'])) {
    unset($users_id);
    session_destroy();
    header('location: connexion.php');
}

if (isset($_POST['ajouter_au_produits'])) {
    $produits_nom = $_POST['produits_nom'];
    $produits_prix = $_POST['produits_prix'];
    $produits_img = $_POST['produits_img'];
    $produits_quantite = $_POST['produits_quantite'];

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE nom = '$produits_nom'
    AND users_id = '$users_id'") or die('connexion échoué');

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'Produit prêt a être ajouter a votre panier!';
    } else {
        mysqli_query($conn, "INSERT INTO cart (users_id, nom, prix, img, quantite) VALUES ('users_id', '$produits_nom',
        '$produits_prix', '$produits_img', '$produits_quantite')") or die('connexion échoué');
        $message[] = 'produits ajouter';
    }
}

if(isset($_POST['actualiser_cart'])){
    $actualiser_quantite = $_POST['cart_quantite'];
    $actualiser_id = $_POST['cart_id'];
    mysqli_query($conn, "UPDATE cart SET quantite = '$actualiser_quantite' WHERE id =
     '$actualiser_id'") or die('connexion échoué');
     $message[] = 'Actualiser avec succès!';
}

if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$remove_id'") or die('connexion échoué');
    header('location: index.php');
}

if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM cart ") or die('connexion échoué');
    header('location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>akwaba_shops/market_place</title>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --bleu: #007bff;
            --vert: #28a745;
            --orange: #f58220;
            --gris: #6c757d;
            --noir: #343a40;
            --blanc: #ffffff;
            --bg-light: #ffffffbf;
            --rouge: #ec0f0f;
            --box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
            --border: 2px solid black;
        }

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            text-decoration: none;
        }

        .container {
            padding: 0 20px;
            margin: 0 auto;
            max-width: 100rem;
            padding-bottom: 70px;
        }

        .message {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            padding: 15px 10px;
            background: var(--blanc);
            text-align: center;
            z-index: 1000;
            box-shadow: var(--box-shadow);
            font-size: 20px;
            /* text-transform: capitalize; */
            cursor: pointer;
        }

        .container .heading {
            text-align: center;
            margin-bottom: 20px;
            font-size: 40px;
            text-transform: uppercase;
            color: var(---noir);
        }

        .container .profile {
            padding: 30px;
            text-align: center;
            border: 2px solid black;
            background: var(--blanc);
            box-shadow: var(--box-shadow);
            /* border-radius: 5px; */
            margin: 20px auto;
            max-width: 500px;
        }

        .container .profile p {
            margin-bottom: 10px;
            font-size: 25px;
            color: black;
        }

        .container .profile .flex {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            align-items: flex-end;
        }

        .container .produits .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .container .produits .box-container .box {
            text-align: center;
            border-radius: 5px;
            box-shadow: var(--box-shadow);
            border: 2px solid black;
            position: relative;
            padding: 15px;
            background: var(--blanc);
            width: 450px;
        }

        .container .produits .box-container .box img {
            height: 400px;
        }

        .container .produits .box-container .box input[type="number"] {
            margin: 5x 0;
            width: 100%;
            border: 2px solid black;
            border-radius: 5px;
            font-size: 20px;
            color: var(---noir);
            padding: 15px 18px;
        }

        .container .cart_marcher {
            padding: 20px 0;
        }

        .container .cart_marcher table {
            width: 100%;
            text-align: center;
            border: 2px solid white;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
            color: black;
            padding: 20px 10px;
            /* background-color: white; */
        }

        .container .cart_marcher table thead {
            background-color: var(--noir);
        }

        .container .cart_marcher table thead th {
            padding: 10px;
            color: white;
            text-transform: uppercase;
            font-size: 20px;
        }

        .container .cart_marcher table .table-bottom {
            background-color: var(--bg-light);
        }

        .container .cart_marcher table tr td{
            padding: 10px;
            font-size: 20px;
            color: black;
        }

        .container .cart_marcher table tr td:nth-child(1){
            padding: 0;
        }

        .container .cart_marcher table tr td input[type="number"]{
            width: 80px;
            border: black;
            padding: 12px 14px;
            font-size: 20px;
        }

        .container .cart_marcher .cart-btn{
            margin-top: 10px;
            text-align: center;
        }

        .container .cart_marcher .disabled{
            pointer-events: none;
            background-color: var(---rouge);
            opacity: .5;
            user-select: none;
        }

        @media screen and (max-width: 1200px){
            .container .cart_marcher{
                overflow: scroll;
            }

            .container .cart_marcher table{
                width: 1200px;
            }
        }

        @media screen and (max-width: 450px){
            .container .heading{
                font-size: 30px;
            }

            .container .produits .box-container .box img{
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="profile">
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM users WHERE id = '$users_id'")
                or die('connexion échoué');
            if (mysqli_num_rows($select_users) > 0) {
                $fetch_users = mysqli_fetch_assoc($select_users);
            }
            ;
            ?>
            <p>Nom : <span><?php echo $fetch_users['nom']; ?></span></p>
            <p>email : <span><?php echo $fetch_users['email']; ?></span></p>

            <div class="flex">
                <a href="connexion.php" class="btn">Connexion</a>
                <a href="inscription.php" class="option-btn">Inscription</a>
                <a href="index.php?logout=<?php echo $users_id; ?>" onclick="return confirm('Vraiment 😥😣?')"
                    class="delete-btn">Déconnexion</a>
            </div>
        </div>

        <div class="produits">

            <h1 class="heading">Dernier produits</h1>

            <div class="box-container">
                <?php
                $select_produits = mysqli_query($conn, "SELECT * FROM produits")
                    or die('connexion échoué');
                if (mysqli_num_rows($select_produits) > 0) {
                    while ($fetch_produits = mysqli_fetch_assoc($select_produits)) {
                        ?>

                        <form action="" class="box" method="post">
                            <img src="images/<?php echo $fetch_produits['img']; ?>" alt="">

                            <div class="nom" style="font-size: 40px; color: black; padding: 5px 0;">
                                <?php echo $fetch_produits['nom']; ?>
                            </div>

                            <div class="prix"
                                style="font-size: 20px; color: black; padding: 5px 10px; position: absolute; top: 10px; left: 1px; border-radius: 5px; background: var(--orange); color: white;">
                                XOF<?php echo $fetch_produits['prix']; ?></div>

                            <input type="number" style="font-size: 20px;" name="produits_quantite" min="1" value="1" id="">

                            <input type="hidden" name="produits_img" value="<?php echo $fetch_produits['img']; ?>">

                            <input type="hidden" name="produits_nom" value="<?php echo $fetch_produits['nom']; ?>">

                            <input type="hidden" name="produits_prix" value="<?php echo $fetch_produits['prix']; ?>">

                            <input type="submit" value="ajouter" name="ajouter_au_produits" class="btn">
                        </form>
                        <?php
                    }
                    ;
                }
                ;
                ?>
            </div>
        </div>

        <div class="cart_marcher">

            <h1 class="heading">Panier</h1>

            <table>
                <thead>
                    <th>image</th>
                    <th>nom</th>
                    <th>prix</th>
                    <th>quantite</th>
                    <th>total</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    $cart_query = mysqli_query($conn, "SELECT * FROM cart ")
                        or die('connexion échoué');
                    if (mysqli_num_rows($cart_query) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                            ?>
                            <tr>
                                <td><img src="images/<?php echo $fetch_cart['img']; ?>" height="100" alt=""></td>

                                <td><?php echo $fetch_cart['nom']; ?></td>
                                <td>XOF<?php echo $fetch_cart['prix']; ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                        <input type="number" min="1" name="cart_quantite"
                                            value="<?php echo $fetch_cart['quantite']; ?>">
                                        <input type="submit" value="Actualiser" name="actualiser_cart" class="option-btn">
                                    </form>
                                </td>
                                <td>XOF<?php echo $sub_total = ($fetch_cart['prix'] * $fetch_cart['quantite']); ?></td>
                                <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn"
                                        onclick="return confirm('êtes vous sur de supprimez?');">Supprimez</a></td>
                                </td>
                            </tr>
                            <?php
                            $grand_total += $sub_total;
                        };
                    }else{
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">Panier vide</td></tr>';
                    };
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4" style="font-size: 30px;">Total :</td>
                        <td style="font-size: 25px;">XOF<?php echo $grand_total; ?></td>
                        <td><a href="index.php?delete_all" onclick="return confirm('êtes vous sûr de tous supprimer?')"
                                class="delete-btn <?php echo ($grand_total > 1)?'': 'disabled'; ?>">Supprimer tous</a></td>
                    </tr>
                </tbody>
            </table>

            <div class="cart-btn">
                <a href="#" class="btn <?php echo ($grand_total > 1)?'': 'disabled'; ?>">Voir</a>
            </div>
        </div>

    </div>
</body>

</html>