<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class Category
{
    private $authentication;
    private $categoriesTable;
    private $jobsTable;
    private $get;
    private $post;

    public function __construct(
        \CupOfPHP\Authentication $authentication,
        DatabaseTable $categoriesTable,
        DatabaseTable $jobsTable,
        array $get,
        array $post
    ) {
        $this->authentication = $authentication;
        $this->categoriesTable = $categoriesTable;
        $this->jobsTable = $jobsTable;
        $this->get = $get;
        $this->post = $post;
    }

    public function read()
    {
        return [
            'template' => '/admin/categories/index.html.php',
            'title' => 'Admin - Categories',
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'categories' => $this->categoriesTable->findAll()
            ]
        ];
    }

    public function update($errors = [])
    {
        if (empty($this->post['category'])) {
            if (isset($this->get['id'])) {
                $category = $this->categoriesTable->findById($this->get['id']);
            }
        } else {
            $category = new \JosJobs\Entity\Category($this->jobsTable);

            foreach ($this->post['category'] as $key => $value) {
                $category->$key = $value;
            }
        }

        $title = 'Admin - Categories - ';
        $title .= isset($this->get['id']) ? 'Update' : 'Create';

        return [
            'template' => '/admin/categories/update.html.php',
            'title' => $title,
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'category' => $category ?? null,
                'errors' => $errors
            ]
        ];
    }

    public function saveUpdate()
    {
        // Run form validation passing the data from the form
        $errors = $this->validateForm($this->post['category']);

        // If the form is valid perform create/update
        // action or show the form again with errors.
        if (empty($errors)) {
            // Create/Update record in database
            $this->categoriesTable->save($this->post['category']);

            // Redirect the user to categories page
            header('location: /admin/categories/');

            // Return status code from action
            return http_response_code();
        } else {
            return $this->update($errors);
        }
    }

    public function validateForm($category)
    {
        // Declare an empty array to store potential errors
        $errors = [];

        // Validate the category name field
        if (empty($category['name'])) {
            $errors[] = 'Category name cannot be blank';
        } elseif (strlen($category['name']) > 255) {
            $errors[] = 'Category name exceeds 255 characters';
        }

        // Return any form validation errors
        return $errors;
    }

    public function delete()
    {
        // Perform the deletion of the category
        $this->categoriesTable->deleteById($this->post['category_id']);

        // Redirect the user to categories page
        header('location: /admin/categories/');

        // Return status code from action
        return http_response_code();
    }
}
