<?php


class Database
{
    public static function getDB()
    {
        try
        {
            $db_name = "gbaf";
            $db_user = "root";
            $db_pass = "";
            $db_host = "localhost";

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
