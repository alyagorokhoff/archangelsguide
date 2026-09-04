<?php
declare(strict_types=1);
require_once __DIR__ . '/../_private/bootstrap.php';
alya_secure_headers();
if (alya_current_buyer()) {
    alya_redirect('/premium/');
}
$flash = alya_take_flash();
$step = (($_GET['step'] ?? '') === 'code') ? 'code' : 'email';
$email = alya_normalize_email((string)($_GET['email'] ?? ''));
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <meta name="robots" content="noindex,nofollow">
  <title>Вход в PREMIUM · ALYA</title>
  <link rel="stylesheet" href="/assets/access.css?v=1">
</head>
<body>
<main class="access-shell">
  <a class="brand" href="/"><span>✦</span> ALYA</a>
  <section class="access-card">
    <p class="eyebrow">ЗАКРЫТОЕ ПРОСТРАНСТВО</p>
    <h1>PREMIUM-доступ</h1>
    <p class="lead">Живая колода и полный гайд открываются только для покупателей.</p>
    <?php if ($flash): ?><div class="notice <?= alya_escape($flash['type']) ?>"><?= alya_escape($flash['message']) ?></div><?php endif; ?>
    <?php if ($step === 'email'): ?>
      <form method="post" action="/access/action.php" autocomplete="on">
        <input type="hidden" name="csrf" value="<?= alya_escape(alya_csrf()) ?>">
        <input type="hidden" name="mode" value="request">
        <label for="email">Email, указанный при оплате</label>
        <input id="email" name="email" type="email" inputmode="email" autocomplete="email" required placeholder="name@example.com">
        <button type="submit">Получить код</button>
      </form>
    <?php else: ?>
      <form method="post" action="/access/action.php" autocomplete="one-time-code">
        <input type="hidden" name="csrf" value="<?= alya_escape(alya_csrf()) ?>">
        <input type="hidden" name="mode" value="verify">
        <input type="hidden" name="email" value="<?= alya_escape($email) ?>">
        <label for="code">Код из письма</label>
        <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required placeholder="000000">
        <button type="submit">Открыть PREMIUM</button>
      </form>
      <a class="quiet-link" href="/access/">Указать другой email</a>
    <?php endif; ?>
    <div class="details"><span>10 минут</span><span>5 попыток</span><span>до 2 устройств</span></div>
    <p class="support">Вход сохранится на 90 дней. Затем можно бесплатно получить новый код на тот же email.</p>
  </section>
</main>
<script src="/protect-content.js?v=1"></script>
</body>
</html>

