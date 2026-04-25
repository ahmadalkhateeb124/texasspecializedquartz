<?php /** Expects: $customerMeta */ ?>
<?php if (!empty($customerMeta)): ?>
    <div class="card">
        <div class="card-header">
            <h6 class="card-title"><i class='bx bx-buildings text-primary'></i> Company Info</h6>
            <a href="profile" style="font-size:12px;">Edit</a>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column gap-2" style="font-size:13px;">
                <div>
                    <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Company</div>
                    <div><?= htmlspecialchars($customerMeta['company_name'] ?? '—') ?></div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Email</div>
                    <div><?= htmlspecialchars($customerMeta['email'] ?? '—') ?></div>
                </div>
                <?php if (!empty($customerMeta['phone'])): ?>
                    <div>
                        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Phone</div>
                        <div><?= htmlspecialchars($customerMeta['phone']) ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($customerMeta['address'])): ?>
                    <div>
                        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Address</div>
                        <div><?= htmlspecialchars($customerMeta['address']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
