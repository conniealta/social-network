<?php
session_start();
?>

<!DOCTYPE html> <!-- das ist HTML 5 -->
<html lang="de">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <title> Blog </title>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <style>
        #box {
            width:100px;
            height:100px;
            background-color: #e0a800;
        }

        #container {
            height: 120px;
            padding-left: 60px;
            margin-top:30px;
            background-color: #3b5998;
        }

        #container2 {
            height: 300px;
            padding-left: 30px;
        }

        body
        {
            margin: 0;
            padding: 0;
            background: url(images/blue.jpg);
            background-size: contain;
            font-family: sans-serif;
            background-repeat: repeat;

        }

    </style>

</head>
<body>



<?php

if(!isset($_SESSION["angemeldet"]))
{
    echo"Bitte zuerst <a href=\"login.html\">einloggen</a>";
    die();
}
else {
    $userid = $_SESSION['angemeldet'];
    echo "Hallo User: ".$userid;
}
?>




<header>
    <A ID="toc"></A>

    <nav>
        <div id="erste">

            <ul class="list1">

                <li>
                    <a  class="active" href="index.php">Feed</a>
                </li>

                <li>
                    <a href="profil.html">Profil </a>
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


<div id="dritte">
    <a class="hov1" href="logout.php">Log out!</a>
</div>




<h1 class="title"> Posts  </h1>
<div id="dritte">
    <form action="do_post.php" method="post">
        <textarea name="content" rows="17" cols="70"> </textarea>
        <input type="submit" value="Post" />
    </form>
</div>

<br><br><br><br><br>






<div id="zweite">
    <p> Warum ist die Schrift so klein...</p>

    <?php

    $content = $_POST["content"];
    echo $content;

    $pdo = new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de; dbname=u-ka034', 'ka034', 'zeeD6athoo', array('charset' => 'utf8'));

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
</div>


<br><br><br><br>






<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body>
</html>



