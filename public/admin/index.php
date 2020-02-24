<?php

/**
 * Admin Login/Index Controller for Jo's Jobs.
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

require __DIR__ . '/../../includes/DatabaseConnection.php';
require __DIR__ . '/../../classes/DatabaseTable.php';
require __DIR__ . '/../../includes/LoadTemplate.php';

session_start();

$category = new DatabaseTable($pdo, 'category', 'id');

if (isset($_POST['submit'])) {
    if ($_POST['password'] === 'letmein') {
        $_SESSION['loggedin'] = true;
    }
}

echo loadTemplate(
    __DIR__ . '/../../templates/layout.html.php', 
    [
        'title' => 'Admin',
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../../templates/admin/index.html.php',
            [
                
            ]
        )
    ]
);
