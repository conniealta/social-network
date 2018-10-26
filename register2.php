<?php
session_start();
$pdo = new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de; dbname=u-ka034', 'ka034', 'zeeD6athoo', array('charset' => 'utf8'));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrierung</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body
        {
            background: url(as.jpg);
            background-size: cover;
        }

    </style>
</head>
<body>

<?php
$showFormular = true; //Variable ob das Registrierungsformular angezeigt werden soll

if(isset($_GET['register'])) {
    $error = false;
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
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
        $statement = $pdo->prepare("SELECT * FROM list WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();

        if($user !== false) {
            echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
            $error = true;
        }
    }

    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO list (email, passwort) VALUES (:email, :passwort)");
        $result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash));

        if($result) {
            echo 'Du wurdest erfolgreich registriert. <a href="login2.php">Zum Login</a>';
            $showFormular = false;
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    }
}

if($showFormular) {
    ?>
    <div class="loginBox">
        <img src="user.png" class="user">
        <h2>Register</h2>
        <form action="?register=1" method="post">

            <p>Email:</p>
            <input type="text" name="email" placeholder="Enter Email">

            <p>Password:</p>
            <input type="password" name="passwort" placeholder="••••••">

            <p>Confirm password:</p>
            <input type="password" name="passwort2" placeholder="••••••"><br><br>

            <input type="submit" value="Register">
            <br><br><br>
            <a>Already a member?</a>
            <a class="hov1" href="login2.php">Log in now </a></p>
        </form>

    </div>

    <?php
} //Ende von if($showFormular)
?>

</body>
</html>
