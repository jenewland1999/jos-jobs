<?php

/**
 * Admin Create Jobs Controller for Jo's Jobs.
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
$job = new DatabaseTable($pdo, 'job', 'id');

$templateVars = [
    'categories' => $category->findAll(),
    'isLoggedIn' => false,
    'message' => ''
];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $templateVars['isLoggedIn'] = true;

    if (isset($_POST['submit'])) {
        $job->insert($_POST['job']);
        $templateVars['message'] = 'Job added';
    } else {
        $templateVars['message'] = '';
    }
} else {
    $templateVars['isLoggedIn'] = false;
}

echo loadTemplate(
    __DIR__ . '/../../../templates/layout.html.php', 
    [
        'title' => 'Admin - Jobs - Create',
        'categories' => $job->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../../../templates/admin/jobs/create.html.php',
            $templateVars
        )
    ]
);
