<?php

/**
 * Admin Create Categories Controller for Jo's Jobs.
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

    if (isset($_POST['submit'])) {
        if (rtrim($_POST['category']['name']) !== '') {
            $category->insert($_POST['category']);
            $templateVars['message'] = 'Category added';
        } else {
            $templateVars['message'] = 'Category name is required';
        }
    } else {
        $templateVars['message'] = '';
    }
} else {
    $templateVars = [
        'isLoggedIn' => false
    ];
}

echo loadTemplate(
    __DIR__ . '/../../../templates/layout.html.php', 
    [
        'title' => 'Admin - Categories - Create',
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../../../templates/admin/categories/create.html.php',
            $templateVars
        )
    ]
);
