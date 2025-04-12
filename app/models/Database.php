<?php
class Database {
    private static $connection = null;
    private static $db_password="banhmi";
    public static function connect() {
        if (self::$connection === null) {
            self::$connection = new mysqli("localhost", "root", self::$db_password, "mcq_db");
            if (self::$connection->connect_error) {
                die("Database Connection Failed: " . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
}
?>
