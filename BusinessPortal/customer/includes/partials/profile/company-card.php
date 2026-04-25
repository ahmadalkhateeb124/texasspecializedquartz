<?php /** Expects: $profile, $pdo */
$adminData = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([2]);
    $adminData = $stmt->fetch() ?: [];
} catch (PDOException $e) {
}
$supportEmail = $adminData['email'] ?? 'cs@webkoit.com';
$addressParts = array_filter([
    $profile['address']  ?? '',
    $profile['city']     ?? '',
    $profile['state']    ?? '',
    $profile['zip_code'] ?? '',
]);
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-buildings text-primary'></i> Company</h6>
    </div>
    <div class="card-body" style="font-size:13px;">
        <div class="mb-2">
            <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:2px;">Company Name</div>
            <div style="font-weight:600;"><?= htmlspecialchars($profile['company_name'] ?? '—') ?></div>
        </div>
        <div class="mb-2">
            <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:2px;">Phone</div>
            <div style="font-weight:500;"><?= htmlspecialchars($profile['phone'] ?? '—') ?></div>
        </div>
        <div>
            <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:2px;">Address</div>
            <div style="font-weight:500;"><?= htmlspecialchars(implode(', ', $addressParts) ?: '—') ?></div>
        </div>
        <div class="mt-3 pt-2" style="border-top:1px solid var(--border);font-size:11px;color:var(--muted);">
            To update company info,
            <a href="https://mail.google.com/mail/?view=cm&to=<?= urlencode($supportEmail) ?>"
                target="_blank" style="color:var(--primary);">contact support</a>
        </div>
    </div>
</div>
