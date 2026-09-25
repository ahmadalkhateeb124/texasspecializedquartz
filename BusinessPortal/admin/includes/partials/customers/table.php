<?php
/** Expects: $customers */
$statusBadge = function (string $s): string {
    return match ($s) {
        'Active'      => '<span class="badge badge-success"><span class="dot"></span>Active</span>',
        'Blacklisted' => '<span class="badge badge-critical"><span class="dot"></span>Blacklisted</span>',
        default       => '<span class="badge badge-neutral"><span class="dot"></span>Inactive</span>',
    };
};
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-group text-primary'></i> All Customers</h6>
        <div class="d-flex gap-2 align-items-center">
            <div class="input-group" style="width:220px;">
                <span class="input-group-text" style="border-right:none;">
                    <i class='bx bx-search' style="font-size:15px;"></i>
                </span>
                <input type="text" id="customerSearch" class="form-control"
                    placeholder="Search customers…" style="border-left:none;">
            </div>
            <select id="statusFilter" class="form-select" style="width:150px;">
                <option value="">All Statuses</option>
                <option>Active</option>
                <option>Inactive</option>
                <option>Blacklisted</option>
            </select>
        </div>
    </div>

    <?php if (empty($customers)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class='bx bx-group'></i></div>
            <p class="empty-state-title">No customers yet</p>
            <p class="empty-state-desc">Add your first customer to get started.</p>
            <a href="customers-new" class="btn btn-primary btn-sm">
                <i class='bx bx-plus'></i> Add Customer
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="customersTable">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Added</th>
                        <th class="text-end" style="width:90px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="customersTbody">
                    <?php foreach ($customers as $c):
                        $status   = $c['account_status'] ?? 'Inactive';
                        $initials = strtoupper(substr($c['company_name'] ?? 'C', 0, 2));
                        $location = implode(', ', array_filter([$c['city'] ?? '', $c['state'] ?? '']));
                    ?>
                        <tr data-status="<?= htmlspecialchars($status) ?>">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:34px;height:34px;border-radius:50%;background:#000000;
                                                display:flex;align-items:center;justify-content:center;
                                                color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                                        <?= htmlspecialchars($initials) ?>
                                    </div>
                                    <div>
                                        <a href="customers-view?id=<?= $c['id'] ?>"
                                            style="font-weight:600;color:var(--color-text);">
                                            <?= htmlspecialchars($c['company_name']) ?>
                                        </a>
                                        <div style="font-size:11px;color:var(--color-text-sub);">ID #<?= $c['id'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($c['contact_name'] ?? '—') ?></div>
                                <?php if (!empty($c['contact_position'])): ?>
                                    <div style="font-size:11px;color:var(--color-text-sub);">
                                        <?= htmlspecialchars($c['contact_position']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
                            <td style="color:var(--color-text-sub);"><?= htmlspecialchars($c['email'] ?? '—') ?></td>
                            <td style="color:var(--color-text-sub);"><?= htmlspecialchars($location ?: '—') ?></td>
                            <td><?= $statusBadge($status) ?></td>
                            <td style="color:var(--color-text-sub);white-space:nowrap;">
                                <?= date('M j, Y', strtotime($c['created_at'])) ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="customers-view?id=<?= $c['id'] ?>"
                                        class="btn btn-icon btn-sm btn-outline" title="View">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="customers-edit?id=<?= $c['id'] ?>"
                                        class="btn btn-icon btn-sm btn-outline" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-icon btn-sm btn-outline text-danger delete-customer-btn"
                                        data-id="<?= $c['id'] ?>"
                                        data-name="<?= htmlspecialchars($c['company_name']) ?>"
                                        title="Delete">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
