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


<div id="zweite">
    <a href="logout.php">Log out!</a>
</div>


    <?php

    if(!isset($_SESSION["angemeldet"]))
    {
        echo"Bitte zuerst <a href=\"login.html\">einloggen</a>";
        die();
    }
    else {
        $user = $_SESSION['angemeldet'];
        echo "Hallo User: ".$user;
    }
    ?>

                    <h1> <?php echo $user; ?>'s Profil</h1>



                    <?php
                    session_start();
                    if(isset($_POST["username"]) )
                    {
                        }
                    else
                    {
                        echo"Keine Daten";
                        die();
                    }


                    $user = "";
                    $isFollowing = False;


                    $pdo=new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de;dbname=u-ka034', 'ka034', 'zeeD6athoo',array('charset'=>'utf8'));

                    if (   $statement = $pdo->prepare("SELECT username FROM list5 WHERE username=:username")){

                                if($statement->execute(array(':username'=>$user))) {
                                if($user=$statement->fetch()) {
                                    $_SESSION["angemeldet"] = $user["username"];}

                                    $user = $_SESSION['angemeldet'];

                                    $userid = $pdo->prepare("SELECT id FROM list5 WHERE username=:username"); array(':username' => $_GET['username']);
                                    $followerid =$userid;


                                }else{
                                echo 'Benutzer konnte nicht gefunden werden!';
                                }


                    if ($userid != $followerid) {
                        if ($isFollowing) {
                            echo '<input type="submit" name="unfollow" value="Unfollow">';
                        } else {
                            echo '<input type="submit" name="follow" value="Follow">';
                        }
                    }
                    ?>


<h1 class="title"> Meine Posts  </h1>
<div id="dritte">
    <form action="do_post_profile.php" method="post">
        <textarea name="content" rows="17" cols="70"> </textarea>
        <input type="submit" value="Post" />
    </form>
</div>


        <?php

        $content = $_POST["content"];
        echo $content;

        $pdo = new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de; dbname=u-ka034', 'ka034', 'zeeD6athoo', array('charset' => 'utf8'));

        $statement = $pdo->prepare("SELECT * FROM blog ");

        if($statement->execute(array(':id'=>$user, ':content'=>$content))) {
            while($row=$statement->fetch()) {

                echo $row['id']." ".$row['content'];
                echo "<br>";

                echo "<tr>";
                echo "<td>$row->id </td>";
                echo "<td>$row->content</td>";
                echo "</tr>";
                echo "<br>";

            }
        } else {
            echo "Datenbank-Fehler:";
            echo $statement->errorInfo()[2];
            echo $statement->queryString;
            die();

        }

        ?>

</body>
</html>
