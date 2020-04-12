<?php namespace App\Models;

use System\Db;
use PDOException;

class Model
{
    public static function delete($table)
    {

    }

    public static function insert($table)
    {

    }

    public static function select($table, $value)
    {
        return self::select_by($table, 'id', $value);
    }

    public static function select_all($table)
    {
        try {
            $prepare = Db::conn()->prepare("SELECT * FROM $table");
            $prepare->execute();
            return $prepare->fetchAll();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function select_by($table, $column_name, $value)
    {
        try {
            $prepare = Db::conn()->prepare("SELECT * FROM $table WHERE $column_name = ?");
            $prepare->execute([$value]);
            return $prepare->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}