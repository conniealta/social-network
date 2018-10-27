<?php

$content = $_POST["content"];
echo $content;


$pdo = new PDO('mysql:: host=mars.iuk.hdm-stuttgart.de; dbname=u-ka034', 'ka034', 'zeeD6athoo', array('charset' => 'utf8'));


$statement = $pdo->prepare("INSERT INTO blog (content) VALUES (?)");
$statement->execute(array($content));
echo "id in der Datenbank: ".$id=$pdo->lastInsertId();


