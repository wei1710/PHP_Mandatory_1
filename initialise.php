<?php

/**
* Creates two constants for the application:
*
* - ROOT_PATH: The file system path to the application
*   (e.g., "C:/xampp/htdocs/php_company_employees", "var/www/html/php_company_employees")
* 
* - BASE_URL: The web URL path to the application
*   (e.g., "php_company_employees")
*/

// Fix Windows backslashes (double backslash needed as escape character)
define('ROOT_PATH', str_replace('\\', '/', __DIR__));

// Web server's document root ("C:\xampp\htdocs", "var/www/html" or similar)
$documentRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));

// The base URL is the present script path minus the document root
$baseUrl = str_replace($documentRoot, '', ROOT_PATH);

// As it is an absolute path, it must start with a slash.
// If it already starts with a slash, ltrim removes it before it gets added again
define('BASE_URL', '/' . ltrim($baseUrl, '/'));