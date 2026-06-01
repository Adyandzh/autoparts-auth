<?php

require_once __DIR__ . '/../controllers/UserController.php';

$controller = new UserController();

$method = $_SERVER['REQUEST_METHOD'];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/autoparts-auth/api/v1';

$route = str_replace($basePath, '', $uri);

$route = trim($route, '/');

$parts = explode('/', $route);

$resource = $parts[0] ?? '';
$id = $parts[1] ?? null;

if ($method === 'POST' && $resource === 'register') {
    $controller->register();
    exit;
}

if ($method === 'POST' && $resource === 'login') {
    $controller->login();
    exit;
}

if ($method === 'GET' && $resource === 'users' && $id === null) {
    $controller->getAllUsers();
    exit;
}

if ($method === 'GET' && $resource === 'users' && $id !== null) {
    $controller->getUserById($id);
    exit;
}

if (($method === 'PUT' || $method === 'PATCH') && $resource === 'users' && $id !== null) {
    $controller->updatePassword($id);
    exit;
}

if ($method === 'DELETE' && $resource === 'users' && $id !== null) {
    $controller->deleteUser($id);
    exit;
}

http_response_code(404);

echo json_encode([
    'status' => 'error',
    'message' => 'Маршрут не найден'
], JSON_UNESCAPED_UNICODE);