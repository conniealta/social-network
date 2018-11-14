<?php
class Post {

    public static function createPost($postbody, $loggedIn_userid, $profileUserId) {


        if (strlen($postbody) > 1000 || strlen($postbody) < 1) {
            die('Inkorrekte Länge!');
        }
        if ($loggedIn_userid == $profileUserId) { //wenn die eingeloggte Person auf ihrer eigenen Profilseite ist, dann darf sie Einträge posten
            DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0)', array(':postbody' => $postbody, ':userid' => $profileUserId));
        } // -> '\'= die erste Spalte in der Datenbanktabelle ("id"); NOW() = das ist eine Funktion, die das aktuelle Datum und Uhrzeit anzeigt; '0'= die Standardanzahl der "Likes"
        else {
            die('Falscher Benutzer!');
        }

    }



    public static function likePost($postid, $likerId) {

        if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$likerId))) {
            //wenn folgendes nicht der Fall ist: der Benutzer hat den Post bereits geliked, dann wird der Code ausgeführt:
            DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid' => $postid));
            //wo die Post-"id" in der Datenbank gleich die ":postid" ist, die dem URL übergeben wird, wenn man auf den Like-Button klickt
            DB::query('INSERT INTO post_likes VALUES (\'\',:postid, :userid)', array(':postid' => $postid, ':userid' => $likerId));
            //das zeigt ob die eingeloggte Person (followerid) den Post geliked hat
        }
        else {
            DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid' => $postid));
            DB::query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid' => $postid, ':userid' => $likerId));
        }
    }



    public static function displayPosts($userid, $username, $loggedIn_userid) {

        $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
        $posts = "";
        foreach($dbposts as $p) {

            if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid' => $p['id'], ':userid' => $loggedIn_userid))) {

                $posts .= htmlspecialchars($p['body']) . "
              <form action='profile.php?username=$username&postid=" . $p['id'] . "' method='post'>
                 <input type='submit' name='like' value='Like'>
                 <span>".$p['likes']." likes</span>
              </form>
              <hr /></br />";
            }
            else {
                $posts .= htmlspecialchars($p['body']) . "
              <form action='profile.php?username=$username&postid=" . $p['id'] . "' method='post'>
                 <input type='submit' name='unlike' value='Unlike'>
                 <span>".$p['likes']." likes</span>
              </form>
              <hr /></br />";
            }

        }

        return $posts;

        //mit "return" --> dies gibt die Variable '$posts = "";' zurück , die all den HTML-Code und alle Posts beinhaltet

        /* $p = ein Array mit den Datenbankeinträgen: z.B. --> Array ([id]=>1 [0]=>1 [body]=>Hello [1]=>Hello [posted_at]=>2018-11-08 17:37:23 ...)
        $p[body] = unser Post wird bei "body" in der Datenbanktabelle gespeichert --> mit dieser Funktion sehen wir nur den Inhalt des Posts (nicht id, Datum, etc.)
        $posts = "" -> zunächst leer Array
        "<hr />" = horizontale Linie
        ".=" (->  $txt1 = "Hello"; $txt2 = " world!"; $txt1 .= $txt2; --> Hello world)
        htmlspecialchars = wandelt Sonderzeichen in HTML-Codes um
        postid = das ist die "id" des jeweiligen Posteintrag
        if (isset($_GET['postid']) -> prüfen, ob der Like-Button geklickt wurde, wenn ja
        */
    }



}
?>