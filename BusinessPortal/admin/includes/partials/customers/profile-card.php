<?php /** Expects: $company, $account, $status */
$initials  = strtoupper(substr($company['company_name'] ?? 'C', 0, 2));
$badgeHtml = match ($status) {
    'Active'      => '<span class="badge badge-success"><span class="dot"></span>Active</span>',
    'Blacklisted' => '<span class="badge badge-critical"><span class="dot"></span>Blacklisted</span>',
    default       => '<span class="badge badge-neutral"><span class="dot"></span>Inactive</span>',
};
?>
<div class="card mb-4">
    <div class="card-section text-center" style="padding-top:2rem;">
        <div style="width:72px;height:72px;border-radius:50%;background:#000000;
                    display:flex;align-items:center;justify-content:center;
                    color:#fff;font-size:28px;font-weight:700;margin:0 auto 12px;">
            <?= htmlspecialchars($initials) ?>
        </div>
        <h5 style="margin:0 0 4px;font-weight:700;"><?= htmlspecialchars($company['company_name']) ?></h5>
        <p style="color:var(--color-text-sub);font-size:13px;margin:0 0 12px;">
            <?= htmlspecialchars($company['contact_position'] ?? 'Company') ?>
        </p>
        <?= $badgeHtml ?>
    </div>
    <div class="card-section">
        <div class="resource-item" style="padding:10px 0;border-bottom:1px solid var(--color-border);">
            <div style="font-size:12px;color:var(--color-text-sub);">Contact Person</div>
            <div style="font-weight:500;"><?= htmlspecialchars($company['contact_name'] ?? '—') ?></div>
        </div>
        <div class="resource-item" style="padding:10px 0;border-bottom:1px solid var(--color-border);">
            <div style="font-size:12px;color:var(--color-text-sub);">Phone</div>
            <div><?= htmlspecialchars($company['phone'] ?? '—') ?></div>
        </div>
        <div class="resource-item" style="padding:10px 0;border-bottom:1px solid var(--color-border);">
            <div style="font-size:12px;color:var(--color-text-sub);">Email</div>
            <div><?= htmlspecialchars($company['email'] ?? '—') ?></div>
        </div>
        <div class="resource-item" style="padding:10px 0;">
            <div style="font-size:12px;color:var(--color-text-sub);">Member Since</div>
            <div><?= date('M j, Y', strtotime($company['created_at'])) ?></div>
        </div>
    </div>
</div>
