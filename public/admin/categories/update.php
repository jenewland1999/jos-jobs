<?php

/**
 * Admin Update Categories Controller for Jo's Jobs.
 * 
 * PHP Version 7
 * 
 * @category  Default
 * @package   Default
 * @author    Jordan Newland <github@jenewland.me.uk>
 * @copyright 2020 Jordan Newland
 * @license   All Rights Reserved
 * @link      https://github.com/jenewland1999/
 */

require __DIR__ . '/../../../includes/DatabaseConnection.php';
require __DIR__ . '/../../../classes/DatabaseTable.php';
require __DIR__ . '/../../../includes/LoadTemplate.php';

session_start();

$category = new DatabaseTable($pdo, 'category', 'id');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $templateVars = [
        'isLoggedIn' => true
    ];

    if (isset($_GET['id'])) {
        if (isset($_POST['submit'])) {
            if (rtrim($_POST['category']['name']) !== ''
                && $_POST['category']['id'] !== ''
            ) {
                $category->update($_POST['category']);
                $templateVars['message'] = 'Category updated';
                $templateVars['category'] = $category->find('id', $_GET['id'] ?? '')[0];
            } else {
                $templateVars['message'] = 'Category name & ID is required';
                $templateVars['category'] = $category->find('id', $_GET['id'] ?? '')[0];
            }
        } else {
            $templateVars['category'] = $category->find('id', $_GET['id'] ?? '')[0];
            $templateVars['message'] = '';
        }
    } else {
        $templateVars['message'] = 'Category not found';
    }
} else {
    $templateVars = [
        'isLoggedIn' => false
    ];
}

echo loadTemplate(
    __DIR__ . '/../../../templates/layout.html.php', 
    [
        'title' => 'Admin - Categories - Update',
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../../../templates/admin/categories/update.html.php',
            $templateVars
        )
    ]
);
