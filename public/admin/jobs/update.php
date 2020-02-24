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
$job = new DatabaseTable($pdo, 'job', 'id');

$templateVars = [
    'categories' => $category->findAll(),
    'isLoggedIn' => false,
    'message' => ''
];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $templateVars['isLoggedIn'] = true;

    if (isset($_GET['id'])) {
        if (isset($_POST['submit'])) {
            if ($_POST['job']['id'] !== '') {
                $job->update($_POST['job']);
                $templateVars['message'] = 'Job updated';
                $templateVars['job'] = $job->find('id', $_GET['id'] ?? '')[0];
            } else {
                $templateVars['message'] = 'Job ID not specified';
                $templateVars['job'] = $job->find('id', $_GET['id'] ?? '')[0];
            }
        } else {
            $templateVars['job'] = $job->find('id', $_GET['id'] ?? '')[0];
            $templateVars['message'] = '';
        }
    } else {
        $templateVars['message'] = 'Job not found';
    }
} else {
    $templateVars['isLoggedIn'] = false;
}

echo loadTemplate(
    __DIR__ . '/../../../templates/layout.html.php', 
    [
        'title' => 'Admin - Jobs - Update',
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../../../templates/admin/jobs/update.html.php',
            $templateVars
        )
    ]
);
