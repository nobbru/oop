<?php
require_once __DIR__ . '/../core/init.php';

function require_login() {
    $u = new User();
    if (!$u->isLoggedIn()) {
        Session::flash('home', 'Por favor, entre para continuar.');
        Redirect::to('/login.php');
    }
}

function require_admin() {
    $u = new User();
    if (!$u->isLoggedIn() || !$u->hasRole('admin')) {
        Redirect::to(404);
    }
}
