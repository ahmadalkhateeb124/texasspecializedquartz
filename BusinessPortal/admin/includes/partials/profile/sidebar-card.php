<?php /** Expects: $admin, $initials, $totalOrders, $totalCustomers, $totalProducts */
$hasAvatar = !empty($admin['avatar']) && file_exists(__DIR__ . '/../../../../auth/uploads/' . $admin['avatar']);
?>
<div class="card mb-4">
    <div style="height:150px;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-h));
                border-radius:var(--radius-md) var(--radius-md) 0 0;"></div>

    <div class="card-section" style="padding-top:0;text-align:center;background-color:#FCFCFC;">
        <div style="margin-top:-36px;margin-bottom:12px;">
            <?php if ($hasAvatar): ?>
                <img src="../auth/uploads/<?= htmlspecialchars($admin['avatar']) ?>"
                    style="box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
                           width:72px;height:72px;border-radius:50%;
                           border:3px solid var(--color-surface);object-fit:cover;" alt="Avatar">
            <?php else: ?>
                <div style="width:72px;height:72px;border-radius:50%;background:#8b6f4e;
                            border:3px solid var(--color-surface);
                            display:inline-flex;align-items:center;justify-content:center;
                            color:#fff;font-size:26px;font-weight:700;">
                    <?= htmlspecialchars($initials) ?>
                </div>
            <?php endif; ?>
        </div>

        <h5 style="font-weight:700;margin:0 0 3px;"><?= htmlspecialchars($admin['fullname'] ?? '—') ?></h5>
        <p style="font-size:13px;color:var(--color-text-sub);margin:0 0 6px;">
            @<?= htmlspecialchars($admin['username'] ?? '') ?>
        </p>
        <span class="badge badge-success"><span class="dot"></span>Administrator</span>

        <hr style="border-color:var(--color-border);margin:16px 0;">

        <div class="row g-0 text-center">
            <div class="col">
                <div style="font-size:22px;font-weight:800;color:var(--color-text);"><?= $totalOrders ?></div>
                <div style="font-size:11px;color:var(--color-text-sub);font-weight:500;">Orders</div>
            </div>
            <div class="col" style="border-left:1px solid var(--color-border);">
                <div style="font-size:22px;font-weight:800;color:var(--color-text);"><?= $totalCustomers ?></div>
                <div style="font-size:11px;color:var(--color-text-sub);font-weight:500;">Customers</div>
            </div>
            <div class="col" style="border-left:1px solid var(--color-border);">
                <div style="font-size:22px;font-weight:800;color:var(--color-text);"><?= $totalProducts ?></div>
                <div style="font-size:11px;color:var(--color-text-sub);font-weight:500;">Remnants</div>
            </div>
        </div>

        <hr style="border-color:var(--color-border);margin:16px 0;">

        <p style="font-size:12px;color:var(--color-text-sub);margin:0;">
            Member since <?= !empty($admin['created_at']) ? date('M Y', strtotime($admin['created_at'])) : '—' ?>
        </p>
    </div>
</div>
