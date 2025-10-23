<?php
require_once 'core/init.php';

$user = new User();
$user->logout();
Session::flash('home', 'Terminou a sessão com sucesso.');
Redirect::to('login.php');
