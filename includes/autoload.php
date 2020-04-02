<?php

/**
 * Autoloader.
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

spl_autoload_register(function ($class) {
    $fileName = str_replace('\\', '/', $class) . '.php';
    $file = __DIR__ . '/../classes/' . $fileName;
    include $file;
});
