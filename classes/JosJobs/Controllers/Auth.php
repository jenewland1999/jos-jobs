<?php

namespace JosJobs\Controllers;

class Auth
{
    private $authentication;

    public function __construct(\CupOfPHP\Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function forbidden()
    {
        return [
            'template' => '403.html.php',
            'title' => '403 - Forbidden',
            'variables' => [
                'isLoggedIn' => $this->authentication->isLoggedIn()
            ]
        ];
    }

    public function loginForm()
    {
        return [
            'template' => '/id/login.html.php',
            'title' => 'Login'
        ];
    }

    public function processLogin()
    {
        $user = $_POST['user'];

        // Assume that data is valid to begin with
        $valid = true;
        $validLogin = true;
        $errors = [];

        // If any fields are blank, set $valid to false
        if (empty($user['email'])) {
            $valid = false;
            $errors[] = 'Email cannot be blank.';
        } elseif (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $valid = false;
            $errors[] = 'Invalid email address';
        }

        if (empty($user['password'])) {
            $valid = false;
            $errors[] = 'Password cannot be blank';
        }

        if ($valid) {
            if (!$this->authentication->login($user['email'], $user['password'])) {
                $validLogin = false;
                $errors[] = 'Invalid login credentials';
            }
        }

        // If $valid and $validLogin are true then data must be valid and login was successful
        if ($valid && $validLogin) {
            header('location: /admin/');
        } else {
            return [
                'template' => '/id/login.html.php',
                'title' => 'Login',
                'variables' => [
                    'errors' => $errors,
                    'user' => $user ?? null
                ]
            ];
        }
    }

    public function logout()
    {
        unset($_SESSION);
        session_destroy();
        header('location: /id/login');
    }
}
