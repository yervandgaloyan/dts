<?php

include_once '../settings.php';

class Users extends Settings{

    public function isSignIn() : int
    {
        if(!isset($_COOKIE['username']) || !isset($_COOKIE['session']) || !isset($_COOKIE['user_id']) || !isset($_COOKIE['role'])) return 0;
        return $this->checkSession($_COOKIE['session'], $_COOKIE['user_id']);
        
    }
    public function signIn(string $username = null, string $password = null) : int
    {
        if(is_null($username) || is_null($password)) return -1;
        if(!($user = $this->getUserByUsername($username))) return -2;
        if(!password_verify($password, $user['password'])) return 0;

        setcookie("username", $username, 2147483647, "/");
        setcookie("session", $this->addSession($this->generateId(), $user['user_id']), 2147483647, "/");
        setcookie("user_id", $$user['user_id'], 2147483647, "/");
        setcookie("role", $user['role'], 2147483647, "/");
        return 1;
        
    }

    public function signOut() : int
    {
        setcookie("username", "", time() - 3600);
        setcookie("session", "", time() - 3600);
        setcookie("user_id", "", time() - 3600);
        setcookie("role", "", time() - 3600);
        return 1;
    }

    public function addUser(string $username = null, string $password = null, string $role = null) : int
    {
        if(is_null($username) || is_null($password) || is_null($role)) return -1;
        $this->getUsers();
        $user_id = $this->generateId();
        $newUser = array('user_id' => $user_id, 'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => $role ,'registration_date' => time());
        $this->users[$username] = $newUser;
        if(file_put_contents('users.php', '<?php return ' . var_export($this->users, true) . ';')) return 1;
        return 0;
    }
    
    public function getUserByUsername(string $username = null)
    {
        if(is_null($username)) return -1;
        $this->getUsers();
        if(!array_key_exists($username, $this->users)) return 0;
        return $this->users[$username];
    }

    // Get users
    private function getUsers() : bool
    {
        $this->users = include 'users.php';
        return true;
    }
    
    // Return all users
    public function getAllUsers()
    {
        $this->getUsers();
        return $this->users;
    }


    // ----------------SESSIONS--------------- //

    // Get sessions
    private function getSessions() : bool
    {
        $this->session = include 'sessions.php';
        return true;
    }
    
    // Return all sessions
    public function getAllSessions()
    {
        $this->getSessions();
        return $this->session;
    }
    
    // Return session by session_id
    public function getSessionBySessionId(string $session_id = null)
    {
        if(is_null($session_id)) return -1;
        $this->getSessions();
        if(!array_key_exists($session_id, $this->session)) return 0;
        return $this->session[$session_id];
    }
    
    // Add session
    public function addSession(string $session_id = null, $user_id = null)
    {
        if(is_null($session_id) || is_null($user_id)) return -1;
        $this->getSessions();
        $newSession = array('user_id' => $user_id, 'sign_in_date' => time(), 'expire_date' => time()+intval(parent::getConfigByName('sessionExpire')));
        $this->session[$session_id] = $newSession;
        if(file_put_contents('sessions.php', '<?php return ' . var_export($this->session, true) . ';')) return $newSession;
        return 0;
    }

    public function checkSession(string $session_id = null, string $user_id) : int
    {
        if(is_null($session_id) || is_null($user_id)) return -1;
        $session = $this->getSessionBySessionId($session_id);
        if($session != 1) return 0;
        if($session['user_id'] == $user_id && $session['expire_date'] > time()) return 1;
        return 0;
    }

    public function generateId(int $length = 20) : string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

// $signIn = new Users;

// print_r($signIn->addSession($signIn->generateId(),$signIn->generateId()));
// print_r($signIn->getSessionBySessionId('KtiNq16i603uxMOSD7sn'));

// print_r($signIn->addUser('test@gmail.com', 'pass', 'manager'));
// print_r($signIn->getUserByUsername('yervandgaloyan@gmail.com'));
?>