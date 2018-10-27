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

    </style>

</head>
<body>

<?php
session_start();
if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="login.php">einloggen</a>');
}

//Abfrage der Nutzer ID vom Login
$userid = $_SESSION['userid'];

echo "Hallo User: ".$userid;
?>

<header>
    <A ID="toc"></A>

    <nav>
        <div id="erste">
            <ul class="list1">

                <li>
                    <a  class="active" href="startseite.php">Home</a>
                </li>

                <li>
                    <a href=".html">Feed</a>
                </li>
                <li>
                    <a class="wi" href=".html">Profil</a>
                </li>

                <li>
                    <a class="wi" href=".html">Messages</a>
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

<h1 class="title"> Blog </h1>
<div id="zweite">
    <form action="schreiben.php" method="post">
        <textarea name="content" rows="30" cols="95"> </textarea>
        <input type="submit"/>
    </form>
</div>



<div id="container">
    <div id="box">
        Hey <3
    </div>
</div>

<br>

<button onclick="$('#box').show()" >einblenden</button>
<button onclick="$('#box').hide()">ausblenden</button>

<br>

<div id="dritte">
    <a href="lesen2.php">Blogeinträge!</a>
    <br><br><br>
    <a class="hov1" href="logout.php">Log out!</a>
</div>

<br><br>

<div id="container2">
    <img src="k.jpg" width="300">
</div>

<br><br><br><br><br><br><br><br><br>

<button onclick="$('img').show()">Bild anzeigen</button>
<button onclick="$('img').hide()">Bild weg</button>
<button onclick="$('img').attr('src','adv2.jpg')">Anderes Bild</button>
<button onclick="$('img').attr('src','k.jpg')">Erstes Bild</button>


<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body>
</html>



