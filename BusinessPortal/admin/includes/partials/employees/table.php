<?php /** Expects: $employees */ ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-id-card text-primary'></i> All Employees</h6>
        <div class="d-flex gap-2 align-items-center">
            <div class="input-group" style="width:220px;">
                <span class="input-group-text" style="border-right:none;">
                    <i class='bx bx-search' style="font-size:15px;"></i>
                </span>
                <input type="text" id="employeeSearch" class="form-control"
                    placeholder="Search employees…" style="border-left:none;">
            </div>
            <select id="statusFilter" class="form-select" style="width:150px;">
                <option value="">All Statuses</option>
                <option>Active</option>
                <option>Inactive</option>
            </select>
        </div>
    </div>

    <?php if (empty($employees)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class='bx bx-id-card'></i></div>
            <p class="empty-state-title">No employees yet</p>
            <p class="empty-state-desc">Add your first employee to start assigning job sections.</p>
            <a href="employees-new" class="btn btn-primary btn-sm">
                <i class='bx bx-plus'></i> Add Employee
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="employeesTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Designation</th>
                        <th>Assigned Sections</th>
                        <th>Status</th>
                        <th class="text-end" style="width:90px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="employeesTbody">
                    <?php foreach ($employees as $e):
                        $status   = $e['status'] ?? 'Active';
                        $initials = strtoupper(substr($e['fullname'] ?? 'E', 0, 2));
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
                                        <a href="employees-edit?id=<?= $e['id'] ?>"
                                            style="font-weight:600;color:var(--color-text);">
                                            <?= htmlspecialchars($e['fullname']) ?>
                                        </a>
                                        <div style="font-size:11px;color:var(--color-text-sub);">ID #<?= $e['id'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="color:var(--color-text-sub);"><?= htmlspecialchars($e['email'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($e['phone'] ?? '—') ?></td>
                            <td style="color:var(--color-text-sub);"><?= htmlspecialchars($e['designation'] ?? '—') ?></td>
                            <td><span class="badge badge-primary"><?= (int)$e['section_count'] ?></span></td>
                            <td>
                                <?php if ($status === 'Active'): ?>
                                    <span class="badge badge-success"><span class="dot"></span>Active</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral"><span class="dot"></span>Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="employees-edit?id=<?= $e['id'] ?>"
                                        class="btn btn-icon btn-sm btn-outline" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-icon btn-sm btn-outline text-danger delete-employee-btn"
                                        data-id="<?= $e['id'] ?>"
                                        data-name="<?= htmlspecialchars($e['fullname']) ?>"
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
