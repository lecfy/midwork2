<?php namespace System;

use PDOException;

class Controller
{
    /*
     * empty constructor
     */
    public function __construct(){}

    /*
     * validates input fields
     */
    public function validate($input, $rules)
    {
        $rules = explode('|', $rules);

        foreach ($rules as $rule) {
            $input_name = '<B>' . ucfirst($input) . '</B>';

            if ($rule == 'required') {
                if (empty($_POST[$input])) {
                    set_alert("$input_name is required", 'danger');
                    back_with_input();
                }
                continue;
            } elseif (preg_match('/min:([0-9]+)/', $rule, $matches)) {
                if (strlen($_POST[$input]) < $matches[1]) {
                    set_alert("$input_name must be minimum $matches[1] characters", 'danger');
                    back_with_input();
                }
                continue;
            } elseif (preg_match('/max:([0-9]+)/', $rule, $matches)) {
                if (strlen($_POST[$input]) > $matches[1]) {
                    set_alert("$input_name can only be $matches[1] characters maximum", 'danger');
                    back_with_input();
                }
                continue;
            }
        }

    }

    /*
     * the following methods are for compatibility reasons only with version 1
     */

    public function prepare($prepare)
    {
        return Db::conn()->prepare($prepare);
    }

    public function query($query)
    {
        return Db::conn()->query($query);
    }

    public function last_id()
    {
        return Db::conn()->lastInsertId();
    }

    protected function ins($table, $data)
    {
        $columns = implode(',', array_keys($data));

        $questions = '?';
        for($i=1;$i<count($data); $i++) {
            $questions .= ',?';
        }

        try {
            $sql = "INSERT INTO $table ($columns) VALUES ($questions)";
            $prepare = $this->prepare($sql);
            $prepare->execute(array_values($data));
        } catch(PDOException $e) {
            exception($e, $sql, 'post');
        }

        return $this->last_id();
    }

    public function upd($table, $id, $data)
    {
        $sets = false;
        foreach ($data as $key => $value) {
            $sets .= ', ' . $key . '=?';
        }
        $sets = ltrim($sets, ',');

        if (is_numeric($id)) { //
            $column = 'id';
            $data['id'] = $id;
        } else {
            foreach ($id as $key => $value) {
                $column = $key;
                $data['id'] = $value;
            }
        }

        $q = $this->prepare("UPDATE $table SET $sets WHERE $column = ?");
        $q->execute(array_values($data));
        //print_r($id);
    }

    public function get($table, $id)  // @todo
    {
        if (is_numeric($id)) { // !is_array
            $q = $this->prepare("SELECT * FROM $table WHERE id = ?");
            $q->execute([$id]);
        } elseif ($id == 'rand') {
            $q = $this->query("SELECT * FROM $table ORDER BY RAND()");
        } else {
            $column = array_keys($id)[0];

            $q = $this->prepare("SELECT * FROM $table WHERE $column = ?");
            $q->execute(array_values($id));
        }
        return $q->fetch();
    }

    public function get_all($table, $where = false, $order_by = false)
    {
        // intro
        $sql = "SELECT * FROM $table";

        // adding where
        if ($where) {
            $column = array_keys($where)[0];
            $sql .= " WHERE $column = ?";
        }

        // prepare
        $q = $this->prepare($sql);

        // execute
        if ($where) {
            $value = array_values($where)[0]; // simplify
            $q->execute([$value]);
        } else {
            $q->execute();
        }

        return $q->fetchAll();
    }

    public function del($table, $id)
    {
        if (is_numeric($id)) { // !is_array
            $q = $this->prepare("DELETE FROM $table WHERE id = ?");
            $q->execute([$id]);
        } else {
            $column = array_keys($id)[0];
            $value = array_values($id)[0];

            $q = $this->prepare("DELETE FROM $table WHERE $column = ?");
            $q->execute([$value]);
        }

    }









}