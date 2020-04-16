<?php

namespace JosJobs\Controllers;

class Auth
{
    private $authentication;
    private $get;
    private $post;

    public function __construct(\CupOfPHP\Authentication $authentication, array $get, array $post)
    {
        $this->authentication = $authentication;
        $this->get = $get;
        $this->post = $post;
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

    public function loginForm($errors = [])
    {
        return [
            'template' => '/id/login.html.php',
            'title' => 'Login',
            'variables' => [
                'errors' => $errors,
                'user' => $this->post['user'] ?? null
            ]
        ];
    }

    public function processLogin()
    {
        // Extract the user array from $this->post field
        $user = $this->post['user'];

        // Run the form validation
        $errors = $this->validateLogin($user);

        // If the form is valid, check the login credentials and
        // if valid log the user in otherwise display the form again.
        if (empty($errors)) {
            if ($this->authentication->login($user['email'], $user['password'])) {
                header('location: /admin/');
                return http_response_code();
            } else {
                $errors[] = 'Invalid login credentials';
                return $this->loginForm($errors);
            }
        } else {
            return $this->loginForm($errors);
        }
    }

    public function validateLogin($user)
    {
        // Declare an empty array to store potential errors
        $errors = [];

        // Validate the email address field
        if (empty($user['email'])) {
            $errors[] = 'Email address cannot be blank.';
        } elseif (strlen($user['email']) > 255) {
            $errors[] = 'Email address exceeds 255 characters';
        } elseif (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address';
        }

        // Validate the password field
        if (empty($user['password'])) {
            $errors[] = 'Password cannot be blank';
        }

        // Return any form validation errors
        return $errors;
    }

    public function logout()
    {
        // Log the user out by destroying the user's session
        session_destroy();

        // Redirect them to the login screen
        header('location: /id/login');
    }
}
