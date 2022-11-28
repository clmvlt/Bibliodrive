<!DOCTYPE html>
<html>
    <head>
        <title>BibDioDrive</title>
        <link rel="stylesheet" href="./bootstrap-5.1.3-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/css.css">
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
                <div class="d-flex justify-content-center">
                    <!--==========================================-->
                    <!-- Carousel                                 -->
                    <!--==========================================-->
                    <div id="nouveautes" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#nouveautes" data-slide-to="0" class="active"></li>
                            <li data-target="#nouveautes" data-slide-to="1"></li>
                            <li data-target="#nouveautes" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <?php
                                require_once('connexion.php');
                                // récupérer les 3 derniers livres ajoutés
                                $stmt = $connexion->prepare("select * from livre order by dateajout desc limit 3");

                                $stmt->setFetchMode(PDO::FETCH_OBJ);
                                $stmt->execute();
                                $enr = $stmt->fetch();
                                // ajout des image dans le carousel
                                echo "
                                    <div class='carousel-item active w-100'>
                                        <a href='livre.php/?nolivre=$enr->nolivre'><img class='d-block w-100' src='$enr->image' alt='$enr->titre.png'><a>
                                    </div>";

                                while ($enr = $stmt->fetch()) {
                                    echo "
                                    <div class='carousel-item w-100'>
                                        <a href='livre.php/?nolivre=$enr->nolivre'><img class='d-block w-100' src='$enr->image' alt='$enr->titre.png'><a>
                                    </div>";
                                }
                            ?>
                        </div>
                        <a class="carousel-control-prev" href="#nouveautes" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#nouveautes" role="button" data-slide="next">
                            <span class="carousel-control-next-icon bg-dark" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </div>
            <!--==========================================-->
            <!-- Formulaire de login                      -->
            <!--==========================================-->
            <div class="col-md-2 bg-warning h-100">
                <?php include "authentification.php";?>
            </div>
        </div>
    </body>
</html>