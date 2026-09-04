<?php
declare(strict_types=1);

const ALYA_ROOT = __DIR__ . '/..';
const ALYA_PRIVATE = __DIR__;
const ALYA_DB = ALYA_PRIVATE . '/access.sqlite';
const ALYA_SESSION_SECONDS = 7776000; // 90 days
const ALYA_OTP_SECONDS = 600; // 10 minutes
const ALYA_OTP_MAX_ATTEMPTS = 5;
const ALYA_MAX_DEVICES = 2;

function alya_config(): array
{
    static $config;
    if ($config !== null) {
        return $config;
    }
    $path = ALYA_PRIVATE . '/config.php';
    if (!is_file($path)) {
        throw new RuntimeException('Protected access is not configured.');
    }
    $config = require $path;
    if (!is_array($config) || empty($config['app_secret'])) {
        throw new RuntimeException('Protected access configuration is invalid.');
    }
    return $config;
}

function alya_db(): PDO
{
    static $db;
    if ($db instanceof PDO) {
        return $db;
    }
    if (!is_dir(ALYA_PRIVATE)) {
        mkdir(ALYA_PRIVATE, 0700, true);
    }
    $db = new PDO('sqlite:' . ALYA_DB, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $db->exec('PRAGMA journal_mode=WAL; PRAGMA foreign_keys=ON; PRAGMA busy_timeout=5000;');
    $db->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS buyers (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT NOT NULL UNIQUE,
  order_id TEXT,
  product_label TEXT,
  paid_at INTEGER NOT NULL,
  active INTEGER NOT NULL DEFAULT 1,
  created_at INTEGER NOT NULL,
  updated_at INTEGER NOT NULL
);
CREATE TABLE IF NOT EXISTS otp_codes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT NOT NULL,
  code_hash TEXT NOT NULL,
  expires_at INTEGER NOT NULL,
  attempts INTEGER NOT NULL DEFAULT 0,
  used_at INTEGER,
  requested_at INTEGER NOT NULL,
  ip_hash TEXT
);
CREATE INDEX IF NOT EXISTS otp_email_idx ON otp_codes(email, requested_at DESC);
CREATE TABLE IF NOT EXISTS devices (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  buyer_id INTEGER NOT NULL,
  device_hash TEXT NOT NULL,
  created_at INTEGER NOT NULL,
  last_seen INTEGER NOT NULL,
  revoked_at INTEGER,
  UNIQUE(buyer_id, device_hash),
  FOREIGN KEY(buyer_id) REFERENCES buyers(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS access_tokens (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  buyer_id INTEGER NOT NULL,
  device_id INTEGER NOT NULL,
  token_hash TEXT NOT NULL UNIQUE,
  expires_at INTEGER NOT NULL,
  created_at INTEGER NOT NULL,
  last_seen INTEGER NOT NULL,
  revoked_at INTEGER,
  FOREIGN KEY(buyer_id) REFERENCES buyers(id) ON DELETE CASCADE,
  FOREIGN KEY(device_id) REFERENCES devices(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS access_token_idx ON access_tokens(token_hash);
CREATE TABLE IF NOT EXISTS webhook_events (
  event_key TEXT PRIMARY KEY,
  received_at INTEGER NOT NULL,
  payload_hash TEXT NOT NULL
);
SQL);
    return $db;
}

function alya_secure_headers(): void
{
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: same-origin');
    header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data: blob:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; font-src 'self' data:; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");
}

function alya_start_form_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_name('alya_form');
    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function alya_csrf(): string
{
    alya_start_form_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['csrf'];
}

function alya_verify_csrf(): void
{
    alya_start_form_session();
    $given = (string)($_POST['csrf'] ?? '');
    if (!$given || empty($_SESSION['csrf']) || !hash_equals((string)$_SESSION['csrf'], $given)) {
        http_response_code(419);
        exit('Сессия формы истекла. Обновите страницу.');
    }
}

function alya_normalize_email(string $email): string
{
    return mb_strtolower(trim($email), 'UTF-8');
}

function alya_hash(string $value): string
{
    return hash_hmac('sha256', $value, (string)alya_config()['app_secret']);
}

function alya_cookie(string $name, string $value, int $expires, bool $httpOnly = true): void
{
    setcookie($name, $value, [
        'expires' => $expires,
        'path' => '/',
        'secure' => true,
        'httponly' => $httpOnly,
        'samesite' => 'Lax',
    ]);
}

function alya_redirect(string $path): never
{
    header('Location: ' . $path, true, 303);
    exit;
}

function alya_flash(string $type, string $message): void
{
    alya_start_form_session();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function alya_take_flash(): ?array
{
    alya_start_form_session();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_array($flash) ? $flash : null;
}

function alya_find_active_buyer(string $email): ?array
{
    $stmt = alya_db()->prepare('SELECT * FROM buyers WHERE email = ? AND active = 1 LIMIT 1');
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function alya_send_code(string $email, string $code): bool
{
    $config = alya_config();
    $subject = '=?UTF-8?B?' . base64_encode('Код входа в PREMIUM ALYA') . '?=';
    $body = "Ваш код входа: {$code}\n\nОн действует 10 минут. Доступ сохранится на этом устройстве на 90 дней.\n\nЕсли вы не запрашивали код, просто проигнорируйте письмо.";
    $fromName = '=?UTF-8?B?' . base64_encode((string)$config['mail_from_name']) . '?=';
    $headers = [
        'From: ' . $fromName . ' <' . $config['mail_from'] . '>',
        'Reply-To: ' . $config['mail_from'],
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
    ];
    return mail($email, $subject, $body, implode("\r\n", $headers));
}

function alya_request_code(string $email): void
{
    $db = alya_db();
    $now = time();
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    $ipHash = alya_hash('ip:' . $ip);
    $recent = $db->prepare('SELECT COUNT(*) FROM otp_codes WHERE (email = ? OR ip_hash = ?) AND requested_at > ?');
    $recent->execute([$email, $ipHash, $now - 3600]);
    if ((int)$recent->fetchColumn() >= 8) {
        return;
    }
    if (!alya_find_active_buyer($email)) {
        return;
    }
    $code = (string)random_int(100000, 999999);
    $db->prepare('UPDATE otp_codes SET used_at = ? WHERE email = ? AND used_at IS NULL')->execute([$now, $email]);
    $stmt = $db->prepare('INSERT INTO otp_codes(email, code_hash, expires_at, requested_at, ip_hash) VALUES(?,?,?,?,?)');
    $stmt->execute([$email, password_hash($code, PASSWORD_DEFAULT), $now + ALYA_OTP_SECONDS, $now, $ipHash]);
    if (!alya_send_code($email, $code)) {
        error_log('ALYA: failed to send OTP email');
    }
}

function alya_verify_code(string $email, string $code): array
{
    $db = alya_db();
    $stmt = $db->prepare('SELECT * FROM otp_codes WHERE email = ? AND used_at IS NULL ORDER BY requested_at DESC LIMIT 1');
    $stmt->execute([$email]);
    $otp = $stmt->fetch();
    if (!$otp || (int)$otp['expires_at'] < time() || (int)$otp['attempts'] >= ALYA_OTP_MAX_ATTEMPTS) {
        return ['ok' => false, 'message' => 'Код истёк. Запросите новый.'];
    }
    $attempts = (int)$otp['attempts'] + 1;
    $db->prepare('UPDATE otp_codes SET attempts = ? WHERE id = ?')->execute([$attempts, $otp['id']]);
    if (!password_verify($code, (string)$otp['code_hash'])) {
        $left = max(0, ALYA_OTP_MAX_ATTEMPTS - $attempts);
        return ['ok' => false, 'message' => $left ? "Неверный код. Осталось попыток: {$left}." : 'Попытки закончились. Запросите новый код.'];
    }
    $buyer = alya_find_active_buyer($email);
    if (!$buyer) {
        return ['ok' => false, 'message' => 'Доступ для этого email не найден.'];
    }
    $deviceRaw = (string)($_COOKIE['alya_device'] ?? '');
    if (!preg_match('/^[a-f0-9]{64}$/', $deviceRaw)) {
        $deviceRaw = bin2hex(random_bytes(32));
    }
    $deviceHash = alya_hash('device:' . $deviceRaw);
    $deviceStmt = $db->prepare('SELECT * FROM devices WHERE buyer_id = ? AND device_hash = ? AND revoked_at IS NULL LIMIT 1');
    $deviceStmt->execute([$buyer['id'], $deviceHash]);
    $device = $deviceStmt->fetch();
    if (!$device) {
        $countStmt = $db->prepare('SELECT COUNT(*) FROM devices WHERE buyer_id = ? AND revoked_at IS NULL');
        $countStmt->execute([$buyer['id']]);
        if ((int)$countStmt->fetchColumn() >= ALYA_MAX_DEVICES) {
            return ['ok' => false, 'message' => 'Уже подключено два устройства. Напишите в поддержку, чтобы заменить одно из них.'];
        }
        $now = time();
        $db->prepare('INSERT INTO devices(buyer_id, device_hash, created_at, last_seen) VALUES(?,?,?,?)')->execute([$buyer['id'], $deviceHash, $now, $now]);
        $deviceId = (int)$db->lastInsertId();
    } else {
        $deviceId = (int)$device['id'];
    }
    $token = bin2hex(random_bytes(32));
    $now = time();
    $db->prepare('UPDATE access_tokens SET revoked_at = ? WHERE buyer_id = ? AND device_id = ? AND revoked_at IS NULL')->execute([$now, $buyer['id'], $deviceId]);
    $db->prepare('INSERT INTO access_tokens(buyer_id, device_id, token_hash, expires_at, created_at, last_seen) VALUES(?,?,?,?,?,?)')
       ->execute([$buyer['id'], $deviceId, alya_hash('token:' . $token), $now + ALYA_SESSION_SECONDS, $now, $now]);
    $db->prepare('UPDATE otp_codes SET used_at = ? WHERE id = ?')->execute([$now, $otp['id']]);
    alya_cookie('alya_device', $deviceRaw, $now + 31536000, true);
    alya_cookie('alya_access', $token, $now + ALYA_SESSION_SECONDS, true);
    return ['ok' => true];
}

function alya_current_buyer(): ?array
{
    $token = (string)($_COOKIE['alya_access'] ?? '');
    if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
        return null;
    }
    $stmt = alya_db()->prepare(<<<'SQL'
SELECT b.*, t.id AS token_id, t.expires_at AS token_expires, d.id AS device_id
FROM access_tokens t
JOIN buyers b ON b.id = t.buyer_id
JOIN devices d ON d.id = t.device_id
WHERE t.token_hash = ? AND t.revoked_at IS NULL AND t.expires_at > ?
  AND b.active = 1 AND d.revoked_at IS NULL
LIMIT 1
SQL);
    $stmt->execute([alya_hash('token:' . $token), time()]);
    $buyer = $stmt->fetch();
    if (!$buyer) {
        return null;
    }
    $now = time();
    alya_db()->prepare('UPDATE access_tokens SET last_seen = ? WHERE id = ?')->execute([$now, $buyer['token_id']]);
    alya_db()->prepare('UPDATE devices SET last_seen = ? WHERE id = ?')->execute([$now, $buyer['device_id']]);
    return $buyer;
}

function alya_require_buyer(): array
{
    $buyer = alya_current_buyer();
    if (!$buyer) {
        alya_flash('info', 'Войдите по email, который указали при оплате.');
        alya_redirect('/access/');
    }
    return $buyer;
}

function alya_logout(): void
{
    $token = (string)($_COOKIE['alya_access'] ?? '');
    if ($token) {
        alya_db()->prepare('UPDATE access_tokens SET revoked_at = ? WHERE token_hash = ?')->execute([time(), alya_hash('token:' . $token)]);
    }
    alya_cookie('alya_access', '', time() - 3600, true);
}

function alya_escape(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

