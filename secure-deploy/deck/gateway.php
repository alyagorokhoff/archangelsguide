<?php
declare(strict_types=1);
require_once __DIR__ . '/../_private/bootstrap.php';
alya_require_buyer();
$requested = rawurldecode((string)($_GET['path'] ?? 'index.html'));
$requested = ltrim(str_replace('\\', '/', $requested), '/');
if ($requested === '' || str_contains($requested, '..') || str_contains($requested, "\0")) {
    http_response_code(400);
    exit('Некорректный путь.');
}
$base = realpath(ALYA_PRIVATE . '/content/deck');
$file = realpath(ALYA_PRIVATE . '/content/deck/' . $requested);
if (!$base || !$file || !str_starts_with($file, $base . DIRECTORY_SEPARATOR) || !is_file($file)) {
    http_response_code(404);
    exit('Файл не найден.');
}
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$types = [
    'html' => 'text/html; charset=UTF-8', 'css' => 'text/css; charset=UTF-8',
    'js' => 'application/javascript; charset=UTF-8', 'json' => 'application/json; charset=UTF-8',
    'svg' => 'image/svg+xml', 'webp' => 'image/webp', 'png' => 'image/png',
    'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'woff2' => 'font/woff2',
];
header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
header('Content-Length: ' . filesize($file));
header('Cache-Control: private, max-age=3600');
header('X-Content-Type-Options: nosniff');
readfile($file);

