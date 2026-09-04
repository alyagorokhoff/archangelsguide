<?php
declare(strict_types=1);
require_once __DIR__ . '/../_private/bootstrap.php';
alya_secure_headers();
$buyer = alya_require_buyer();
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <meta name="robots" content="noindex,nofollow">
  <title>PREMIUM · ALYA</title>
  <link rel="stylesheet" href="/assets/access.css?v=1">
</head>
<body>
<main class="premium-shell">
  <header><a class="brand" href="/"><span>✦</span> ALYA</a><form method="post" action="/access/action.php"><input type="hidden" name="csrf" value="<?= alya_escape(alya_csrf()) ?>"><input type="hidden" name="mode" value="logout"><button class="logout" type="submit">Выйти</button></form></header>
  <section class="welcome">
    <p class="eyebrow">ТВОЁ ЗАКРЫТОЕ ПРОСТРАНСТВО</p>
    <h1>Выбери, что нужно тебе сегодня</h1>
    <p>Доступ сохранён на этом устройстве. Возвращайся по этой странице в любой момент.</p>
  </section>
  <section class="premium-grid">
    <a class="premium-tile deck-tile" href="/deck/"><span class="tile-icon">✦</span><small>ИНТЕРАКТИВНАЯ ПРАКТИКА</small><h2>Живая колода</h2><p>Задай вопрос и открой личное послание дня.</p><b>Открыть колоду →</b></a>
    <a class="premium-tile guide-tile" href="/premium/guide.php"><span class="tile-icon">◈</span><small>ПОЛНАЯ ВЕРСИЯ</small><h2>PREMIUM-гайд</h2><p>Практики, настройки и опоры семи Архангелов.</p><b>Открыть гайд →</b></a>
  </section>
  <p class="account-note">Доступ открыт для <?= alya_escape((string)$buyer['email']) ?></p>
</main>
<script src="/protect-content.js?v=1"></script>
</body>
</html>

