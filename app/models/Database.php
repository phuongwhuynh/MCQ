<?php
class Database
{
    private static $connection = null;
    private static $db_password = "";
    public static function connect()
    {
        if (self::$connection === null) {
            self::$connection = new mysqli("localhost", "mysql", self::$db_password, "test");
            if (self::$connection->connect_error) {
                die("Database Connection Failed: " . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
}
