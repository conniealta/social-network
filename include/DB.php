<?php
class DB {
    private static function connect () {
        $pdo = new PDO ('mysql:: host=mars.iuk.hdm-stuttgart.de;dbname=u-ka034', 'ka034', 'zeeD6athoo',array('charset'=>'utf8'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //we need to this for pdo's error reporting
        return $pdo;
    }
    public static function query($query, $params = array()) {
        $statement = self::connect()->prepare($query);
        $statement->execute($params);

        if (explode(' ', $query)[0] == 'SELECT') {
            $data = $statement->fetchAll();
            return $data;
        }
        //if the first keyword is equal to SELECT then we will run this, otherwise we won't

    }
    //query = this is how we are going to interact with our database
    // we need to write a code to actually query our database
    // so what this function does - it takes the query ($query) we want to run, it takes the parameters for that query -> then connects to the database (self::connect), it prepares the query and executes the query -> then takes the data from the query and returns it
}

//'static' = means that we don't need to create an object of the DB-class to be able to use it