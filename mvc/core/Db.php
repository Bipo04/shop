<?php
class Database {
    private static $conn;

    public static function getConnection() {
        if (!isset(self::$conn)) {
            $serverName = 'DESKTOP-5HQDO2R\SQLEXPRESS';
            $database = 'AngelBabie';
            $uid = '';
            $pass = '';
            try {
                self::$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $uid, $pass);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$conn;
    }

    function join_table($table ,$table_join = NULL , $query_join = NULL,$type_join = NULL){
        $qr = '';
        if ($table != NULL && $query_join != NULL && $type_join != NULL) {
            $qr .= ' '.$type_join.' JOIN ' .$table_join .' '.'ON '.$table.'.'.$query_join[0].' = '.$table_join.'.'.$query_join[1] ;
        }
        return $qr;
    }
}
?>