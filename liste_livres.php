<!DOCTYPE html>
<html>
    <head>
        <title>BibDioThèque</title>
        <link rel="stylesheet" href="./bootstrap-5.1.3-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/main.css">
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
            <br/>
            <ul class="">
                <?php
                    require_once('connexion.php');
                    // ajouter % à l'avant et à l'arrière pour transformer en expression régulière la recherche demandé
                    if (!isset($_GET["inputAuthor"])) {
                        $author = "%";
                    } 
                    else {
                        $author = "%".$_GET["inputAuthor"]."%";
                        if (strlen($_GET["inputAuthor"] > 0)) {
                            echo 'Résultats pour '.$_GET["inputAuthor"].'<br/>';
                        }
                    }
                    // rechecher des livres selon le nom de l'auteur
                    $stmt = $connexion->prepare("select l.nolivre, l.titre, l.anneeparution from auteur a, livre l where a.nom like :author and l.noauteur = a.noauteur");
                    $stmt->bindValue(":author", $author);

                    $stmt->setFetchMode(PDO::FETCH_OBJ);
                    $stmt->execute();

                    $nb = $stmt->rowCount();

                    if ($nb > 0) {
                        while ($enr = $stmt->fetch()) {
                            echo "<br/>
                            <a class='link-primary' href='livre.php/?nolivre=$enr->nolivre'>
                                <li class=''>". $enr->titre. " (". $enr->anneeparution. ")</li>
                            </a>";
                        } 
                    } else {
                        echo "<br/>Aucun livres trouvés";
                    }
                ?>
                </ul>
            </div>
            <div class="col-md-2 bg-warning h-100">
                <?php include "authentification.php";?>
            </div>
        </div>
    </body>
</html>