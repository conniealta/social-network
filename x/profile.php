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

        $postbody = $_POST['postbody'];
        $loggedIn_userid = $userid2;  // oben definiert: "$userid2 = $_SESSION['angemeldet'];" das ist die "id" der eingeloggten Person

        if (strlen($postbody) > 1000 || strlen($postbody) < 1) {
            die('Inkorrekte Länge!');
        }
        if ($loggedIn_userid == $userid) { //wenn die eingeloggte Person auf ihrer eigenen Profilseite ist, dann darf sie Einträge posten
            DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0)', array(':postbody'=>$postbody, ':userid'=>$userid));
        }
        // -> '\'= die erste Spalte in der Datenbanktabelle ("id"); NOW() = das ist eine Funktion, die das aktuelle Datum und Uhrzeit anzeigt; '0'= die Standardanzahl der "Likes"
        else {
            die('Falscher Benutzer!');
        }

    }

    if (isset($_GET['postid'])) {
        if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid))) {
            //wenn das nicht der Fall ist: der Benutzer hat den Post bereits geliked, dann wird der Code ausgeführt:
            DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid' => $_GET['postid']));
            //wo die Post-"id" in der Datenbank gleich die ":postid" ist, die dem URL übergeben wird, wenn man auf den Like-Button klickt
            DB::query('INSERT INTO post_likes VALUES (\'\',:postid, :userid)', array(':postid' => $_GET['postid'], ':userid' => $followerid));
            //das zeigt welcher Benutzer den Post geliked hat
        }
        else {
            echo 'Already liked';
        }
    }

    $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
    $posts = "";
    foreach($dbposts as $p) {
        $posts .= htmlspecialchars($p['body'])."
          <form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
             <input type='submit' name='like' value='Like'>
          </form>
       <hr /></br />";
    }
    /* $p = ein Array mit den Datenbankeinträgen: z.B. --> Array ([id]=>1 [0]=>1 [body]=>Hello [1]=>Hello [posted_at]=>2018-11-08 17:37:23 ...)
    $p[body] = unser Post wird bei "body" in der Datenbanktabelle gespeichert --> mit dieser Funktion sehen wir nur den Inhalt des Posts (nicht id, Datum, etc.)
    $posts = "" -> zunächst leer Array
    "<hr />" = horizontale Linie
    ".=" (->  $txt1 = "Hello"; $txt2 = " world!"; $txt1 .= $txt2; --> Hello world)
    htmlspecialchars = wandelt Sonderzeichen in HTML-Codes um
    postid = das ist die "id" des jeweiligen Posteintrag
    if (isset($_GET['postid']) -> prüfen, ob der Like-Button geklickt wurde, wenn ja
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

