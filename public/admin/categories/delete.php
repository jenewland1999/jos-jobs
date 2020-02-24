<?php

/**
 * Admin Delete Categories Controller for Jo's Jobs.
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
    $category->delete('id', $_POST['id']);
}

header('location: /admin/categories/');
