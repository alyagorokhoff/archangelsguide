<?php
declare(strict_types=1);
require_once __DIR__ . '/../_private/bootstrap.php';
alya_require_buyer();
$file = ALYA_PRIVATE . '/content/guide-premium.pdf';
if (!is_file($file)) {
    http_response_code(404);
    exit('Гайд пока недоступен.');
}
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="ALYA-PREMIUM.pdf"');
header('Content-Length: ' . filesize($file));
header('Cache-Control: private, no-store, max-age=0');
header('X-Content-Type-Options: nosniff');
readfile($file);

