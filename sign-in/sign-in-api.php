<?php

include_once 'sign-in.php';

class UsersApi extends Users{
    
    // TODO add other endpoints & security checks

    public function __construct() {
        if (isset($_GET['isSignIn']))
        {
            return parent::isSignIn();
        }elseif (isset($_GET['signIn']) && isset($_GET['username']) && isset($_GET['password']))
        {
            return parent::signIn($_GET['username'], $_GET['password']);
        }elseif (isset($_GET['signOut'])) {
            return parent::signOut();
        }
    }
}