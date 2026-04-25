<?php
/**
 * Reusable customer picker with search + checkbox list.
 * Renders a hidden <select multiple> the original form expects,
 * plus a clean visual picker above it.
 *
 * Expects: $accounts
 * Optional: $pickerId (default: uploadPicker), $pickerSelect (default: assignAccounts)
 *           $pickerLoadingId (optional)
 */
$pickerId        = $pickerId        ?? 'uploadPicker';
$pickerSelect    = $pickerSelect    ?? 'assignAccounts';
$pickerLoadingId = $pickerLoadingId ?? '';
?>
<label class="pl-label">Assign to Customers</label>
<div class="pl-picker" data-picker="<?= htmlspecialchars($pickerId) ?>" data-select="<?= htmlspecialchars($pickerSelect) ?>">
    <div class="pl-picker-head">
        <i class='bx bx-user-plus'></i>
        <span class="pl-picker-title">Select customers</span>
        <?php if ($pickerLoadingId): ?>
            <span class="pl-picker-loading" id="<?= htmlspecialchars($pickerLoadingId) ?>" style="display:none;margin-inline-start:auto;">
                <span class="pl-spinner" style="width:14px;height:14px;"></span>
            </span>
        <?php endif; ?>
        <span class="pl-picker-badge" data-count>
            0 / <?= count($accounts) ?>
        </span>
    </div>

    <div class="pl-picker-search">
        <i class='bx bx-search'></i>
        <input type="text" placeholder="Search by name or email…" data-picker-search>
    </div>

    <div class="pl-picker-list" data-picker-list>
        <?php if (empty($accounts)): ?>
            <div class="pl-picker-empty">
                <i class='bx bx-user-x'></i>
                No active customers available
            </div>
        <?php else: ?>
            <?php foreach ($accounts as $a):
                $initials = strtoupper(substr($a['name'] ?? 'C', 0, 2));
            ?>
                <label class="pl-picker-item" data-name="<?= htmlspecialchars(strtolower($a['name'] . ' ' . $a['email'])) ?>">
                    <input type="checkbox" value="<?= $a['id'] ?>" data-picker-check>
                    <div class="pl-picker-avatar"><?= htmlspecialchars($initials) ?></div>
                    <div class="pl-picker-info">
                        <div class="pl-picker-name"><?= htmlspecialchars($a['name']) ?></div>
                        <div class="pl-picker-email"><?= htmlspecialchars($a['email']) ?></div>
                    </div>
                </label>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="pl-picker-footer">
        <button type="button" data-picker-all>Select all</button>
        <button type="button" data-picker-clear>Clear</button>
        <span class="pl-picker-spacer"></span>
        <span style="color:var(--text-sub);">
            <span data-selected-count>0</span> selected
        </span>
    </div>
</div>

<!-- Hidden select kept so FormData picks the values with the original field name -->
<select name="assigned_accounts[]" id="<?= htmlspecialchars($pickerSelect) ?>" multiple style="display:none;">
    <?php foreach ($accounts as $a): ?>
        <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
    <?php endforeach; ?>
</select>
