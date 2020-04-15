<?php
/*
 * Common/helper functions
 */

/*
 * returns previous post values or default
 *
 * @return string|false
 */
if (!function_exists('value')) {
    function value($input, $default = false) {
        if (!empty($_SESSION['post'][$input])) {
            $temp = $_SESSION['post'][$input];
            unset($_SESSION['post'][$input]);

            return $temp;
        } elseif (!empty($default)) {
            return $default;
        }

        return false;
    }
}

/*
 * show notification message
 */
function show_alert() {
    if (!empty($_SESSION['alert'])) {
        $temp = $_SESSION['alert'];
        unset($_SESSION['alert']);

        return view('alert', [
            'message' => $temp['message'],
            'type' => $temp['type']
        ]);
    }

    return false;
}

/*
 * sets notication message
 */
function set_alert($message, $type = 'primary') {
    $_SESSION['alert']['message'] = $message;
    $_SESSION['alert']['type'] = $type;
}

/*
 * shows not found error message
 */
function not_found() {
    return view('not_found', [
        'header' => false,
        'footer' => false,
        'title' => 'Not Found'
    ]);
}

/*
 * generates url or html link
 *
 * @return mixed
 */
function href($link = false, $name = false) {
    $return = config('host') . $link;

    if ($name) {
        $return = '<a href="' . $return . '">' . $name . '</a>';
    }

    return $return;
}

/*
 * returns view
 *
 * @return mixed
 */
if (!function_exists('view')) {
    function view($name, $data = []) {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        return include_once APP_PATH . 'Views/' . $name . '.php';
    }
}

/*
 * goes back to previous page with session['post']
 */
function back_with_input() {
    $_SESSION['post'] = $_POST;

    back();
}

/*
 * goes back to previous page
 */
function back() {
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

/*
 * Redirect Shortcut Function
 *
 * @param url
 *
 */
function redirect($url = false) {
    header('location: ' . config('host') . $url);
    exit;
}

function exception($e, ... $errors)
{
    echo '<p style="font-weight: bold; color: red;">' . $e->getMessage() . '</p>';

    foreach ($errors as $error) {
        if ($error == 'post') {
            echo '<p style="font-weight: bold; color: black;">$_POST: ';
            print_r($_POST);
            echo '</p>';
        } else {
            echo '<p style="font-weight: bold; color: rgb(230,33,67);">' . $error . '</p>';
        }
    }

    $_SESSION['no_redirect'] = true;
}