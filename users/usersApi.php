<?php
include_once 'users.php';

class UsersApi extends Users{
    
    // TODO add other endpoints & security checks

    public function __construct() {
        if (isset($_GET['isSignIn']))
        {
            echo parent::isSignIn();
        }elseif (isset($_GET['signIn']) && isset($_GET['username']) && isset($_GET['password']))
        {
            echo parent::signIn($_GET['username'], $_GET['password']);
            header('Location: ../sign-in.php');
        }elseif (isset($_GET['signOut'])) {
            echo parent::signOut();
            header('Location: ../sign-in.php');

        }
    }
}

new UsersApi();