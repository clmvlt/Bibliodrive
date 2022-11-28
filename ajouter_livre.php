
<<!DOCTYPE html>
<html>
    <head>
        <title>BibDioDrive</title>
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
            // detect le mode de connexion de l'utilisateur 
            if ($_SESSION["profil"] != "admin") {
              echo '<br/><div class="row justify-content-center"><div class="col-md-10"><div class="alert alert-danger" role="alert">
                Vous n\'avez pas les permission d\'accès à cette page.
              </div></div></div>';
              return;
            } 
        ?>

        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="d-flex justify-content-center">
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = 'post'><br/>
                    <label for='author' class='d-flex justify-content-center'>Auteur</label>
                    <select name="selectAuthor" class="form-control">
                      <option value="">-- Auteurs --</option>
                      <?php 
                        require_once('connexion.php');
                        // récupérer tous les auteurs de la base de donnée 
                        $select = $connexion->prepare("select * from auteur");
                        $select->setFetchMode(PDO::FETCH_OBJ);
                        $select->execute();
                        while ($enr = $select->fetch()) {
                          $authorno = $enr->noauteur;
                          $name = $enr->nom;
                          $firstname = $enr->prenom;
                          echo "<option value=\"$authorno\">$name $firstname</option>";
                        }
                      ?>
                    </select><br/>
                        <!-- Formulaire d'ajout de livres -->
                    <label for='title' class='d-flex justify-content-center'>Titre du livre</label>
                    <input name='inputTitle' type='text' class='form-control' id='title' placeholder='Marine fait du dessin' size="100" required>
                    <label for='isbn' class='d-flex justify-content-center'>ISBN 13</label>
                    <input name='inputISBN' type='number' class='form-control' id='isbn' placeholder='123456789' required>
                    <label for='parution' class='d-flex justify-content-center'>Année de parution</label>
                    <input name='inputPYear' type='number' class='form-control' id='parution' placeholder='1856' required>
                    <label for='resume' class='d-flex justify-content-center'>Résumé</label>
                    <textarea name='inputSummary' type='text' class='form-control justify-content-center' id='resume' placeholder="C'est l'histoire de... " required></textarea>
                    <label for='img' class='d-flex justify-content-center'>Image (lien)</label>
                    <input name='inputImageLink' type='text' class='form-control' id='img' placeholder='www.google.fr/logo.png' required>
                    <div class='form-group d-flex justify-content-center'>
                        <button name='btnAddBook' type='submit' class='btn btn-primary'>Ajouter</button>
                    </div>
                  </form>

                </div>
              </div>
            <div class="col-md-2 bg-warning h-100">
                <?php include "authentification.php";?>
            </div>
          </div>
        </div>
        <?php
          if (isset($_POST["btnAddBook"])) {
            // en cas d'appuie sur le bouton pour ajouter livre 
            require_once('connexion.php');
            $author = $_POST['selectAuthor'];
            $title = $_POST['inputTitle'];
            $isbn = $_POST['inputISBN'];
            $pyear = $_POST['inputPYear'];
            $summary = $_POST['inputSummary'];
            $image = $_POST['inputImageLink'];

            $m = date("m");
            $d = date("d");
            $y = date("y");
            $date = "$y-$m-$d";
            // insertion du livre dans la base de donnée 
            $stmt = $connexion->prepare("insert into livre (noauteur, titre, isbn13, anneeparution, resume, dateajout, image) values (
              :authorno, :title, :isbn, :pyear, :summary, :addedDate, :image)");

            $stmt->bindValue(":authorno", $author, PDO::PARAM_INT);
            $stmt->bindValue(":title", $title);
            $stmt->bindValue(":isbn", $isbn, PDO::PARAM_INT);
            $stmt->bindValue(":pyear", $pyear, PDO::PARAM_INT);
            $stmt->bindValue(":summary", $summary);
            $stmt->bindValue(":addedDate", $date);
            $stmt->bindValue(":image", $image);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();

            $nbline = $stmt->rowCount();
            // detect les erreurs lors de l'ajout et affiche un message en conséquence 
            if ($nbline > 0) {
              echo '<br/><div class="row justify-content-center"><div class="col-md-10"><div class="alert alert-success" role="alert">
                Le livre a été ajouté.
              </div></div></div>';
            } 
            else {
              echo '<br/><div class="row justify-content-center"><div class="col-md-10"><div class="alert alert-danger" role="alert">
                Erreur d\'ajout du livre.
              </div></div></div>';
            }
          }
      ?>
    </body>
</html>



