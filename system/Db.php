<?php namespace System;

use PDO;
use PDOException;

class Db
{
    protected static $instance;

    /*
     * Establishes connection to DB
     *
     * Use app/Config.php to set it up
     */
    public static function conn()
    {
        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO('mysql:host=' . config('db_host') . ';dbname=' . config('db_name'), config('db_user'), config('db_password'));
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}