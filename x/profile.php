<?php
session_start();
?>


<!DOCTYPE html> <!-- das ist HTML 5 -->
<html lang="de">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <title> Mein Profil </title>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>



</head>



<body>


<header>
    <A ID="toc"></A>

    <nav>
        <div id="erste">

            <ul class="list1">

                <li>
                    <a  class="active" href="index.php">Feed</a>
                </li>

                <li>
                    <a href="profile.php">Profil </a>
                </li>
                <li>
                    <a class="wi" href="messages.html">Messages</a>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn">Benachrichtigungen</a>
                    <div class="dropdown-content">
                        <a href="#">Link 1</a>
                        <a href="#">Link 2</a>
                        <a href="#">Link 3</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

<br><br><br><br>

<a href="logout.php">Log out!</a>








<?php

if(!isset($_SESSION["angemeldet"]))
{
    echo"Bitte zuerst <a href=\"login.html\">einloggen</a>";
    die();
}
else {
    $userid2 = $_SESSION['angemeldet'];
    echo "Hallo User: ".$userid2;
}
?>






<?php
include('DB.php');
include('Post.php');

$username = "";
// je nachdem, auf welcher Profilseite wir sind, heißt die Profilseite z.B. "profile2.php?username=conniealta"
$isFollowing = False; //bedeutet, dass man einem Benutzer noch nicht folgt

if (isset($_GET['username'])) {

    if (DB::query('SELECT username FROM list5 WHERE username=:username', array(':username'=>$_GET['username']))) {
        // Wir definieren zuerst die Variablen (bevor der Button geklickt wird) wie folgt:

        $username = DB::query('SELECT username FROM list5 WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
        //$username = ":username", den wir in die URL angeben, muss dem "username" in der Datenbank entsprechen

        $userid = DB::query('SELECT id FROM list5 WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
        // "$userid" ist die "id" der Person, auf deren Profilseite wir sind (das kann auch unsere Profilseite sein, aber auch die Profilseite von einem anderen Benutzer)

        /* beim "$userid" wird die "id" gespeichert, die dem "username" in der Datenbank gehört, der wiederum dem ":username" in der URL entsprechen muss
          -> anders formuliert: die "id" aus der Datenbank auswählen, wo "username" in der Datenbank dem ":username" in der URL entspricht
           und diese "id" dann bei der Variable "$userid" speichern */

        $followerid = $userid2; //"followerid" ist die "id" des Benutzers, der sich eingeloggt hat
        //'$userid2' ist die "id" der eingeloggten Person (oben definiert: "$userid2 = $_SESSION['angemeldet'];"
        // wenn man auf seiner eigenen Profilseite ist, dann sind die "$userid=1" und die "$followerid=1" gleich
        // wenn man auf der Profilseite eines anderen Benutzers ist, dann ist z.B. die "$userid=3" und die eigene "followerid=1"



        if (isset($_POST['follow'])) {

            if ($userid != $followerid) { //dieser Code wird nur dann ausgeführt, wenn die eingeloggte Person nicht auf ihrer eigenen Profilseite ist
                if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                    /* die id der Person, die sich eingeloggt hat, muss in der Datenbank nicht neben der id der Person stehen, auf deren
                    Profilseite die eingeloggte Person ist;
                    d.h. es wird geprüft, ob neben z.B. user_id=3 (Profilseite anderes Benutzers)-> follower_id=1 steht (eingeloggter Benutzer)
                    wenn es nicht steht, dann darf man der Person folgen */
                    DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
                }
                else {
                    echo 'Already following!';
                }
                /* wenn user_id=3 neben follower_id=1 steht, dann folgt die eingeloggte Person schon der Person, auf deren Profilseite wir sind  */

                $isFollowing = True; //bedeutet, dass man einem Benutzer schon folgt
            }
        }

        if (isset($_POST['unfollow'])) {
            if ($userid != $followerid) {
                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                    DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
                }
                $isFollowing = False;
            }
        }

        if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid ', array(':userid'=>$userid, ':followerid'=>$followerid))) {
            //echo 'Already following!';
            $isFollowing = True;
        } /* Wir schreiben hier (außerhalb der Hauptbedingung) fast den gleichen Code wie oben nochmals, sodass er ausgeführt wird, auch wenn der Follow-Button nicht geklickt wird:
          Siehe die Bedingung oben: "if (isset($_POST['follow'])) ..." */
    }


    if (isset($_POST['post'])) { //prüfen, ob  der Post-Button geklickt wurde und wenn ja:

        Post::createPost($_POST['postbody'], $userid2, $userid);
        /*  in "Post.php" -> '$postbody', 'loggedIn_userid', '$profileUserId'

        --> die "$_POST['postbody'], $userid2, $userid" werden dann an die Parameter in "Post.php" übergeben

        "$userid2 = $_SESSION['angemeldet'];" (oben definiert) -> das ist die "id" der eingeloggten Person
        $userid = die 'id' der Person, auf deren Profilseite die eingeloggte Person ist

       --> durch die Übertragung ($userid2 -> $loggedIn_userid etc.)  darf  die eingeloggte Person nur auf ihrer eigenen Profilseite posten
        */

    }

    if (isset($_GET['postid'])) {
        Post::likePost($_GET['postid'], $followerid);
    }
    /*  in "Post.php" -> '$postid', '$likerId'
     --> die "$_GET['postid'], $followerid" werden dann an die Parameter in "Post.php" übergeben

    $followerid = die eingeloggte Person
    $likerid = die Person, die den Post geliked hat
    --> durch die Übertragung ($followerid -> $likerid)  kann man sehen, ob die eingeloggte Person den Post geliked hat
     */



    $posts = Post::displayPosts($userid, $username, $followerid);
    /*
    $followerid -> $loggedIn_userid etc.  (Übertragung in Post.php)

    die Variable "$posts" ist gleich der "return-Wert" von dieser Methode

    in Post.php -> return $posts;
     "return" --> dies gibt die Variable '$posts = "";' zurück , die all den HTML-Code und alle Posts beinhaltet
    */



} else {
    die('User not found!');
}

?>


<h1><?php echo $username; ?>'s Profile</h1>
<form action="profile.php?username=<?php echo $username; ?>" method="post">

    <?php

    if ($userid != $followerid) { //nur wenn die eingeloggte Person nicht auf ihrer eigenen Profilseite ist, wird der Button angezeigt
        if ($isFollowing) {
            echo '<input type="submit" name="unfollow" value="Unfollow">';
        } else {
            echo '<input type="submit" name="follow" value="Follow">';
        }
    }
    //wenn "$isFollowing = True" wird der Unfollow-Button gezeigt
    //wenn "$isFollowing = False" wird der Follow-Button gezeigt
    ?>

</form>


<form action="profile.php?username=<?php echo $username; ?>" method="post">
    <textarea name="postbody" rows="8" cols="80"></textarea>
    <input type="submit" name="post" value="Post">
</form>


<div class="posts">
    <?php echo $posts; ?>
</div>


</body>

</html>

