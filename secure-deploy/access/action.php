<?php
declare(strict_types=1);
require_once __DIR__ . '/../_private/bootstrap.php';
alya_secure_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    alya_redirect('/access/');
}
alya_verify_csrf();
$mode = (string)($_POST['mode'] ?? '');

if ($mode === 'request') {
    $email = alya_normalize_email((string)($_POST['email'] ?? ''));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        alya_flash('error', 'Проверьте, правильно ли указан email.');
        alya_redirect('/access/');
    }
    alya_request_code($email);
    alya_flash('success', 'Если этот email указан в оплаченной покупке, код уже отправлен. Проверьте также папку «Спам».');
    alya_redirect('/access/?step=code&email=' . rawurlencode($email));
}

if ($mode === 'verify') {
    $email = alya_normalize_email((string)($_POST['email'] ?? ''));
    $code = preg_replace('/\D+/', '', (string)($_POST['code'] ?? ''));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^\d{6}$/', $code)) {
        alya_flash('error', 'Введите шестизначный код из письма.');
        alya_redirect('/access/?step=code&email=' . rawurlencode($email));
    }
    $result = alya_verify_code($email, $code);
    if (!empty($result['ok'])) {
        alya_redirect('/premium/');
    }
    alya_flash('error', (string)$result['message']);
    alya_redirect('/access/?step=code&email=' . rawurlencode($email));
}

if ($mode === 'logout') {
    alya_logout();
    alya_redirect('/access/');
}

alya_redirect('/access/');

