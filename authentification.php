<?php
echo "<div class='row justify-content-center'>";
if (isset($_POST["btnLog"])) {
    require_once('connexion.php');
    $mail = $_POST['inputMail'];
    $pwd = $_POST['inputPwd'];
    // regarde si le mail existe dans la base de donnée
    $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE mel=:mail");
    $stmt->bindValue(":mail", $mail);
    $stmt->setFetchMode(PDO::FETCH_OBJ);
    $stmt->execute();
    if ($enr = $stmt->fetch()) {
        // compare le mdp entré avec celui de la base de donnée
    if (password_verify($pwd, $enr->motdepasse)) {
        $_SESSION["name"] = $enr->nom;
        $_SESSION["firstname"] = $enr->prenom;
        $_SESSION["mail"] = $enr->mel;
        $_SESSION["profil"] = $enr->profil;
        $_SESSION["adresse"] = $enr->adresse;
        $_SESSION["ville"] = $enr->ville;
        $_SESSION["cp"] = $enr->codepostal;
        $_SESSION["panier"] = array();
        echo '
        <script language="Javascript">
            document.location.replace("'.htmlspecialchars($_SERVER["PHP_SELF"]).'");
        </script>';
    } else {
    echo '<br/><div class="row justify-content-center"><div class="col-md-10"><div class="alert alert-danger" role="alert">
        Mauvais mot de passe.
        </div></div></div>';
    }
    } 
    else {
    echo '<br/><div class="row justify-content-center"><div class="col-md-10"><div class="alert alert-danger" role="alert">
        Mauvais identifiant.
        </div></div></div>';
    }
}
if (isset($_POST["btnLogout"])) {
    session_unset();
    $_SESSION["profil"] = "visit";
    echo '
    <script language="Javascript">
        document.location.replace("'.htmlspecialchars($_SERVER["PHP_SELF"]).'");
    </script>';
}
// affichage en fonction du mode de connexion
switch ($_SESSION["profil"]) {
    default: 
        echo "
        <form action=".htmlspecialchars($_SERVER["PHP_SELF"])." method = 'post'><br/>
            <label for='mail' class='d-flex justify-content-center'>Mail</label>
            <input name='inputMail' pattern='[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$' type='text' class='form-control' id='mail' placeholder='exemple@mail.fr' required><br/>
            <label for='pwd' class='d-flex justify-content-center'>Mot de passe</label>
            <input name='inputPwd' type='password' class='form-control' id='pwd' placeholder='azerty1234' required><br/>
            <div class='d-flex justify-content-center'>
                <button name='btnLog' type='submit' class='btn btn-primary'>Se connecter</button>
            </div>
        </form>";
        break;
    case "user":
        echo "
        <form action=".htmlspecialchars($_SERVER["PHP_SELF"])." method = 'post'><br/>
            <div class='border border-dark border-3 rounded-3'><br/>
                <label class='d-flex justify-content-center'>".$_SESSION['name']." ".$_SESSION['firstname']."</label>
                <label class='d-flex justify-content-center'>".$_SESSION['mail']."</label><br/>
                <label  class='d-flex justify-content-center'>".$_SESSION['adresse'].",</label>
                <label class='d-flex justify-content-center'>".$_SESSION['cp']." ".$_SESSION['ville']."</label><br/>
            </div><br/>
            <div class='form-group d-flex justify-content-center'>
                <button name='btnLogout' type='submit' class='btn btn-danger'>Se déconnecter</button>
            </div>
        </form>";
        break;
    case "admin":
        echo "
        <form action=".htmlspecialchars($_SERVER["PHP_SELF"])." method = 'post'><br/>
                <div class='alert alert-warning text-center' role='alert'>
                    <strong>ADMINISTRATEUR</strong>
                </div><br/>
            <div class='border border-dark border-3 rounded-3'><br/>
                    <label class='d-flex justify-content-center'>".$_SESSION['name']." ".$_SESSION['firstname']."</label>
                    <label class='d-flex justify-content-center'>".$_SESSION['mail']."</label><br/>
            </div><br/>
            <div class='d-flex justify-content-center'>
                <button name='btnLogout' type='submit' class='btn btn-danger'>Se déconnecter</button>
            </div>
        </form>
        <from><br/>
                <label class='d-flex justify-content-center'>Panel Admin</label>
            <div class='form-group d-flex justify-content-center'>
                <a href='ajouter_membre.php'><button name='btnAddMember' class='btn btn-success'>Membres</button></a>
                <a href='ajouter_livre.php'><button name='btnAddBook' class='btn btn-success'>Livres</button></a>
            </div>
        </form>";
        break;
}
echo  "</div><br/>
        <div class='row'>
        <div class='col-md-1'></div>
        <div class='col-md-10'>
        <div class='col-md-1'></div>
        </div>";
?>