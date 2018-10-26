<?php
include('include/DB.php');
//establish connection to the database

if (isset($_POST['register']))  {
    //if the form is submitted:
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {
//!DB = we have to negate this so that we are checking if user/email doesn't exist we want to add him to our database

        if (strlen($passwort) >= 3 && strlen($passwort)<=60) {

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                if($passwort = $passwort2) {

                         DB::query('INSERT INTO users VALUES (\'\', :email, :passwort)', array(':email'=>$email, ':password'=>password_hash($password, PASSWORD_BCRYPT)));
                        //this way we insert the data into the database
                        // "\'\'" = this is an empty "id"-field;  ':email' =  the email  we submit in the form -> so it doesn't match the table column of our database
                         echo "Du wurdest erfolgreich registriert!";
                }
                else {
                    echo "Die Passwörter müssen übereinstimmen";
                }
            }
            else {
                  echo 'Bitte eine gültige E-Mail-Adresse angeben';
            }
        }
        else {
               echo "Bitte ein gültiges Passwort angeben";

        }
    }
    else {
        echo "Es ist bereits ein Benutzer mit diese E-Mail registriert!";
    }

}


?>

<h1>Registrieren</h1>
<form action="register.php" method="post">
    <input type="email" name="email" value="" placeholder="E-Mail...">
    <input type="password" name="passwort" value="" placeholder="Passwort...">
    <input type="submit" name=""

    <p>Confirm password:</p>
    <input type="password" name="passwort2" placeholder="••••••"><br><br>

    <input type="submit" name="register" value="Registrieren">
    <br><br><br>
    <a>Already a member?</a>
    <a class="hov1" href="login2.php">Log in now </a>
</form>
