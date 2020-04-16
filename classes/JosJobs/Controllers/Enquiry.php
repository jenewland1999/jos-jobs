<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class Enquiry
{
    private $authentication;
    private $enquiriesTable;
    private $usersTable;
    private $get;
    private $post;

    public function __construct(
        \CupOfPHP\Authentication $authentication,
        DatabaseTable $enquiriesTable,
        DatabaseTable $usersTable,
        array $get,
        array $post
    ) {
        $this->authentication = $authentication;
        $this->enquiriesTable = $enquiriesTable;
        $this->usersTable = $usersTable;
        $this->get = $get;
        $this->post = $post;
    }

    public function create()
    {
        // Extract the enquiry array from $this->post field
        $enquiry = $this->post['enquiry'];

        // Tidy up the form data
        $enquiry['name'] = trim($enquiry['name']);
        $enquiry['email'] = trim($enquiry['email']);
        $enquiry['tel_no'] = trim($enquiry['tel_no']);
        $enquiry['enquiry'] = trim($enquiry['enquiry']);

        // Run the form validation
        $errors = $this->validateForm($enquiry);

        // If no errors were detected submit the form else
        // display form again with errors
        if (empty($errors)) {
            // Insert the enquiry into the database
            $this->enquiriesTable->insert($enquiry);

            // Reload the page with reply=success query string
            header('location: /contact?reply=success');

            // Return status code from action
            return http_response_code();
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

    public function validateForm($enquiry)
    {
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

        // Return any form validation errors
        return $errors;
    }

    public function read()
    {
        return [
            'template' => '/admin/enquiries/index.html.php',
            'title' => 'Admin - Enquiries',
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'enquiries' => $this->enquiriesTable->findAll()
            ]
        ];
    }

    public function readOne()
    {
        // If an enquiry isn't specified return them to the list of enquiries
        // TODO: Pass an error message to this page to explain the issue.
        if (!isset($this->get['id'])) {
            header('location: /admin/enquiries');
        }

        // Retrieve the enquiry list of all enquiries
        $enquiry = $this->enquiriesTable->findById($this->get['id']);

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
                'authUser' => $this->authentication->getUser(),
                'enquiry' => $enquiry,
                'users' => $users
            ]
        ];
    }

    public function assign()
    {
        // Get the enquiry object to assign a user to
        $enquiry = $this->enquiriesTable->findById($this->post['enquiry_id']);

        // Get the user object selected to be the assignee
        $user = $this->usersTable->findById($this->post['user_id']);

        // Perform the assignment operation on the enquiry
        $this->enquiriesTable->update([
            'enquiry_id' => $this->post['enquiry_id'],
            'user_id' => $this->post['user_id']
        ]);

        // Redirect the user to enquiry page
        header('location: /admin/enquiries/enquiry?id=' . $enquiry->enquiry_id);
    }

    public function complete()
    {
        // Get the enquiry object to be marked complete/incomplete
        $enquiry = $this->enquiriesTable->findById($this->post['enquiry_id']);

        // Perform the complete/incomplete operation on the enquiry
        // ? Sets the is_complete field to the opposite of what it currently is
        $this->enquiriesTable->update([
            'enquiry_id' => $this->post['enquiry_id'],
            'is_complete' => !$enquiry->is_complete
        ]);

        // Redirect the user to enquiries page
        header('location: /admin/enquiries/');
    }

    public function delete()
    {
        // Perform the deletion of the enquiry
        $this->enquiriesTable->deleteById($this->post['enquiry_id']);

        // Redirect the user to enquiries page
        header('location: /admin/enquiries/');

        // Return status code from action
        return http_response_code();
    }
}
