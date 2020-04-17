<?php namespace System;

class Auth
{
    private static $user;

    /*
     * returns $user if already exists, otherwise creates new instance
     *
     * @return array|string|mixed
     */
    public static function user(string $column = '')
    {
        if (empty($_SESSION['user_id'])) {
            return false;
        }

        if (empty(self::$user)) {
            self::$user = Model::select('users', $_SESSION['user_id']);
        }

        return $column ? self::$user[$column] : self::$user;
    }

    /*
     * creates auth session
     */
    public static function create(int $user_id)
    {
        $_SESSION['user_id'] = $user_id;
    }

    /*
     * unsets auth session
     */
    public static function destroy()
    {
        unset($_SESSION['user_id']);
    }

}