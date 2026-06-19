<?php
require_once __DIR__ . '/app/LinkController.php';

$db = new PDO('sqlite:' . __DIR__ . '/links.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('CREATE TABLE IF NOT EXISTS links (code TEXT PRIMARY KEY, url TEXT NOT NULL)');

$controller = new LinkController($db);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if($method === 'POST' && $path === '/shorten') {
    $input = json_decode(file_get_contents('php://input'), true);
    $url = $input['url'];

    if(!isset($input['url']) || $input['url'] === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Missing url']);
        exit;
    }

    $code = $controller->shorten($url);

    header('Content-Type: application/json');
    echo json_encode(['code' => $code]);
} elseif($method === 'GET' && $path !== '/') {
    $url = $controller->unwrap(ltrim($path, '/'));
    if($url) {
        header('Location: ' . $url, true, 302);
        exit;
    }

    http_response_code(404);
    echo json_encode(['error' => 'Unable to resolve url']);
} else {
    // homepage ig
}