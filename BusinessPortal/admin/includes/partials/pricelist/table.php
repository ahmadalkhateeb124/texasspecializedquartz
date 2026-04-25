<?php /** Expects: $priceLists, $fileCount, $priceListManager */
require_once __DIR__ . '/../../../../customer/includes/partials/pricelist/_helpers.php';
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-list-ul text-primary'></i> All Price Lists</h6>
        <div class="d-flex gap-2 align-items-center">
            <div class="input-group" style="width:220px;">
                <span class="input-group-text" style="border-right:none;">
                    <i class='bx bx-search' style="font-size:15px;"></i>
                </span>
                <input type="text" id="priceListSearch" class="form-control"
                    placeholder="Search files…" style="border-left:none;">
            </div>
        </div>
    </div>

    <?php if ($fileCount > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Type</th>
                        <th>File Size</th>
                        <th>Assigned To</th>
                        <th>Uploaded</th>
                        <th class="text-end" style="width:160px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="priceListTbody">
                    <?php foreach ($priceLists as $p):
                        $st = priceListFileTypeStyle($p['file_type']);
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:34px;height:34px;border-radius:var(--radius-sm);background:<?= $st['bg'] ?>;
                                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class='bx <?= $st['icon'] ?>' style="font-size:1.1rem;color:<?= $st['color'] ?>;"></i>
                                    </div>
                                    <div>
                                        <a href="<?= htmlspecialchars($priceListManager->getFileUrl($p['id'], true)) ?>" target="_blank" rel="noopener"
                                            style="font-weight:600;color:var(--color-text);text-decoration:none;">
                                            <?= htmlspecialchars($p['file_name']) ?>
                                        </a>
                                        <div style="font-size:11px;color:var(--color-text-sub);">ID #<?= $p['id'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background:<?= $st['bg'] ?>;color:<?= $st['color'] ?>;font-weight:600;">
                                    <?= strtoupper(htmlspecialchars($p['file_type'])) ?>
                                </span>
                            </td>
                            <td style="color:var(--color-text-sub);"><?= PriceListManager::formatFileSize($p['file_size']) ?></td>
                            <td>
                                <span class="badge badge-neutral view-assigned-btn" style="cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#viewAssignedModal"
                                    data-id="<?= $p['id'] ?>">
                                    <i class='bx bx-user me-1'></i><?= $p['account_count'] ?>
                                    account<?= $p['account_count'] != 1 ? 's' : '' ?>
                                </span>
                            </td>
                            <td style="color:var(--color-text-sub);white-space:nowrap;">
                                <?= date('M j, Y', strtotime($p['created_at'])) ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="<?= htmlspecialchars($priceListManager->getFileUrl($p['id'])) ?>"
                                        class="btn btn-icon btn-sm btn-outline" title="Download">
                                        <i class='bx bx-download'></i>
                                    </a>
                                    <button class="btn btn-icon btn-sm btn-outline edit-pricelist-btn" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#editPriceListModal"
                                        data-id="<?= $p['id'] ?>"
                                        data-name="<?= htmlspecialchars($p['file_name'], ENT_QUOTES) ?>">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button class="btn btn-icon btn-sm btn-outline text-danger delete-pricelist-btn" title="Delete"
                                        data-id="<?= $p['id'] ?>"
                                        data-name="<?= htmlspecialchars($p['file_name'], ENT_QUOTES) ?>">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class='bx bx-file'></i></div>
            <p class="empty-state-title">No price lists uploaded yet</p>
            <p class="empty-state-desc">Upload your first price list to get started.</p>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadPriceListModal">
                <i class='bx bx-plus'></i> Upload Price List
            </button>
        </div>
    <?php endif; ?>
</div>
