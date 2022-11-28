<!DOCTYPE html>
<html>
    <head>
        <title>Panier</title>
        <link rel="stylesheet" href="./bootstrap-5.1.3-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/main.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <?php
            session_start();
            if (!isset($_SESSION["profil"])) $_SESSION["profil"] = 'visit';

            include 'menu_top.html';
        ?>

        <div class="row justify-content-center">
            <div class="col-md-9">
                <?php 
                // check du mode de connexion et restreindre l'accès aux visiteurs 
                    if ($_SESSION["profil"] == 'visit') {
                        echo "
                            <div class='alert alert-danger text-center' role='alert'>
                            Vous devez être connecté pour avoir accès à votre panier!
                            </div>
                            ";
                    } else {
                        require_once('connexion.php');
                        require_once("fonctions.php");

                        
                        // ajouter un livre au panier
                        if (isset($_POST["borrow"])) {
                            $total = countBorrowedBook($_SESSION["mail"]) + count($_SESSION["panier"]);
                            $nolivre =  $_POST["nolivre"];
                            if ($total >= 5) {
                                echo "
                                <div class='alert alert-danger text-center' role='alert'>
                                    <strong>Vous ne pouvez pas emprunter plus de 5 livres à la fois!</strong>
                                </div>
                                ";
                            } else {
                                if (isAvailable($nolivre))
                                    if (!in_array($nolivre, $_SESSION["panier"])) 
                                        $_SESSION["panier"][$nolivre] = $nolivre;
                            }
                        }

                        // retirer le livre du panier
                        if (isset($_POST["cancel"])) {
                            $nolivre =  $_POST["nolivre"];
                            if (in_array($nolivre, $_SESSION["panier"])) unset($_SESSION["panier"][$nolivre]);
                        }

                        // valider le panier 
                        if (isset($_POST["valider"])) {
                            foreach ($_SESSION["panier"] as $item) {
                                borrow($item, $_SESSION["mail"]);
                                unset($_SESSION["panier"][$item]);
                            }
                            echo "
                            <div class='alert alert-success text-center' role='alert'>
                                <strong>Votre panier a été validé!</strong>
                            </div>";
                        }

                        // affiche les places disponibles dans le panier 
                        $places = 5 - (countBorrowedBook($_SESSION["mail"]) + count($_SESSION["panier"]));
                        echo countBorrowedBook($_SESSION["mail"])." empruns en cours. ".count($_SESSION["panier"])." dans le panier. ".$places." places disponibles<br/>";
                        
                        // recherche des livres qui sont présent dans le panier
                        foreach ($_SESSION["panier"] as $nolivre) {
                            $stmt = $connexion->prepare("select * from auteur a, livre l where l.noauteur = a.noauteur and nolivre = :nolivre");
                            $stmt->bindValue(':nolivre', $nolivre);
                            $stmt->setFetchMode(PDO::FETCH_OBJ);
                            $stmt->execute();
                            if ($enr = $stmt->fetch()) {    
                                $name = $enr->nom;
                                $firstname = $enr->prenom;
                                $title = $enr->titre;
                                $pyear = $enr->anneeparution;
                                // afficahge du livre avec le bouton pour le retirer
                                echo "<p> </p>
                                <form class='form-inline' method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                                    <input type='hidden' name='nolivre' value='$nolivre'>
                                    <div class='form-group mb-2'>
                                        <input type='text' class='form-control-plaintext' value=\"$name $firstname - $title ($pyear)\">
                                    </div>
                                    <button type='submit' class='btn btn-danger mb-2' name='cancel'>Retirer</button>
                                </form><br/>";
                            }
                        }

                        if (count($_SESSION["panier"]) > 0) {
                            echo "<form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                                <input class='btn btn-success' type='submit' value='Valider le panier' name='valider'></form><br/>";
                        } else {
                            echo "Votre panier est vide.";
                        }
                    }
                ?>
            </div>
            <div class="col-md-2 bg-warning h-100">
                <?php include "authentification.php";?>
            </div>
        </div>
    </body>
</html>