<?php
session_start();
if(!isset($_SESSION["angemeldet"]))
{
    echo"Bitte zuerst <a href=\"login.html\">einloggen</a>";
    die();
}

$content= $_POST["content"];


$pdo=new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de;dbname=u-ka034', 'ka034', 'zeeD6athoo',array('charset'=>'utf8'));

$statement = $pdo->prepare("INSERT INTO blog (content) VALUES (?)");
$statement->execute(array($content));

echo $content." "."mit id in der Datenbank: ".$id=$pdo->lastInsertId();
header('Location: index.php');
