<?php namespace System;

use PDOException;

class Model
{
    public static function delete(string $table, int $id)
    {
        return self::delete_where($table, [
            'id' => $id
        ]);
    }

    public static function delete_where(string $table, array $where)
    {
        $column = array_keys($where)[0];

        try {
            $sql = "DELETE FROM $table WHERE $column = ?";

            $prepare = Db::conn()->prepare($sql);
            $prepare->execute(array_values($where));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function insert(string $table, array $data)
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

    /*
     * returns one row
     */
    public static function select(string $table, int $id)
    {
        return self::select_where($table, [
            'id' => $id
        ]);
    }

    /*
     * returns one row
     */
    public static function select_where(string $table, array $where)
    {
        $column = array_keys($where)[0];

        try {
            $prepare = Db::conn()->prepare("SELECT * FROM $table WHERE $column = ?");
            $prepare->execute(array_values($where));
            return $prepare->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /*
     * returns all rows
     */
    public static function select_all(string $table)
    {
        try {
            $prepare = Db::conn()->prepare("SELECT * FROM $table");
            $prepare->execute();
            return $prepare->fetchAll();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}