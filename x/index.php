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
include('Post.php');
include('Comment.php');

$showTimeline = False;

if(!isset($_SESSION["angemeldet"]))
{
    echo"Bitte zuerst <a href=\"login.html\">einloggen</a>";
    die();
}
else {
    $user_loggedin = $_SESSION['angemeldet'];
    echo "Hallo User: ".$user_loggedin;
    $showTimeline = True;
}
?>



<h1> Das Profil von '<?php echo $user_loggedin; ?>'</h1>





<h1 class="title"> Feed  </h1>
<div id="zweite">
    <form action="do_post.php" method="post">
        <textarea name="content" rows="17" cols="70"> </textarea>
        <input type="submit" value="Post" />
    </form>
</div>


<?php

if (isset($_GET['postid'])) {
    Post::likePost($_GET['postid'], $user_loggedin); //wir ändern '$followerid' zu '$user_loggedin', weil in dieser Datei die Variable einfach umbenannt wurde
}

if (isset($_POST['comment'])) {
    Comment::createComment($_POST['commentbody'], $_GET['postid'], $user_loggedin); //wir ändern '$followerid' zu '$user_loggedin', weil in dieser Datei die Variable einfach umbenannt wurde
}



$followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, list5.username FROM list5, posts, followers 
                             WHERE posts.user_id = followers.user_id 
                             AND list5.id = posts.user_id 
                             AND follower_id = :userid
                             ORDER BY posts.likes DESC;', array(':userid'=>$user_loggedin));


foreach ($followingposts as $post) {

    echo $post['body'] . "~ " . $post['username'];
    echo "<form action='index.php?postid=" . $post['id'] . "' method='post'>";

    if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid' => $post['id'], ':userid' => $user_loggedin))) {
        /*damit überprüfen wir, ob der Post durch die eingeloggte Person schon geliked wurde
          wenn die eingeloggte Person den Post noch nicht geliked hat, wird dieses Formular angezeigt: */

        echo "<input type='submit' name='like' value='Like'>";
    }else {
        echo "<input type='submit' name='unlike' value='Unlike'>";

    }
    echo "<span>" . $post['likes'] . " likes</span>
              </form>
              
              
         
              <form action='index.php?postid=".$post['id']." 'method='post'>
              <textarea name='commentbody' rows='3' cols='50'></textarea>
              <input type='submit' name='comment' value='Kommentieren'>
              </form>
              ";
    Comment::displayComments($post['id']);

    echo" 

              <hr /></br />";

}

/* joints -> WHERE posts.user_id = followers.user_id
= zusammenfügen, wo die "id" der Person, deren Post angezeigt werden soll, mit der "id" der Person übereinstimmt, der von der eingeloggten Person gefolgt ist
*/

?>





</body>
</html>
