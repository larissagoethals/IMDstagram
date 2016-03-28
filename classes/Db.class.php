<?php
class Db
{
    private static $conn;
    public static function getInstance(){
        if( is_null( self::$conn ) ){
            self::$conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        }
        return self::$conn;
    }
}
?>