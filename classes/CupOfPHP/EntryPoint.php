<?php

namespace CupOfPHP;

/**
 * EntryPoint class.
 *
 * @package  CupOfTea
 * @author   Jordan Newland <github@jenewland.me.uk>
 * @license  All Rights Reserved
 * @link     https://github.com/jenewland1999/
 */
class EntryPoint
{
    private $pdo;
    private $route;
    private $method;
    private $routes;

    /**
     * EntryPoint constructor.
     *
     * @param string $route The current route the user is on.
     */
    public function __construct(\PDO $pdo, string $route, string $method, \CupOfPHP\IRoutes $routes)
    {
        $this->pdo = $pdo;
        $this->route = $route;
        $this->method = $method;
        $this->routes = $routes;
        $this->checkUrl();
    }

    /**
     * Checks a given route is lowercase and if not converts it to lowercase.
     *
     * @return null
     */
    private function checkUrl()
    {
        if ($this->route !== strtolower($this->route)) {
            http_response_code(301);
            header('location: ' . strtolower($this->route));
        }
    }

    /**
     * Loads a template file.
     *
     * Using a relative file path (including filename and extension) load a file
     * using the object buffer optionally passing in an associative array of
     * variables required for the template (which will be extracted).
     *
     * @param string $filename     The filename of the template (inc. ext).
     * @param array  $templateVars An array of variables required for template.
     *
     * @return string The contents of the template.
     */
    private function loadTemplate($filename, $templateVars = [])
    {
        // Extract variables from the $templateVars associative
        // array if size isn't empty
        if (!empty($templateVars)) {
            extract($templateVars);
        }

        // Start the output buffer
        ob_start();

        // Include the file using the $filePath provided.
        include __DIR__ . '/../../templates/' . $filename;

        // Return the contents of that file and clear the buffer.
        return ob_get_clean();
    }

    public function run()
    {
        $routes = $this->routes->getRoutes();
        $authentication = $this->routes->getAuthentication();

        if (
            isset($routes[$this->route]['login']) &&
            !$authentication->isLoggedIn()
        ) {
            http_response_code(403);
            header('location: /403');
        } elseif (
            isset($routes[$this->route]['permissions']) &&
            !$this->routes->checkPermission($routes[$this->route]['permissions'])
        ) {
            http_response_code(403);
            header('location: /403');
        } else {
            $controller = $routes[$this->route][$this->method]['controller'];
            $action = $routes[$this->route][$this->method]['action'];
            $page = $controller->$action();

            $title = $page['title'];

            if (isset($page['variables'])) {
                $output = $this->loadTemplate($page['template'], $page['variables']);
            } else {
                $output = $this->loadTemplate($page['template']);
            }

            echo $this->loadTemplate(
                'layout.html.php',
                $this->routes->getLayoutVars($title, $output)
            );
        }
    }
}
