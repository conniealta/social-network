<?php

class Comment {

    public static function createComment ($commentBody, $postId, $userId) {


        if (strlen($commentBody) > 100 || strlen($commentBody) < 1) {
            die('Inkorrekte Länge!');
        }

        if (!DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$postId))) {
            //wenn die 'id' nicht valide ist:
            echo "Invalide Post-Id";
        }
        else {
            DB::query('INSERT INTO comments VALUES (\'\', :comment, :userid, NOW(), :postid)', array(':comment'=>$commentBody, ':userid'=>$userId, ':postid'=>$postId));
        }

    }


    public static function displayComments($postId) {

        $comments = DB::query('SELECT comments.comment, list5.username FROM comments, list5 WHERE post_id = :postid AND comments.user_id = list5.id', array(':postid'=>$postId));
        // Join machen --> Fremdschlüssel mit den Primärschlüsseln zusammenfügen, sodass nur der Kommentar und der Name des Nutzers, der ihn geschrieben hat, angezeigt werden
        foreach ($comments as $comment) {
            echo $comment['comment']." ~ ".$comment['username']."<hr />";
        }
        // ['comment'] = die Spalte in der Datenbank


    }

}

?>