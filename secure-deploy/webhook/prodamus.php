<?php
declare(strict_types=1);
require_once __DIR__ . '/../_private/bootstrap.php';

function prodamus_normalize(mixed $value): mixed
{
    if (is_array($value)) {
        ksort($value, SORT_STRING);
        foreach ($value as $key => $item) {
            $value[$key] = prodamus_normalize($item);
        }
        return $value;
    }
    if ($value === null) return '';
    if (is_bool($value)) return $value ? '1' : '0';
    return (string)$value;
}

function prodamus_signature(array $payload, string $secret): string
{
    unset($payload['signature']);
    $json = json_encode(prodamus_normalize($payload), JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    return hash_hmac('sha256', (string)$json, $secret);
}

function prodamus_header(string $name): string
{
    foreach (getallheaders() as $key => $value) {
        if (strcasecmp((string)$key, $name) === 0) return trim((string)$value);
    }
    return '';
}

function prodamus_flatten(mixed $value, array &$parts): void
{
    if (is_array($value)) {
        foreach ($value as $item) prodamus_flatten($item, $parts);
    } elseif (is_scalar($value)) {
        $parts[] = (string)$value;
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new RuntimeException('method');
    $raw = file_get_contents('php://input') ?: '';
    $contentType = strtolower((string)($_SERVER['CONTENT_TYPE'] ?? ''));
    $payload = str_contains($contentType, 'application/json') ? json_decode($raw, true) : $_POST;
    if (!is_array($payload) || !$payload) throw new RuntimeException('empty payload');
    $config = alya_config();
    $secret = (string)($config['prodamus_secret'] ?? '');
    if ($secret === '' || str_contains($secret, 'PASTE_')) throw new RuntimeException('not configured');
    $sign = prodamus_header('Sign');
    if ($sign === '' || !hash_equals(prodamus_signature($payload, $secret), $sign)) throw new RuntimeException('bad signature');

    $parts = [];
    prodamus_flatten($payload['products'] ?? [], $parts);
    $haystack = mb_strtoupper(implode(' ', $parts), 'UTF-8');
    $allowed = false;
    foreach ((array)($config['premium_markers'] ?? []) as $marker) {
        if ($marker !== '' && str_contains($haystack, mb_strtoupper((string)$marker, 'UTF-8'))) $allowed = true;
    }
    $ids = array_map('strval', (array)($config['premium_product_ids'] ?? []));
    $candidateIds = array_filter(array_map('strval', [
        $payload['product_id'] ?? null, $payload['payment_link_id'] ?? null,
        $payload['paymentLinkId'] ?? null, $payload['sku'] ?? null,
    ]));
    if ($ids && array_intersect($ids, $candidateIds)) $allowed = true;
    if (!$allowed) {
        http_response_code(200);
        exit('ignored');
    }

    $email = alya_normalize_email((string)($payload['customer_email'] ?? $payload['email'] ?? ''));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new RuntimeException('email missing');
    $orderId = trim((string)($payload['order_id'] ?? $payload['payment_id'] ?? $payload['id'] ?? ''));
    $eventKey = hash('sha256', $orderId . '|' . $email . '|' . ($payload['date'] ?? '') . '|' . $sign);
    $db = alya_db();
    $db->beginTransaction();
    $seen = $db->prepare('SELECT 1 FROM webhook_events WHERE event_key = ?');
    $seen->execute([$eventKey]);
    if (!$seen->fetchColumn()) {
        $now = time();
        $label = implode(' · ', array_slice($parts, 0, 6));
        $upsert = $db->prepare(<<<'SQL'
INSERT INTO buyers(email, order_id, product_label, paid_at, active, created_at, updated_at)
VALUES(?,?,?,?,1,?,?)
ON CONFLICT(email) DO UPDATE SET order_id=excluded.order_id, product_label=excluded.product_label,
active=1, updated_at=excluded.updated_at
SQL);
        $upsert->execute([$email, $orderId, $label, $now, $now, $now]);
        $db->prepare('INSERT INTO webhook_events(event_key, received_at, payload_hash) VALUES(?,?,?)')
           ->execute([$eventKey, $now, hash('sha256', $raw ?: http_build_query($payload))]);
    }
    $db->commit();
    http_response_code(200);
    echo 'success';
} catch (Throwable $e) {
    if (isset($db) && $db instanceof PDO && $db->inTransaction()) $db->rollBack();
    error_log('ALYA Prodamus webhook: ' . $e->getMessage());
    http_response_code(400);
    echo 'error';
}

