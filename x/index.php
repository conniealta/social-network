<?php
session_start();
?>

<!DOCTYPE html> <!-- das ist HTML 5 -->
<html lang="de">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <title> Feed </title>

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
include('DB.php');
$showTimeline = False;

if(!isset($_SESSION["angemeldet"]))
{
    echo"Bitte zuerst <a href=\"login.html\">einloggen</a>";
    die();
}
else {
    $user = $_SESSION['angemeldet'];
    echo "Hallo User: ".$user;
    $showTimeline = True;
}
?>


<h1> Das Profil von '<?php echo $user; ?>'</h1>





<h1 class="title"> Feed  </h1>
<div id="zweite">
    <form action="do_post.php" method="post">
        <textarea name="content" rows="17" cols="70"> </textarea>
        <input type="submit" value="Post" />
    </form>
</div>


<?php
$followingposts = DB::query('SELECT posts.body, posts.likes, list5.username FROM list5, posts, followers 
                                    WHERE posts.user_id = followers.user_id 
                                    AND list5.id = posts.user_id 
                                    AND follower_id = 20
                                    ORDER BY posts.likes DESC');

foreach ($followingposts as $post) {
    echo $post['body']."~~~".$post['username']."<hr />";
}

//joints
?>



<div id="dritte">
    <p> Posts</p>

    <?php

    $content = $_POST["content"];
    echo $content;

    $pdo = new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de; dbname=u-ka034', 'ka034', 'zeeD6athoo', array('charset' => 'utf8'));

    $statement = $pdo->prepare("SELECT * FROM blog");

    if($statement->execute()) {
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
</div>

</body>
</html>

