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
        header('Location: /public/');
        
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

    public function checkClientAccess($user_id, $client_id, $type) {
        // Return true if user has access to client(no restrictions set)



        // Check database for client access restriction
        $stmt = $this->pdo->prepare('SELECT * FROM user_client_restrictions WHERE restriction_user_id = :user_id AND restriction_client_id = :client_id');
        $stmt->execute(['user_id' => $user_id, 'client_id' => $client_id]);
        $restriction = $stmt->fetch($this->pdo::FETCH_ASSOC);
        error_log('client_restriction: ' . print_r($restriction, true));
        error_log('client: requested type: ' . print_r($type, true));
        // If restriction exists, check if it matches the type and class
        if ($restriction) {
            $restrictionType = $restriction['restriction_type'];
            error_log('client: restrictionType: ' . print_r($restrictionType, true));
            if (($restrictionType == $type)) {
                error_log('client: Found restriction');
                return false;
            }
        }
        return true;
    }   
    public function checkClassAccess($user_id, $type, $class) {
        //Check database for class access restriction
        $stmt = $this->pdo->prepare('SELECT * FROM user_class_restrictions WHERE restriction_user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        $restrictions = $stmt->fetchAll($this->pdo::FETCH_ASSOC);
        error_log('class_restrictions: ' . print_r($restrictions, true));
        error_log('class: requested type: ' . print_r($type, true));
        error_log('class: requested class: ' . print_r($class, true));
        //If restrictions exist, check if any match the type and class
        if (!empty($restrictions)) {
            foreach ($restrictions as $restriction) {
                $restrictionType = $restriction['restriction_type'];
                error_log('class: restrictionType: ' . print_r($restrictionType, true));
                $restrictionClass = $restriction['restriction_class'];
                error_log('class: restrictionClass: ' . print_r($restrictionClass, true));
                if (($restrictionType == $type && $restrictionClass == $class) || ($restrictionType == $class && $restrictionClass == $type)) {
                    error_log('class: Found restriction');
                    return false;
                }
            }
        }
        return true;
    }
}
