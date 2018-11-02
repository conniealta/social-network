<?php
session_start();
if(isset($_POST["email"]) AND isset($_POST["passwort"]))
{
    $email=$_POST["email"];
    $passwort=$_POST["passwort"];
}
else
{
    echo"Keine Daten";
    die();
}

$pdo=new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de;dbname=u-ka034', 'ka034', 'zeeD6athoo',array('charset'=>'utf8'));

$passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

$statement = $pdo->prepare("SELECT * FROM list5 WHERE email=:email AND passwort=:passwort");

if($statement->execute(array('email'=>$email, 'passwort' => $passwort_hash))) {
    if($user=$statement->fetch()) {
        if ($user !== false && password_verify($passwort, $user['passwort'])) {
            //echo "angemeldet";
            $_SESSION["angemeldet"] = $user["id"];
            header('Location: index.php');
        } else {
            echo "nicht berechtigt";
        }
    }
}
else {
    echo "Datenbank-Fehler:";
    echo $statement->errorInfo()[2];
    echo $statement->queryString;
    die();
}


//"passwort" = das ist die Variable bei der Datenbak
//":passwort" = das ist der Parameter, den wir im Formular eingegeben haben