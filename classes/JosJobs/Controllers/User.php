<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class User
{
    private $authentication;
    private $jobsTable;
    private $usersTable;
    private $get;
    private $post;

    public function __construct(
        \CupOfPHP\Authentication $authentication,
        DatabaseTable $jobsTable,
        DatabaseTable $usersTable,
        array $get,
        array $post
    ) {
        $this->authentication = $authentication;
        $this->jobsTable = $jobsTable;
        $this->usersTable = $usersTable;
        $this->get = $get;
        $this->post = $post;
    }

    public function read()
    {
        return [
            'template' => '/admin/users/index.html.php',
            'title' => 'Admin - Users',
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'users' => $this->usersTable->findAll()
            ]
        ];
    }

    public function update($errors = [])
    {
        if (empty($this->post['user'])) {
            if (isset($this->get['id'])) {
                $user = $this->usersTable->findById($this->get['id']);
            }
        } else {
            $user = new \JosJobs\Entity\User($this->jobsTable);

            foreach ($this->post['user'] as $key => $value) {
                $user->$key = $value;
            }
        }

        $title = 'Admin - Users - ';
        $title .= isset($this->get['id']) ? 'Update' : 'Create';

        return [
            'template' => '/admin/users/update.html.php',
            'title' => $title,
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'errors' => $errors,
                'user' => $user ?? null
            ]
        ];
    }

    public function saveUpdate()
    {
        // Extract the user array from $this->post field
        $user = $this->post['user'];

        // Run form validation passing the data from the form
        $errors = $this->validateForm($user);

        // If the form is valid perform create/update
        // action or show the form again with errors.
        if (empty($errors)) {
            // Perform password hashing/salting before creating/updating record
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

            // Create/Update record in database
            $this->usersTable->save($user);

            // Redirect the user to either users page or dashboard
            if (isset($this->get['redirect'])) {
                header('location: /admin/users/');
            } else {
                header('location: /admin/');
            }

            // Return status code from action
            return http_response_code();
        } else {
            return $this->update($errors);
        }
    }

    public function validateForm($user)
    {
        // Declare an empty array to store potential errors
        $errors = [];

        // Validate first name field
        if (empty($user['first_name'])) {
            $errors[] = 'First Name cannot be blank';
        } elseif (strlen($user['first_name']) > 255) {
            $errors[] = 'First Name exceeds 255 characters';
        }

        // Validate last name field
        if (empty($user['last_name'])) {
            $errors[] = 'Last Name cannot be blank';
        } elseif (strlen($user['last_name']) > 255) {
            $errors[] = 'Last Name exceeds 255 characters';
        }

        // Validate email address field
        if (empty($user['email'])) {
            $errors[] = 'Email cannot be blank';
        } elseif (strlen($user['email']) > 255) {
            $errors[] = 'Email exceeds 255 characters';
        } elseif (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address';
        } else {
            // Check if the user is already registered on the site (This handles both updates and inserts)
            if (isset($this->get['id'])) {
                // Assume the record doesn't exist
                $exists = false;

                // Loop through all the user database records
                foreach ($this->usersTable->findAll() as $record) {
                    // Disregard record if the current record pk matches that of the user being updated
                    if ($record->user_id === $user['user_id']) {
                        continue;
                    }

                    // Disregard record if the current record email does not match that of the user being updated
                    if (strtolower($record->email) !== strtolower($user['email'])) {
                        continue;
                    }

                    // If the conditions above are false a record with the
                    // new email address already exists that isn't their own
                    $exists = true;
                }

                // If a record with that email address already exists (that's not their own) throw an error
                if ($exists) {
                    $errors[] = 'A user with that email address already exists.';
                }
            } else {
                if ($this->usersTable->total('email', strtolower($user['email'])) > 0) {
                    $errors[] = 'A user with that email address already exists.';
                }
            }
        }

        // Validate password field
        if (empty($user['password'])) {
            $errors[] = 'Password cannot be blank';
        }

        // Return any form validation errors
        return $errors;
    }

    private function hashPassword(array $user): array
    {
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        return $user;
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

        // Return status code from action
        return http_response_code();
    }

    public function permissions()
    {
        $user = $this->usersTable->findById($this->get['id']);

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
