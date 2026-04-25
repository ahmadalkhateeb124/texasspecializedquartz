<?php
/**
 * parts/contact-spam-fields.php
 * Shared spam-protection fields for every public contact form.
 * Include INSIDE each <form> on the site.
 *
 * Mechanisms:
 *   1. Honeypot — invisible field; only bots fill it.
 *   2. Time trap — submission < 3s after page load is a bot.
 *   3. Token — random per-form token tied to the session, not the user.
 */

if (session_status() === PHP_SESSION_NONE) {
    require_once __DIR__ . '/../inc/conn.php';
}

if (empty($_SESSION['contact_token'])) {
    $_SESSION['contact_token'] = bin2hex(random_bytes(16));
}
$contactToken = $_SESSION['contact_token'];
$loadedAt     = time();
?>
<!-- Spam protection — do not modify -->
<div aria-hidden="true" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">
    <label>Website (leave empty)
        <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
    </label>
</div>
<input type="hidden" name="loaded_at" value="<?= $loadedAt ?>">
<input type="hidden" name="contact_token" value="<?= htmlspecialchars($contactToken, ENT_QUOTES, 'UTF-8') ?>">
