<?php

/**
 * Attempt to establish a database connection using PHP PDO extension.
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

// Define the database type e.g. mysql, oci (Oracle DB), etc.
define('DB_TYPE', 'mysql');

// Define the database hostname
define('DB_HOSTNAME', 'localhost');

// Define the database name or schema
define('DB_NAME', 'josjobs');

// Define the database character set
define('DB_CHARSET', 'utf8');

// Define the database login username
define('DB_USERNAME', 'student');

// Define the database login password
define('DB_PASSWORD', 'student');

// Controls whether or not database errors are shown to the user
// ! DON'T USE IN PRODUCTION
define('DB_DEV_MODE', true);

// Attempt to instantiate the PDO class and store it under the variable $pdo
try {
    $pdo = new PDO(
        DB_TYPE . ':host=' .
            DB_HOSTNAME . ';dbname=' .
            DB_NAME . ';charset=' .
            DB_CHARSET,
        DB_USERNAME,
        DB_PASSWORD
    );

    // If dev mode is enabled log errors as exceptions
    if (DB_DEV_MODE) {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    // Terminate execution of script with an error message and exception
    exit(
        'An error occurred attempting to create a database connection.' .
        'Exiting...\n' . $e->getMessage()
    );
}
