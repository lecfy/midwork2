<?php

session_start([
    'cookie_lifetime' => 86400 * 90
]);

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

    if ($key == 'languages') {
        if (!is_readable(APP_PATH . 'Language')) {
            return [];
        }

        $array = scandir(APP_PATH . 'Language');
        $array = array_slice($array, 2);
        $array = str_replace('.php', '', $array);

        return $array;
    }

    if (!empty($env[$key])) {
        return $env[$key];
    }

    return !empty($config[$key]) ? $config[$key] : false;
}

// includes common/basic functions
require_once APP_PATH . 'Common.php';
require_once 'Common.php';

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

/*
 * htmlspecialchars + remove spaces ltrim and rtrim
 */
if($_POST){
    foreach($_POST as $item=>$value){
        $_POST[$item] = hsc($_POST[$item]);
        $_POST[$item] = preg_replace(['/^\s+$/', '/^\s+/', '/\s+$/'], '', $_POST[$item]);
        //$_POST[$item] = preg_replace('/(\S+)(\s{2,})(\S+)/', '$1 $3', $_POST[$item]); @todo
    }
}

/*
 * Routing
 */

/*// gets full url, e.g. https://website.com/controller/method/param/
$full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// removes trailing slash
$full_url = rtrim($full_url, '/');

// cuts website url and leaves us with controller, method and params
$uri = str_replace(config('host'), '', $full_url);*/

$uri = !empty($_GET['uri']) ? $_GET['uri'] : false;

// if it doesn't match allowed characters, returns default rout
if (!preg_match('/^[\/a-z0-9_-]+$/i', $uri)) {
    /** @var array $route */
    $uri = $route['default'];
}

// custom routes
if (in_array($uri, array_keys($route) ) ) {
    $uri = $route[$uri];
}

// returns array as in: 0 => controller class, 1 => method, 2 and more => params
$explode = explode('/', $uri);

// redirect home if controller is undefined
if (empty($explode[0])) {
    redirect();
}

if(file_exists(ROOT_PATH . 'vendor/autoload.php')) {
    require_once ROOT_PATH . '/vendor/autoload.php';
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
$class_name = "\\App\\Controllers\\" . ucfirst($explode[0]);
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