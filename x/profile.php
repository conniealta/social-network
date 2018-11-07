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

<h1> Das Profil von '<?php echo $user; ?>'</h1>


<h1 class="title"> Meine Posts  </h1>
<div id="dritte">
    <form action="do_post.php" method="post">
        <textarea name="content" rows="17" cols="70"> </textarea>
        <input type="submit" value="Post" />
    </form>
</div>




<?php
include('DB.php');
$user = $_SESSION ['angemeldet'];


$username = "";
$isFollowing = False;
if (isset($_GET['username'])) {
if (DB::query('SELECT username FROM list5 WHERE username=:username', array(':username'=>$_GET['username']))) {

$username = $pdo->prepare('SELECT username FROM list5 WHERE username=:username', array(':username' => $_GET['username']))[0]['username'];
$userid = $pdo->prepare('SELECT id FROM list5 WHERE username=:username', array(':username' => $_GET['username']))[0]['id'];


if (isset($_POST['follow'])) {

    if ($followerid = $user) {

        if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid' => $userid))) {
            DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid' => $userid, ':followerid' => $followerid));
        } else {
            echo 'Already following!';
        }
        $isFollowing = True;
    }
}

 if (isset($_POST['unfollow'])) {

                if ($followerid = $user) {

                    if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid' => $userid))) {
                        DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid' => $userid, ':followerid' => $followerid));
                    }
                    $isFollowing = False;
                }
            }
            if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid' => $userid))) {
                //echo 'Already following!';
                $isFollowing = True;
            }


?>


<form action="profile.php?username=<?php echo $username; ?>" method="post">
    <?php
    if ($followerid = $user) {
        if ($isFollowing) {
            echo '<input type="submit" name="unfollow" value="Unfollow">';
        } else {
            echo '<input type="submit" name="follow" value="Follow">';
        }
    }
    ?>
</form>





    <?php

    $content = $_POST["content"];
    echo $content;

    include('DB.php');

    $statement = $pdo->prepare("SELECT * FROM blog WHERE id=:id AND content=:content ");

    if($statement->execute(array(':id'=>$userid, ':content'=>$content))) {
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
