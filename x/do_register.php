
<?php
session_start();

$pdo=new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de;dbname=u-ka034', 'ka034', 'zeeD6athoo',array('charset'=>'utf8'));

if(isset($_POST["email"]) AND isset($_POST["passwort"]) AND isset($_POST["username"]))
{
    $username=$_POST["username"];
    $email=$_POST["email"];
    $passwort=$_POST["passwort"];
}
else
{
    echo"Keine Daten";
    die();
}


if(isset($_POST["email"]) AND isset($_POST["passwort"])AND isset($_POST["username"])) {
    $error = false;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
        $error = true;
    }
    if(strlen($username) == 0) {
        echo 'Bitte einen Username angeben<br>';
        $error = true;
    }
    if(strlen($passwort) == 0) {
        echo 'Bitte ein Passwort angeben<br>';
        $error = true;
    }
    if($passwort != $passwort2) {
        echo 'Die Passwörter müssen übereinstimmen<br>';
        $error = true;
    }

    //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
    if(!$error) {
        $statement = $pdo->prepare("SELECT * FROM list5 WHERE email = :email");
        $result = $statement->execute(array(':email' => $email));
        $user = $statement->fetch();

        if($user !== false) {
            echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
            $error = true;
        }
    }

    //Überprüfe, dass der Username noch nicht registriert wurde
    if(!$error) {
        $statement = $pdo->prepare("SELECT * FROM list5 WHERE username = :username");
        $result = $statement->execute(array(':username' => $username));
        $user = $statement->fetch();

        if($user !== false) {
            echo 'Dieser Username ist bereits vergeben<br>';
            $error = true;
        }
    }

    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {
        echo $passwort;
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO list5 (username, email, passwort) VALUES (:username, :email, :passwort)");
        echo $passwort_hash;
        $result = $statement->execute(array(':username' => $username, ':email' => $email, ':passwort'=> hash('sha256', $passwort, false)));

        if($result) {
            echo 'Du wurdest erfolgreich registriert. <a href="login2.html">Zum Login</a>';
        }
        else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
            echo "Datenbank-Fehler:";
            echo $statement->errorInfo()[2];
            echo $statement->queryString;
            die();
        }
    }
}



/*
$statement = $pdo->prepare("SELECT * FROM list WHERE email=:email AND passwort=:passwort");

//"passwort" = das ist die Variable bei der Datenbak
//":passwort" = das ist der Parameter, den wir im Formular eingegeben haben


if($statement->execute(array(':email'=>$email, ':passwort'=>$passwort))) {
    if($row=$statement->fetch()) {
        //echo "angemeldet";
        $_SESSION["angemeldet"]=$row["id"];
        header('Location: index.php');
    }
    else
    {
        echo"nicht berechtigt";
    }
} else {
    echo "Datenbank-Fehler:";
    echo $statement->errorInfo()[2];
    echo $statement->queryString;
    die();
}


$statement = $pdo->prepare("INSERT INTO posts (content) VALUES (?)");
$statement->execute(array($content));

echo $content." "."mit id in der Datenbank: ".$id=$pdo->lastInsertId();*/

