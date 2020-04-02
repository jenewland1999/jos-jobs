<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class Category
{
    private $authentication;
    private $categoriesTable;

    public function __construct(
        \CupOfPHP\Authentication $authentication,
        DatabaseTable $categoriesTable
    ) {
        $this->authentication = $authentication;
        $this->categoriesTable = $categoriesTable;
    }

    public function read()
    {
        $categories = $this->categoriesTable->findAll();

        return [
            'template' => '/admin/categories/index.html.php',
            'title' => 'Admin - Categories',
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'categories' => $categories
            ]
        ];
    }

    public function update()
    {
        if (isset($_GET['id'])) {
            $category = $this->categoriesTable->findById($_GET['id']);
            $title = 'Admin - Categories - Update';
        } else {
            $title = 'Admin - Categories - Create';
        }

        return [
            'template' => '/admin/categories/update.html.php',
            'title' => $title,
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'category' => $category ?? null
            ]
        ];
    }

    public function saveUpdate()
    {

        $category = new \JosJobs\Entity\Category($this->categoriesTable);

        foreach ($_POST['category'] as $key => $value) {
            $category->$key = $value;
        }

        // Assume that data is valid to begin with
        $errors = [];

        // If any fields are blank, set $valid to false
        if (empty($category->name)) {
            $errors[] = 'Category name cannot be blank.';
        } else {
            // Check if the category already exists on the site (This handles both updates and inserts)
            if (isset($_GET['id'])) {
                $exists = false;
                foreach ($this->categoriesTable->findAll() as $record) {
                    if ($record->category_id === $category->category_id) {
                        continue;
                    }

                    if (strtolower($record->name) !== strtolower($category->name)) {
                        continue;
                    }

                    $exists = true;
                }

                if ($exists) {
                    $errors[] = 'A category with that name already exists.';
                }
            } else {
                if ($this->categoriesTable->total('name', strtolower($category->name)) > 0) {
                    $errors[] = 'A category with that name already exists.';
                }
            }
        }

        if (empty($errors)) {
            $this->categoriesTable->save($_POST['category']);

            header('location: /admin/categories/');
        } else {
            $title = 'Admin - Categories - ';
            $title .= isset($_GET['id']) ? 'Update' : 'Create';

            return [
                'template' => '/admin/categories/update.html.php',
                'title' => $title,
                'variables' => [
                    'authUser' => $this->authentication->getUser(),
                    'errors' => $errors,
                    'category' => $category ?? null
                ]
            ];
        }
    }

    public function delete()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get the category object selected for deletion
        $category = $this->categoriesTable->findById($_POST['category_id']);

        // If the authenticated user isn't permitted to delete categories then
        // prevent delete action
        if (
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_CATEGORIES)
        ) {
            return;
        }

        // Perform the deletion of the category
        $this->categoriesTable->deleteById($_POST['category_id']);

        // Redirect the user to categories page
        header('location: /admin/categories/');
    }
}
