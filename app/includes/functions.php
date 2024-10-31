<?php

// Start PHP session
use class\Member;

session_start();

/**
 * Load configuration from includes/config.php
 *
 * @return array
 */
function getConfig(): array {
    static $config = null;

    if (is_null($config)) {
        $config = include('config.php');
    }

    return $config;
}

/**
 * Create a PDO connection and return it
 *
 * @return PDO
 * @throws Exception
 */
function createPdo(): PDO {
    $config = getConfig();

    try {
        $dsn = vsprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8', [
            $config['db']['db_host'],
            $config['db']['db_port'],
            $config['db']['db_name'],
        ]);

        $pdo = new PDO($dsn, $config['db']['db_user'], $config['db']['db_pass'], [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    } catch (\Exception $e) {
        throw new \Exception('Database error: ' . $e->getMessage());
    }

    return $pdo;
}

/**
 * Get the PDO instance
 *
 * @return PDO
 */
function getPdo(): PDO {
    static $pdo = null;

    if (is_null($pdo)) {
        $pdo = createPdo();
    }

    return $pdo;
}

require 'classes/member.php';
$member = new Member();

/**
 * Get the current page from the URL
 *
 * @return array
 */
/**
 * Get the current page from the URL
 *
 * @return array
 */
function pageGetCurrent(): array {
    $config = getConfig();
    $pages = $config['views'];
    $default = $config['default_page'];

    $URI = $_SERVER['REQUEST_URI'];
    $URI = explode('/', filter_var($URI, FILTER_SANITIZE_URL));

//    print_r($URI);

    $page = '/' . implode('/', array_slice($URI, 1)) ?: $default;

//    print_r($page);

    foreach ($pages as $key => $value) {
        if (in_array($page, $value['urlPath'])) {
            return $value;
        }
    }

    return $pages['404'];
}


function getAllMembers(): array {
    $query = getPdo()->query('SELECT * FROM membres');
    $members = $query->fetchAll();

    return $members;
}

function getMemberById($id) {
    $id = (int) $id;
    $query = getPdo()->prepare('SELECT * FROM membres WHERE id = :id');
    $query->execute([
        'id' => $id,
    ]);
    $member = $query->fetch();

    return $member;
}

function deleteMember($id) {
    $id = (int) $id;
    $query = getPdo()->prepare('DELETE FROM `membres` WHERE `id` = :id');
    $query->execute([
        'id' => $id,
    ]);
}

/**
 * Configure the router with pages from the configuration
 *
 * @param \src\Router\Router $router
 */
function configureRouter(\src\Router\Router $router): void {
    $config = getConfig();
    $member = new Member();
    foreach ($config['views'] as $page) {
        foreach ($page['urlPath'] as $path) {
            if (in_array('GET', $page['method'])) {
                $router->get($path, function () use ($page, $member) {
                    $page_title = $page['title'];
                    require_once __DIR__ . $page['route'];
                });
            }
            if (in_array('POST', $page['method'])) {
                $router->post($path, function () use ($page, $member) {
                    require_once __DIR__ . $page['path'];
                });
            }
        }
    }
}

/**
 * Configure the custom 404 handler
 *
 * @param \src\Router\Router $router
 */
function configure404Handler(\src\Router\Router $router): void {
    $router->set404(function () {
        header('Location: /404');
    });
}

// Get current page info
$thispage = pageGetCurrent();

// Redirect to login if the page is protected and the user is not logged in
if ($thispage['protected'] === true && !$member->isLogged()) {
    header('Location: /allLogin');
    exit;
}

if ($thispage['needToBeAdmin'] === true && !$member->isAdmin()) {
    header('Location: /troll');
}   

// Set the page title
$page_title = $thispage['title'];

// Start output buffering
ob_start();

// Capture the content
$page_content = ob_get_contents();

// End output buffering
ob_end_clean();