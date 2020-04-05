<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class Enquiry
{
    private $authentication;
    private $enquiriesTable;
    private $usersTable;

    public function __construct(
        \CupOfPHP\Authentication $authentication,
        DatabaseTable $enquiriesTable,
        DatabaseTable $usersTable
    ) {
        $this->authentication = $authentication;
        $this->enquiriesTable = $enquiriesTable;
        $this->usersTable = $usersTable;
    }

    public function create()
    {
        // Store the enquiry for easier access
        $enquiry = $_POST['enquiry'];

        // Tidy up the form data
        $enquiry['name'] = trim($enquiry['name']);
        $enquiry['email'] = trim($enquiry['email']);
        $enquiry['tel_no'] = trim($enquiry['tel_no']);
        $enquiry['enquiry'] = trim($enquiry['enquiry']);

        // Declare an array to store potential form validation errors
        $errors = [];

        // Validate name field
        if (empty($enquiry['name'])) {
            $errors[] = 'Name cannot be blank';
        } elseif (strlen($enquiry['name']) > 255) {
            $errors[] = 'Name exceeds max length of 255 characters';
        }

        // Validate email field
        if (empty($enquiry['email'])) {
            $errors[] = 'Email cannot be blank';
        } elseif (strlen($enquiry['email']) > 255) {
            $errors[] = 'Email exceeds max length of 255 characters';
        } elseif (!filter_var($enquiry['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email must be a valid email address';
        }

        // Validate phone number field
        if (empty($enquiry['tel_no'])) {
            $errors[] = 'Phone Number cannot be blank';
        } elseif (!preg_match('/\+[0-9]{12}$/', $enquiry['tel_no'])) {
            $errors[] = 'Phone Number must be a valid UK phone number';
        }

        // Validate message field
        if (empty($enquiry['enquiry'])) {
            $errors[] = 'Message cannot be blank';
        } elseif (strlen($enquiry['enquiry']) > 8191) {
            $errors[] = 'Message exceeds max length of 8191';
        }

        // If no errors were detected submit the form else
        // display form again with errors
        if (empty($errors)) {
            $this->enquiriesTable->insert($enquiry);

            // Reload the page with reply=success query string
            header('location: /contact?reply=success');
        } else {
            return [
                'template' => '/contact/index.html.php',
                'title' => 'Contact Us',
                'variables' => [
                    'errors' => $errors,
                    'enquiry' => $enquiry
                ]
            ];
        }
    }

    public function read()
    {
        // Retrieve the authenticated user
        $authUser = $this->authentication->getUser();

        // Retrieve a list of all enquiries
        $enquiries = $this->enquiriesTable->findAll();

        return [
            'template' => '/admin/enquiries/index.html.php',
            'title' => 'Admin - Enquiries',
            'variables' => [
                'authUser' => $authUser,
                'enquiries' => $enquiries
            ]
        ];
    }

    public function readOne()
    {
        // If an enquiry isn't specified return them to the list of enquiries
        // TODO: Pass an error message to this page to explain the issue.
        if (!isset($_GET['id'])) {
            header('location: /admin/enquiries');
        }

        // Retrieve the authenticated user
        $authUser = $this->authentication->getUser();

        // Retrieve the enquiry list of all enquiries
        $enquiry = $this->enquiriesTable->findById($_GET['id']);

        // If an enquiry isn't found return them to the list of enquiries
        // TODO: Pass an error message to this page to explain the issue.
        if (empty($enquiry)) {
            header('location: /admin/enquiries');
        }

        // Retrieve a list of valid assignees for enquiries
        $users = $this->usersTable->findComplex(
            [
                [
                    'field' => 'permissions',
                    'operator' => '>',
                    'value' => 1635
                ]
            ]
        );

        return [
            'template' => '/admin/enquiries/enquiry.html.php',
            'title' => 'Admin - Enquiries - Enquiry #' . $enquiry->enquiry_id,
            'variables' => [
                'authUser' => $authUser,
                'enquiry' => $enquiry,
                'users' => $users
            ]
        ];
    }

    public function assign()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get the enquiry object to assign a user to
        $enquiry = $this->enquiriesTable->findById($_POST['enquiry_id']);

        // Get the user object selected to be the assignee
        $user = $this->usersTable->findById($_POST['user_id']);

        // If the authenticated user isn't permitted to assign enquiries
        // or the user selected somehow doesn't exist then prevent the
        // assign action from executing
        if (
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_ASSIGN_ENQUIRIES) ||
            empty($user)
        ) {
            // Redirect the user to enquiry page
            header('location: /admin/enquiries/enquiry?id=' . $enquiry->enquiry_id);
        }

        // Perform the assignment operation on the enquiry
        $this->enquiriesTable->update([
            'enquiry_id' => $_POST['enquiry_id'],
            'user_id' => $_POST['user_id']
        ]);

        // Redirect the user to enquiry page
        header('location: /admin/enquiries/enquiry?id=' . $enquiry->enquiry_id);
    }

    public function complete()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get the enquiry object to be marked complete/incomplete
        $enquiry = $this->enquiriesTable->findById($_POST['enquiry_id']);

        // If the authenticated user isn't permitted to mark enquiries
        // complete/incomplete then prevent complete action
        if (
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_COMPLETE_ENQUIRIES)
        ) {
            return;
        }

        // Perform the complete/incomplete operation on the enquiry
        // ? Sets the is_complete field to the opposite of what it currently is
        $this->enquiriesTable->update([
            'enquiry_id' => $_POST['enquiry_id'],
            'is_complete' => !$enquiry->is_complete
        ]);

        // Redirect the user to enquiries page
        header('location: /admin/enquiries/');
    }

    public function delete()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // If the authenticated user isn't permitted to
        // delete enquiries then prevent delete action
        if (
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_ENQUIRIES)
        ) {
            return;
        }

        // Perform the deletion of the enquiry
        $this->enquiriesTable->deleteById($_POST['enquiry_id']);

        // Redirect the user to enquiries page
        header('location: /admin/enquiries/');
    }
}
