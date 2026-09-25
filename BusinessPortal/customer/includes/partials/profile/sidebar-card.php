<?php /** Expects: $profile, $currentUser, $orderCount, $initials */ ?>
<div class="card mb-4">
    <div style="height:80px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));
                border-radius:var(--radius-md) var(--radius-md) 0 0;"></div>

    <div class="card-body" style="padding-top:0;text-align:center;">
        <div style="margin-top:-36px;margin-bottom:12px;">
            <div style="width:72px;height:72px;border-radius:50%;background:#000000;
                        border:3px solid var(--surface);display:inline-flex;align-items:center;
                        justify-content:center;color:#fff;font-size:26px;font-weight:700;margin:0 auto;">
                <?= htmlspecialchars($initials) ?>
            </div>
        </div>

        <h5 style="font-weight:700;margin:0 0 3px;">
            <?= htmlspecialchars($profile['name'] ?? $currentUser['name'] ?? '—') ?>
        </h5>
        <p style="font-size:13px;color:var(--muted);margin:0 0 6px;">
            <?= htmlspecialchars($profile['email'] ?? $currentUser['email'] ?? '') ?>
        </p>
        <span class="badge <?= ($profile['status'] ?? '') === 'Active' ? 'badge-success' : 'badge-muted' ?>">
            <span class="dot"></span><?= htmlspecialchars($profile['status'] ?? 'Active') ?>
        </span>

        <hr style="border-color:var(--border);margin:16px 0;">

        <div class="row g-0 text-center">
            <div class="col">
                <div style="font-size:22px;font-weight:800;color:var(--text);"><?= $orderCount ?></div>
                <div style="font-size:11px;color:var(--muted);font-weight:500;">Orders</div>
            </div>
            <div class="col" style="border-left:1px solid var(--border);">
                <div style="font-size:14px;font-weight:700;color:var(--text);">
                    <?= !empty($profile['created_at']) ? date('M Y', strtotime($profile['created_at'])) : '—' ?>
                </div>
                <div style="font-size:11px;color:var(--muted);font-weight:500;">Member Since</div>
            </div>
        </div>

        <hr style="border-color:var(--border);margin:16px 0;">

        <a href="orders" class="btn btn-primary w-100 btn-sm mb-2">
            <i class='bx bx-file'></i> View My Orders
        </a>
        <a href="order-new" class="btn btn-light w-100 btn-sm">
            <i class='bx bx-plus'></i> New Order
        </a>
    </div>
</div>
