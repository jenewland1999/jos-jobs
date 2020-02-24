<?php

/**
 * Admin Delete Jobs Controller for Jo's Jobs.
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

$job = new DatabaseTable($pdo, 'job', 'id');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $job->delete('id', $_POST['id']);
}

header('location: /admin/jobs/');
