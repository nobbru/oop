<?php

    require_once 'core/init.php';

    if(Session::exists('home')) {
        echo '<p>' . Session::flash('home') . '</p>';
    }
    
    //$user = DB::getInstance();

    // -> update('users', 3, array(
    //     'password' => 'newpassword',
    //     'name' => 'Dale Garrett'
    // ));

//     -> insert('users', array(
//         'username' => 'Dale',
//         'password' => 'password',
//         'salt' => 'salt'
// ));

    // -> get('users', array('username', '=', 'alex'))
    // if(!$user -> count()) {
    //     echo 'No user!';
    // } else {
    //     echo $user -> first() -> username;
    // }

