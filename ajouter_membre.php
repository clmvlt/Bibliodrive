<!DOCTYPE html>
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
            // detect le mode de connexion de l'utilisateur et le bloque s'il n'est pas administrateur 
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
                    <!-- formulaire d'ajout de membre -->
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = 'post'><br/>
                    <label for='mail' class='d-flex justify-content-center'>Mail</label>
                    <input name='inputMail' type='mail' pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class='form-control' id='mail' placeholder='exemple@mail.fr' required>
                    <label for='pwd' class='d-flex justify-content-center'>Mot de passe</label>
                    <input name='inputPassword' type='password' class='form-control' id='pwd' placeholder='azerty1234' size="100" required>
                    <label for='name' class='d-flex justify-content-center'>Nom</label>
                    <input name='inputName' pattern="[^0-9]*" type='text' class='form-control' id='name' placeholder='Jean-Jack' required>
                    <label for='firstname' class='d-flex justify-content-center'>Prénom</label>
                    <input name='inputFirstname' pattern="[^0-9]*" type='text' class='form-control' id='firstname' placeholder='Albert' required>
                    <label for='ad' class='d-flex justify-content-center'>Adresse</label>
                    <input name='inputAddress' type='text' class='form-control justify-content-center' id='ad' placeholder='20 rue Rabelais' required>
                    <label for='city' class='d-flex justify-content-center'>Ville</label>
                    <input name='inputCity' pattern="[^0-9]*" type='text' class='form-control' id='city' placeholder='Saint Brieuc' required>
                    <label for='cp' class='d-flex justify-content-center'>Code Postal</label>
                    <input name='inputPC' type='number' class='form-control' id='cp' placeholder='22000' required>
                    <div class='form-group d-flex justify-content-center'>
                        <button name='btnAddMember' type='submit' class='btn btn-primary'>Ajouter</button>
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
              if (isset($_POST["btnAddMember"])) {
                // en cas d'appuie sur le bouton ajouter membre 
                require_once('connexion.php');
                require_once('fonctions.php');
                $mail = $_POST['inputMail'];
                $pwd = $_POST['inputPassword'];
                $name = $_POST['inputName'];
                $firstname = $_POST['inputFirstname'];
                $adress = $_POST['inputAddress'];
                $city = $_POST['inputCity'];
                $pc = $_POST['inputPC'];

                $cryptPwd = password_hash($pwd, PASSWORD_ARGON2I);

                $profil = "user";

                if (mailExist($mail)) {
                    echo '
                    <div class="row justify-content-center"><div class="col-md-10"><div class="alert alert-danger" role="alert">
                      Mail déjà existant.
                    </div></div></div>';
                    return;
                }
                // insertion dans la base de donnée 
                $stmt = $connexion->prepare("insert into utilisateur values (:mail, :pwd, :n, :fn, :ad, :city, :pc, :profil)");

                $stmt->bindValue(":mail", $mail);
                $stmt->bindValue(":pwd", $cryptPwd);
                $stmt->bindValue(":n", $name);
                $stmt->bindValue(":fn", $firstname);
                $stmt->bindValue(":ad", $adress);
                $stmt->bindValue(":city", $city);
                $stmt->bindValue(":pc", $pc, PDO::PARAM_INT);
                $stmt->bindValue(":profil", $profil);
                $stmt->setFetchMode(PDO::FETCH_OBJ);
                $stmt->execute();

                $nbline = $stmt->rowCount();
                // detect les erreurs lors de l'ajout du membre et affiche un message en conséquences 
                if ($nbline > 0) {
                  echo '<br/><div class="row justify-content-center"><div class="col-md-10"><div class="alert alert-success" role="alert">
                    L\'utilisateur a été ajouté.
                  </div></div></div>';
                } 
                else {
                  echo '<br/><div class="row justify-content-center"><div class="col-md-10"><div class="alert alert-danger" role="alert">
                    Erreur d\'ajout de l\'utilisateur.
                  </div></div></div>';
                }
              }
            ?>
    </body>
</html>