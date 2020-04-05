<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class User
{
    private $authentication;
    private $jobsTable;
    private $usersTable;

    public function __construct(
        \CupOfPHP\Authentication &$authentication,
        DatabaseTable $jobsTable,
        DatabaseTable $usersTable
    ) {
        $this->authentication = $authentication;
        $this->jobsTable = $jobsTable;
        $this->usersTable = $usersTable;
    }

    public function read()
    {
        $users = $this->usersTable->findAll();

        return [
            'template' => '/admin/users/index.html.php',
            'title' => 'Admin - Users',
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'users' => $users
            ]
        ];
    }

    public function update()
    {
        if (isset($_GET['id'])) {
            $user = $this->usersTable->findById($_GET['id']);
            $title = 'Admin - Users - Update';
        } else {
            $title = 'Admin - Users - Create';
        }

        return [
            'template' => '/admin/users/update.html.php',
            'title' => $title,
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'user' => $user ?? null
            ]
        ];
    }

    public function saveUpdate()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Create an object to store the new user in
        $user = new \JosJobs\Entity\User($this->jobsTable);

        // Populate the fields of the object using the form data from $_POST
        foreach ($_POST['user'] as $key => $value) {
            $user->$key = $value;
        }

        // Declare an array to store potential form validation errors
        $errors = [];

        if (empty($user->first_name)) {
            $errors[] = 'First Name cannot be blank';
        }

        if (empty($user->last_name)) {
            $errors[] = 'Last Name cannot be blank';
        }

        if (empty($user->email)) {
            $errors[] = 'Email cannot be blank';
        } elseif (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address';
        } else {
            // Check if the user is already registered on the site (This handles both updates and inserts)
            if (isset($_GET['id'])) {
                $exists = false;
                foreach ($this->usersTable->findAll() as $record) {
                    if ($record->user_id === $user->user_id) {
                        continue;
                    }

                    if (strtolower($record->email) !== strtolower($user->email)) {
                        continue;
                    }

                    $exists = true;
                }

                if ($exists) {
                    $errors[] = 'A user with that email address already exists.';
                }
            } else {
                if ($this->usersTable->total('email', strtolower($user->email)) > 0) {
                    $errors[] = 'A user with that email address already exists.';
                }
            }
        }

        if (empty($user->password)) {
            $errors[] = 'Password cannot be blank';
        }

        if (empty($errors)) {
            $_POST['user']['password'] = password_hash($user->password, PASSWORD_DEFAULT);
            $this->authentication->login($_POST['user']['email'], $_POST['user']['password']);
            $this->usersTable->save($_POST['user']);

            if (isset($_GET['redirect'])) {
                header('location: /admin/users/');
            } else {
                header('location: /admin/');
            }
        } else {
            $title = 'Admin - Users - ';
            $title .= isset($_GET['id']) ? 'Update' : 'Create';

            return [
                'template' => '/admin/users/update.html.php',
                'title' => $title,
                'variables' => [
                    'authUser' => $authUser,
                    'errors' => $errors,
                    'user' => $user ?? null
                ]
            ];
        }
    }

    public function delete()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get the user object selected for deletion
        $user = $this->usersTable->findById($_POST['user_id']);

        // If the authenticated user isn't the user being deleted and isn't
        // permitted to delete users then prevent delete action
        if (
            $user->user_id !== $authUser->user_id &&
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_USERS)
        ) {
            return;
        }

        // Perform the deletion of the user
        $this->usersTable->deleteById($_POST['user_id']);

        // Redirect the user to users page
        header('location: /admin/users/');
    }

    public function permissions()
    {
        $user = $this->usersTable->findById($_GET['id']);

        $permissions = (new \ReflectionClass('\JosJobs\Entity\User'))->getConstants();
        $permissionsJobs = array_filter(
            $permissions,
            function ($key) {
                return strpos($key, 'JOBS') !== false ? true : false;
            },
            ARRAY_FILTER_USE_KEY
        );
        $permissionsCategories = array_filter(
            $permissions,
            function ($key) {
                return strpos($key, 'CATEGORIES') !== false ? true : false;
            },
            ARRAY_FILTER_USE_KEY
        );
        $permissionsEnquiries = array_filter(
            $permissions,
            function ($key) {
                return strpos($key, 'ENQUIRIES') !== false ? true : false;
            },
            ARRAY_FILTER_USE_KEY
        );
        $permissionsLocations = array_filter(
            $permissions,
            function ($key) {
                return strpos($key, 'LOCATIONS') !== false ? true : false;
            },
            ARRAY_FILTER_USE_KEY
        );
        $permissionsUsers = array_filter(
            $permissions,
            function ($key) {
                return strpos($key, 'USERS') !== false ? true : false;
            },
            ARRAY_FILTER_USE_KEY
        );

        return [
            'template' => '/admin/users/permissions.html.php',
            'title' => 'Admin - Users - Permissions',
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'user' => $user,
                'permissionsCategories' => $permissionsCategories,
                'permissionsEnquiries' => $permissionsEnquiries,
                'permissionsJobs' => $permissionsJobs,
                'permissionsLocations' => $permissionsLocations,
                'permissionsUsers' => $permissionsUsers
            ]
        ];
    }

    public function savePermissions()
    {
        $user = [
            'user_id' => $_POST['user_id'],
            'permissions' => array_sum($_POST['permissions'] ?? [])
        ];

        $this->usersTable->save($user);

        header('location: /admin/users/');
    }
}
