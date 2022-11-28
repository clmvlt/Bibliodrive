<?php
// check si le mail est présent dans la base de donnée
function mailExist($mail) {
    require('./connexion.php');
    $stmt = $connexion->prepare("select * from utilisateur where mel=:mail");
    $stmt->bindValue(':mail', $mail);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_OBJ);

    if ($enr = $stmt->fetch()) {
        return true;
    } else {
        return false;
    }
}
// check si le livre peut être emprunter
function isAvailable($nolivre) {
    require('./connexion.php');
    $stmt = $connexion->prepare("select * from emprunter where nolivre=:nolivre and dateretour is null");
    $stmt->bindValue(':nolivre', $nolivre);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_OBJ);

    if ($enr = $stmt->fetch()) {
        return false;
    } else {
        return true;
    }
}
// ajouter un livre à la base emprunter
function borrow($nolivre, $mail) {
    if (!isAvailable($nolivre)) return false;
    require('./connexion.php');

    $m = date("m");
    $d = date("d");
    $y = date("y");
    $date = "$y-$m-$d";

    $stmt = $connexion->prepare("insert into emprunter (mel, nolivre, dateemprunt) values (:mail, :nolivre, :dateemprunt)");
    $stmt->bindValue(':nolivre', $nolivre);
    $stmt->bindValue(':mail', $mail);
    $stmt->bindValue(':dateemprunt', $date);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_OBJ);

    if ($enr = $stmt->fetch()) {
        return false;
    } else {
        return true;
    }
}
// compter les nombre del ivres empruntés par l'utilisateur
function countBorrowedBook($mail) {
    require('./connexion.php');
    $stmt = $connexion->prepare("select count(*) as total from emprunter where mel=:mail");
    $stmt->bindValue(':mail', $mail);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_OBJ);
    return $stmt->fetch()->total;
}
?>