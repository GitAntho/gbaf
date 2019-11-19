<?php


class Database
{
    public static function getDB()
    {
        try
        {
            $db = new PDO('mysql:host=localhost;dbname=dbs220166', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

        return $db;
    }
}