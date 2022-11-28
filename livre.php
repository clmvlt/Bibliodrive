<!DOCTYPE html>
<html>
    <head>
        <title>Info Livre</title>
        <link rel="stylesheet" href="./../bootstrap-5.1.3-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="./../css/main.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            session_start();
            if (!isset($_SESSION["profil"])) $_SESSION["profil"] = 'visit';

            include 'menu_top.html';
        ?>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <?php
                    if (!isset($_GET["nolivre"])) header('Location: ./../liste_livres.php');
                    require_once('connexion.php');
                    require_once('fonctions.php');
                    $nolivre = $_GET["nolivre"];

                    // recherche du livre selon le numéro 
                    $stmt = $connexion->prepare("select * from livre l, auteur a where l.nolivre = :nolivre and l.noauteur = a.noauteur");
                    $stmt->bindValue(":nolivre", $nolivre, PDO::PARAM_INT);

                    $stmt->setFetchMode(PDO::FETCH_OBJ);
                    $stmt->execute();

                    if ($enr = $stmt->fetch()) {
                        // affichage du livre
                        echo "<br/>
                        <div class='row'>
                            <div class='col-md-8'>
                                <h2>Auteur : $enr->nom $enr->prenom</h2>
                                <h3>ISBN13 : $enr->isbn13</h2>
                            </div>
                            <div class='col-md-4'>
                                <h2>$enr->nom $enr->prenom</h2>
                                <h3>$enr->titre</h2>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-8'>
                                <h4>Résumé du livre</h2>
                                <p>$enr->resume</p>
                            </div>
                            <div class='col-md-4'>
                                <img class='img-fluid' src='$enr->image' alt='$enr->titre.png'><br/>";
                                // check du mode de connexion
                        switch ($_SESSION["profil"]) {
                            case "visit":
                                echo isAvailable($nolivre) ?
                                "<h5 class='text-success'>Disponible</h5><p>Vous devez être connecté pour pouvoir emprunter ce livre.</p>" 
                                : 
                                "<h5 class='text-danger'>Indisponible</h5>";
                                break;
                            case "admin":
                            case "user":
                                if (in_array($nolivre, $_SESSION["panier"])) {
                                    echo "
                                    <form method='post' action='./../panier.php'>
                                        <input type='hidden' name='nolivre' value='$nolivre'>
                                        <input class='btn btn-warning' type='submit' value='Retirer du panier' name='cancel'>
                                    </form>";
                                } else if (isAvailable($nolivre)) {
                                    echo "<h5 class='text-success'>Disponible</h5>";
                                    if ($nolivre == 6) {
                                        echo "<form action='https://www.dailymotion.com/video/x5ykzv' method='post'>
                                        <input class='btn btn-dark' type='submit' value='Ajouter au panier'></input>
                                        </form>";
                                    } else {
                                        echo "<form action='./../panier.php' method='post'>
                                        <input type='hidden' name='nolivre' value='$nolivre'>
                                        <input class='btn btn-dark' name='borrow' type='submit' value='Ajouter au panier'></input>
                                        </form>";
                                    }
                                } else {
                                    echo "<h5 class='text-danger'>Indisponible</h5>";
                                }
                                break;
                        }
                    } else {
                        // si le livre n'est pas trouvé, renvoie à la page de la liste
                        header('Location: ./../liste_livres.php');
                    }
                ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2 bg-warning h-100">
                <?php include "authentification.php";?>
            </div>
        </div>
    </body>
</html>