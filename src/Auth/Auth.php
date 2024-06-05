<?php
// src/Auth/Auth.php

namespace Twetech\Nestogy\Auth;



class Auth {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public static function check() {
        error_log('check: ' . print_r($_SESSION, true));
        return isset($_SESSION['user_id']);
    }

    public static function login($user_id) {
        $_SESSION['user_id'] = $user_id;
        
    }

    public static function logout() {
        unset($_SESSION['user_id']);
        session_destroy();
        header ('Location: login.php');
        exit;
    }

    public function findUser($email, $password) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE user_email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['user_password'])) {
            //check for token in output
            if (isset($user['user_token'])) {
                return [$user, 'user_id' => $user['user_id'], 'user_token' => $user['user_token']];
            }
            return $user;
        } else {
            return false;
        }
    }
}
