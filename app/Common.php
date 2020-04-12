<?php

use App\Auth;

/*
 * shortcut function for Auth::user
 */
function auth($column = false) {
    return Auth::user($column);
}

/*
 * redirects if not authenticated
 */
function auth_only() {
    if (!Auth::user()) {
        redirect('login');
    }
}

/*
 * redirects to account if authenticated, stays if guest
 */
function guest_only() {
    if (Auth::user()) {
        redirect('account');
    }
}