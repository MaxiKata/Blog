<?php


namespace Blog\App\Entity;


class Session
{
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key = null, $filter = null, $fillWithEmptyString = false)
    {
        if (!$key) {
            if (function_exists('filter_var_array')) {
                return $filter ? filter_var_array($_SESSION, $filter) : $_SESSION;
            } else {
                return $_SESSION;
            }
        }
        if (isset($_SESSION[$key])) {
            if (function_exists('filter_var')) {
                return $filter ? filter_var($_SESSION[$key], $filter) : $_SESSION[$key];
            } else {
                return $_SESSION[$key];
            }
        } else if ($fillWithEmptyString === true) {
            return '';
        }
        return null;
        /*return (isset($_SESSION[$key]) ? $_SESSION[$key] : null);*/
    }

    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }
}