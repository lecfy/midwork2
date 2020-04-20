<?php

// full website url WITH a trailing slash, e.g. https://midreg.com/
// this mirrors your "public" folder location
$config['host'] = '';

// database access details
$config['db_host'] = 'localhost';
$config['db_name'] = '';
$config['db_user'] = '';
$config['db_password'] = '';

//$config['db_port'] = 3307;
//$config['db_dsn'] = '';
//$config['auth_table'] = '';

/*
 * Routes
 */
// default route = controller/method
$route['default'] = 'home/index';

/*
 * Careful!
 * Don't change the following details unless you know what you're doing!
 *
 * Must always have a trailing slash
 */

define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', __DIR__ . '/');
define('STORAGE_PATH', __DIR__ . '/../storage/');

if (file_exists(__DIR__ . '/../system')) {
    define('SYSTEM_PATH', __DIR__ . '/../system/');
} elseif(file_exists(__DIR__ . '/../vendor/midmyk/midwork2/system')) {
    define('SYSTEM_PATH', __DIR__ . '/../vendor/midmyk/midwork2/system/');
}