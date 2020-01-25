<?php


class Database
{
    public static function getDB()
    {
        try
        {
            $db_host = "host";
            $db_name = "name";
            $db_user = "user";
            $db_pass = "password";

            $db = new PDO('mysql:host=' . $db_host .';dbname=' . $db_name, $db_user, $db_pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

        return $db;
    }
}
