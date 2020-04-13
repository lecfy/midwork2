<?php namespace App\Models;

use System\Db;
use PDOException;

class Model
{
    public static function delete($table, $id)
    {
        return self::delete_where($table, [
            'id' => $id
        ]);
    }

    public static function delete_where($table, $where)
    {
        $column = array_keys($where)[0];
        $value = $where[0];

        try {
            $sql = "DELETE FROM $table WHERE $column = ?";

            $prepare = Db::conn()->prepare($sql);
            $prepare->execute([$value]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function insert($table, $data)
    {
        try {
            $columns = implode(',', array_keys($data));

            $questions = '?';
            for($i=1;$i<count($data); $i++) {
                $questions .= ',?';
            }

            $sql = "INSERT INTO $table ($columns) VALUES($questions)";

            $prepare = Db::conn()->prepare($sql);
            $prepare->execute();
            return Db::conn()->lastInsertId();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function select($table, $id)
    {
        return self::select_where($table, 'id', $id);
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

    public static function select_where($table, $where)
    {
        $column = array_keys($where)[0];
        $value = $where[0];

        try {
            $prepare = Db::conn()->prepare("SELECT * FROM $table WHERE $column = ?");
            $prepare->execute([$value]);
            return $prepare->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}