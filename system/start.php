<?php

$env = [];
if (file_exists(ROOT_PATH . '.env')) {
    $env = parse_ini_file(ROOT_PATH . '.env');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

function config($key) {
    global $config;
    global $env;

    if (!empty($env[$key])) {
        return $env[$key];
    }

    return !empty($config[$key]) ? $config[$key] : false;
}

/*
 * Language
 */

$lang = [];
if (config('lang')
    && in_array(config('lang'), config('languages'))
    && file_exists(APP_PATH . 'Language/' . config('lang') . '.php')
) {
    require_once (APP_PATH . 'Language/' . config('lang') . '.php');
}

function lang($key, $replace = false) {
    global $lang;

    if ($replace) {
        $search = is_array($replace) ? array_keys($replace) : '%s';
        $lang[$key] = str_replace($search, $replace, $lang[$key]);
    }

    return !empty($lang[$key]) ? $lang[$key] : $key;
}

session_start();

// includes common/basic functions
require_once APP_PATH . 'Common.php';
require_once 'Common.php';


/*
 * Routing
 */

// gets full url, e.g. https://website.com/controller/method/param/
$full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// removes trailing slash
$full_url = rtrim($full_url, '/');

// cuts website url and leaves us with controller, method and params
$uri = str_replace(config('host'), '', $full_url);

// if it doesn't match allowed characters, returns default rout
if (!preg_match('/^[\/a-z0-9_-]+$/i', $uri)) {
    /** @var array $route */
    $uri = $route['default'];
}

if (in_array($route, array_keys($route ) ) ) {
    $uri = $route[$uri];
}

// returns array as in: 0 => controller class, 1 => method, 2 and more => params
$explode = explode('/', $uri);

// redirect home if controller is undefined
if (empty($explode[0])) {
    redirect();
}

spl_autoload_register(function ($class) {
    $class = lcfirst($class);
    $class = str_replace('\\', '/', $class);

    if (file_exists(ROOT_PATH . $class . '.php')) {
        include ROOT_PATH . $class . '.php';
    } elseif (file_exists(SYSTEM_PATH . '../' . $class . '.php')) {
        include SYSTEM_PATH . '../' . $class . '.php';
    } else {
        die($class . " does not exist");
    }
});

// create object and unset explode[0]
$app_path = rtrim(APP_PATH, '/');
$app = substr($app_path, strrpos($app_path, '/' )+1);

$class_name = "\\$app\\Controllers\\" . ucfirst($explode[0]);
$object = new $class_name;
unset($explode[0]);

// check if method name is set (explode[1])
// default it to 'index' if it's not
// redirect home if method doesn't exist
$method = !empty($explode[1]) ? $explode[1] : 'index';
if (!method_exists($object, $method)) {
    die('Method ' . $class_name . '/' . $method . ' does not exist');
}
unset($explode[1]);


/*
 * Loading
 */
call_user_func_array([$object, $method], array_values($explode));