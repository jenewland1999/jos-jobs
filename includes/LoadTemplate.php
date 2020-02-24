<?php

/**
 * Load Template function.
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

/**
 * Loads a template file.
 * 
 * Using a relative file path (including filename and extension) load a file 
 * using the object buffer optionally passing in an associative array of 
 * variables required for the template (which will be extracted).
 * 
 * @param string $filePath     The path to the file (including name and ext).
 * @param array  $templateVars An array of variables required for template.
 * 
 * @return string The contents of the template.
 */
function loadTemplate($filePath, $templateVars = []) 
{
    // Extract variables from the $templateVars associative 
    // array if size isn't empty
    if (!empty($templateVars)) {
        extract($templateVars);
    }

    // Start the output buffer
    ob_start();

    // Include the file using the $filePath provided.
    include $filePath;
    
    // Return the contents of that file and clear the buffer.
    return ob_get_clean();
}
