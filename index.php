<?php
$db = new PDO('sqlite:' . __DIR__ . '/links.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('CREATE TABLE IF NOT EXISTS links (code TEXT PRIMARY KEY, url TEXT NOT NULL)');

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if($method === 'POST' && $path === '/shorten') {
    // get url
    $input = json_decode(file_get_contents('php://input'), true);
    $url = $input['url'];

    // get code
    $code = bin2hex(random_bytes(4));

    // prep and store
    $statement = $db->prepare('INSERT INTO links (code, url) VALUES (?, ?)');
    $statement->execute([$code, $url]);

    // return code
    header('Content-Type: application/json');
    echo json_encode(['code' => $code]);
} elseif($method === 'GET' && $path !== '/') {
    $code = ltrim($path, '/');
    $statement = $db->prepare('SELECT url FROM links WHERE code = ?');
    $statement->execute([$code]);
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if($row) {
        header('Location: ' . $row['url'], true, 302);
        exit;
    }

    http_response_code(404);
    echo 'Link not found';
} else {
    echo 'shortbeep beckend';
}

echo 'backend up';