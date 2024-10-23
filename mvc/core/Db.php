<?php

class Database {
    private static $conn;

    public static function getConnection() {
        if (!isset(self::$conn)) {
            $serverName = SERVER_NAME; 
            $database = DB_NAME;       
            $uid = DB_USERNAME;       
            $pass = DB_PASSWORD;    

            try {
                self::$conn = new PDO("mysql:host=$serverName;dbname=$database;charset=utf8", $uid, $pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$conn;
    }

    function join_table($table, $table_join = NULL, $query_join = NULL, $type_join = NULL) {
        $qr = '';
        if ($table != NULL && $query_join != NULL && $type_join != NULL) {
            $qr .= ' ' . $type_join . ' JOIN ' . $table_join . ' ON ' . $table . '.' . $query_join[0] . ' = ' . $table_join . '.' . $query_join[1];
        }
        return $qr;
    }
}
?>
