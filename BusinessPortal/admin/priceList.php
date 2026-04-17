<?php

/**
 * admin/priceList.php — Price List Management
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/pricelist-db.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Price Lists';
$breadcrumb  = [['label' => 'Price Lists']];

/* ── Fetch price lists from database ──────────────────────────────────── */
try {
    $priceListManager = new PriceListManager($pdo);
    $priceLists = $priceListManager->getAllPriceLists();
    $accounts = $priceListManager->getActiveAccounts();
} catch (PDOException $e) {
    $priceLists = [];
    $accounts = [];
    $dbError = htmlspecialchars($e->getMessage());
}

$fileCount = count($priceLists);

/**
 * Get file type icon and color
 */
function getFileTypeStyle(string $type): array
{
    return match (strtolower($type)) {
        'pdf'          => ['icon' => 'bx-file', 'color' => '#b91c1c', 'bg' => '#fee2e2'],
        'xlsx', 'xls'  => ['icon' => 'bx-spreadsheet', 'color' => '#3d6b4f', 'bg' => '#dff0e5'],
        'doc', 'docx'  => ['icon' => 'bx-file-doc', 'color' => '#2563eb', 'bg' => '#dbeafe'],
        default        => ['icon' => 'bx-image', 'color' => '#b45309', 'bg' => '#fef3c7'],
    };
}

include __DIR__ . '/includes/head.php';
?>

<!-- Select2 CSS — must be in head before rendering -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    /* ── Kill ALL blue from Bootstrap & Select2 ── */
    :root {
        --bs-primary: #c9a96e !important;
        --bs-primary-rgb: 201, 169, 110 !important;
        --bs-link-color: #c9a96e !important;
        --bs-link-hover-color: #a07840 !important;
    }

    /* Bootstrap focus ring override */
    .form-control:focus,
    .form-select:focus {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 .2rem rgba(201, 169, 110, .25) !important;
    }

    /* ── Select2 — full override, zero blue ── */
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid var(--color-border) !important;
        border-radius: var(--radius) !important;
        min-height: 42px !important;
        background: var(--color-surface) !important;
        padding: 4px 8px !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 2px 0;
    }

    /* Selected chips — gold tags */
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background: var(--color-primary-l) !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
        border-radius: var(--radius-sm) !important;
        color: #ffffff !important;
        font-size: .8rem !important;
        font-weight: 600 !important;
        padding: 3px 8px !important;
        margin: 0 !important;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Remove button on chips */
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: #767676 !important;
        font-size: 1rem !important;
        border: none !important;
        background: none !important;
        padding: 0 2px !important;
        margin: 0 !important;
        order: 2;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: var(--color-critical) !important;
        background: none !important;
    }

    /* Search input inside select */
    .select2-container--bootstrap-5 .select2-search__field {
        font-size: .85rem !important;
        color: var(--color-text) !important;
    }

    .select2-container--bootstrap-5 .select2-search__field::placeholder {
        color: var(--color-text-dis) !important;
    }

    /* Focus & open states — gold, NOT blue */
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 .2rem rgba(121, 121, 121, 0.25) !important;
        outline: none !important;
    }

    /* Dropdown panel */
    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid var(--color-border) !important;
        border-radius: var(--radius) !important;
        box-shadow: var(--shadow-md) !important;
        overflow: hidden;
    }

    /* Dropdown options */
    .select2-container--bootstrap-5 .select2-results__option {
        font-size: .85rem !important;
        padding: 8px 12px !important;
        color: var(--color-text) !important;
        background: var(--color-surface) !important;
    }

    /* Hover / highlighted option — gold tint */
    .select2-container--bootstrap-5 .select2-results__option--highlighted,
    .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected],
    .select2-container--bootstrap-5 .select2-results__option.select2-results__option--highlighted {
        background: var(--color-primary-l) !important;
        color: #767676 !important;
    }

    /* Already selected option — solid gold */
    .select2-container--bootstrap-5 .select2-results__option--selected,
    .select2-container--bootstrap-5 .select2-results__option[aria-selected="true"] {
        background: var(--color-primary) !important;
        color: #fff !important;
    }

    /* Dropdown search field */
    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        border: 1px solid var(--color-border) !important;
        border-radius: var(--radius-sm) !important;
        padding: 8px 10px !important;
        font-size: .85rem !important;
        outline: none !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 .2rem rgba(201, 169, 110, .25) !important;
        outline: none !important;
    }

    /* Clear button */
    .select2-container--bootstrap-5 .select2-selection__clear {
        color: var(--color-text-sub) !important;
    }

    .select2-container--bootstrap-5 .select2-selection__clear:hover {
        color: var(--color-critical) !important;
    }

    /* Assign box wrapper */
    .assign-section {
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        background: var(--color-bg-subdued);
        overflow: hidden;
    }

    .assign-section-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        background: var(--color-bg);
        border-bottom: 1px solid var(--color-border);
        font-size: .85rem;
        font-weight: 600;
        color: var(--color-text);
    }

    .assign-section-header i {
        font-size: 1.15rem;
        color: var(--color-primary);
    }

    .assign-section-body {
        padding: 16px;
    }

    .assign-section-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        font-size: .78rem;
        color: var(--color-text-sub);
    }

    .assign-section-hint i {
        font-size: .85rem;
        flex-shrink: 0;
    }
</style>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title"><i class='bx bx-file me-2'></i>Price Lists</h1>
                    <p class="page-subtitle"><?= $fileCount ?> total file<?= $fileCount != 1 ? 's' : '' ?></p>
                </div>
                <div class="page-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPriceListModal">
                        <i class='bx bx-plus'></i> Upload Price List
                    </button>
                </div>
            </div>

            <?php if (isset($dbError)): ?>
                <div class="alert alert-danger mb-3"><i class='bx bx-error me-2'></i><?= $dbError ?></div>
            <?php endif; ?>

            <!-- KPI strip -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label">Total Price Lists</div>
                                <div class="kpi-value"><?= $fileCount ?></div>
                            </div>
                            <div class="kpi-icon" style="background:var(--color-primary-l);">
                                <i class='bx bx-file' style="color:var(--color-primary);"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label">Active Customers</div>
                                <div class="kpi-value" style="color:var(--color-success);"><?= count($accounts) ?></div>
                            </div>
                            <div class="kpi-icon" style="background:var(--color-success-l);">
                                <i class='bx bx-user-check' style="color:var(--color-success);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Lists Table -->
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
                                <?php foreach ($priceLists as $priceList):
                                    $fStyle = getFileTypeStyle($priceList['file_type']);
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width:34px; height:34px; border-radius:var(--radius-sm); background:<?= $fStyle['bg'] ?>;
                                                    display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                    <i class='bx <?= $fStyle['icon'] ?>' style="font-size:1.1rem; color:<?= $fStyle['color'] ?>;"></i>
                                                </div>
                                                <div>
                                                    <a href="<?= htmlspecialchars($priceListManager->getFileUrl($priceList['file_path'])) ?>" target="_blank"
                                                        style="font-weight:600; color:var(--color-text); text-decoration:none;">
                                                        <?= htmlspecialchars($priceList['file_name']) ?>
                                                    </a>
                                                    <div style="font-size:11px; color:var(--color-text-sub);">ID #<?= $priceList['id'] ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background:<?= $fStyle['bg'] ?>; color:<?= $fStyle['color'] ?>; font-weight:600;">
                                                <?= strtoupper(htmlspecialchars($priceList['file_type'])) ?>
                                            </span>
                                        </td>
                                        <td style="color:var(--color-text-sub);"><?= PriceListManager::formatFileSize($priceList['file_size']) ?></td>
                                        <td>
                                            <span class="badge badge-neutral" style="cursor:pointer;"
                                                data-bs-toggle="modal" data-bs-target="#viewAssignedModal"
                                                onclick="viewAssigned(<?= $priceList['id'] ?>)">
                                                <i class='bx bx-user me-1'></i><?= $priceList['account_count'] ?> account<?= $priceList['account_count'] != 1 ? 's' : '' ?>
                                            </span>
                                        </td>
                                        <td style="color:var(--color-text-sub); white-space:nowrap;"><?= date('M j, Y', strtotime($priceList['created_at'])) ?></td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="<?= htmlspecialchars($priceListManager->getFileUrl($priceList['file_path'])) ?>" target="_blank"
                                                    class="btn btn-icon btn-sm btn-outline" title="Download">
                                                    <i class='bx bx-download'></i>
                                                </a>
                                                <button class="btn btn-icon btn-sm btn-outline" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editPriceListModal"
                                                    onclick="setEditPriceListId(<?= $priceList['id'] ?>, '<?= htmlspecialchars($priceList['file_name'], ENT_QUOTES) ?>')">
                                                    <i class='bx bx-edit'></i>
                                                </button>
                                                <button class="btn btn-icon btn-sm btn-outline text-danger" title="Delete"
                                                    onclick="deletePriceList(<?= $priceList['id'] ?>, '<?= htmlspecialchars($priceList['file_name'], ENT_QUOTES) ?>')">
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

        </main>



        <!-- Upload Price List Modal -->
        <div class="modal fade" id="uploadPriceListModal" tabindex="-1" aria-labelledby="uploadPriceListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadPriceListModalLabel"><i class='bx bx-upload me-2'></i>Upload Price List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="uploadPriceListForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="fileName" class="form-label">Price List Name *</label>
                                <input type="text" class="form-control" id="fileName" name="file_name" placeholder="e.g., Q1 2026 Price List" required>
                            </div>
                            <div class="mb-4">
                                <label for="priceListFile" class="form-label">File (PDF, Excel, Images) *</label>
                                <input type="file" class="form-control" id="priceListFile" name="file" accept=".pdf,.xls,.xlsx,.doc,.docx,.jpg,.jpeg,.png,.gif" required>
                                <small class="form-text text-muted">Max file size: 50MB. Supported: PDF, XLS, XLSX, DOC, DOCX, JPG, PNG, GIF</small>
                            </div>

                            <!-- Assign to Customers -->
                            <div class="assign-section">
                                <div class="assign-section-header">
                                    <i class='bx bx-user-plus'></i>
                                    Assign to Customers
                                    <span class="badge ms-auto" style="background:var(--color-primary-l); color:var(--color-primary); font-weight:600; font-size:.7rem;">
                                        <?= count($accounts) ?> available
                                    </span>
                                </div>
                                <div class="assign-section-body">
                                    <select class="form-select" id="assignAccounts" name="assigned_accounts[]" multiple>
                                        <?php foreach ($accounts as $account): ?>
                                            <option value="<?= $account['id'] ?>"><?= htmlspecialchars($account['name']) ?> &bull; <?= htmlspecialchars($account['email']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="assign-section-hint">
                                        <i class='bx bx-info-circle'></i>
                                        Search and select customers who will have access to this price list
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="uploadBtn"><i class='bx bx-upload me-1'></i> Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Price List Modal -->
        <div class="modal fade" id="editPriceListModal" tabindex="-1" aria-labelledby="editPriceListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPriceListModalLabel"><i class='bx bx-edit me-2'></i>Edit Price List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editPriceListForm" enctype="multipart/form-data">
                        <input type="hidden" id="editPriceListId" name="price_list_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editFileName" class="form-label">Price List Name *</label>
                                <input type="text" class="form-control" id="editFileName" name="file_name" required>
                            </div>
                            <div class="mb-4">
                                <label for="editPriceListFile" class="form-label">Replace File (Optional)</label>
                                <input type="file" class="form-control" id="editPriceListFile" name="file" accept=".pdf,.xls,.xlsx,.doc,.docx,.jpg,.jpeg,.png,.gif">
                                <small class="form-text text-muted">Leave empty to keep current file. Max: 50MB</small>
                            </div>

                            <!-- Assign to Customers -->
                            <div class="assign-section">
                                <div class="assign-section-header">
                                    <i class='bx bx-user-plus'></i>
                                    Assign to Customers
                                    <span id="editAssignLoading" class="ms-auto" style="display:none;">
                                        <span class="spinner-border spinner-border-sm" style="width:14px; height:14px; color:var(--color-primary);"></span>
                                        <span style="font-size:.75rem; font-weight:400; color:var(--color-text-sub); margin-left:4px;">Loading...</span>
                                    </span>
                                    <span class="badge ms-auto" id="editAssignBadge" style="background:var(--color-primary-l); color:var(--color-primary); font-weight:600; font-size:.7rem;">
                                        <?= count($accounts) ?> available
                                    </span>
                                </div>
                                <div class="assign-section-body">
                                    <select class="form-select" id="editAssignAccounts" name="assigned_accounts[]" multiple>
                                        <?php foreach ($accounts as $account): ?>
                                            <option value="<?= $account['id'] ?>"><?= htmlspecialchars($account['name']) ?> &bull; <?= htmlspecialchars($account['email']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="assign-section-hint">
                                        <i class='bx bx-info-circle'></i>
                                        Update customer access for this price list
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="editBtn"><i class='bx bx-check me-1'></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Assigned Accounts Modal -->
        <div class="modal fade" id="viewAssignedModal" tabindex="-1" aria-labelledby="viewAssignedModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewAssignedModalLabel"><i class='bx bx-user-check me-2'></i>Assigned Customers</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="assignedAccountsList">
                        <p class="text-muted">Loading...</p>
                    </div>
                </div>
            </div>
        </div>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            // Search
            (function() {
                const search = document.getElementById('priceListSearch');
                const rows = () => document.querySelectorAll('#priceListTbody tr');

                function applySearch() {
                    const q = search?.value.toLowerCase() || '';
                    rows().forEach(tr => {
                        tr.style.display = !q || tr.textContent.toLowerCase().includes(q) ? '' : 'none';
                    });
                }
                search?.addEventListener('input', applySearch);
            })();

            // Select2 init helper
            function initSelect2(selector, parentModal) {
                $(selector).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Type to search customers...',
                    allowClear: true,
                    closeOnSelect: false,
                    dropdownParent: $(parentModal),
                    language: {
                        noResults: () => 'No customer found'
                    }
                });
            }

            // Upload modal: init Select2 when shown
            $('#uploadPriceListModal').on('shown.bs.modal', function() {
                if ($('#assignAccounts').hasClass('select2-hidden-accessible')) {
                    $('#assignAccounts').select2('destroy');
                }
                initSelect2('#assignAccounts', '#uploadPriceListModal');
            });

            // Edit modal: init Select2 and load assigned accounts
            $('#editPriceListModal').on('shown.bs.modal', function() {
                if ($('#editAssignAccounts').hasClass('select2-hidden-accessible')) {
                    $('#editAssignAccounts').select2('destroy');
                }
                initSelect2('#editAssignAccounts', '#editPriceListModal');

                // Load assigned accounts
                const priceListId = document.getElementById('editPriceListId').value;
                if (priceListId) {
                    loadAssignedAccounts(priceListId);
                }
            });

            // Reset upload form when modal closes
            $('#uploadPriceListModal').on('hidden.bs.modal', function() {
                document.getElementById('uploadPriceListForm').reset();
                if ($('#assignAccounts').hasClass('select2-hidden-accessible')) {
                    $('#assignAccounts').val(null).trigger('change');
                }
            });

            /**
             * Load assigned accounts and pre-select them in edit modal
             */
            function loadAssignedAccounts(priceListId) {
                const loading = document.getElementById('editAssignLoading');
                const badge = document.getElementById('editAssignBadge');
                loading.style.display = '';
                badge.style.display = 'none';

                fetch(`../auth/get-assigned-accounts.php?price_list_id=${priceListId}`)
                    .then(r => r.json())
                    .then(data => {
                        loading.style.display = 'none';
                        badge.style.display = '';
                        if (data.success && data.accounts.length > 0) {
                            const ids = data.accounts.map(a => String(a.id));
                            $('#editAssignAccounts').val(ids).trigger('change');
                            badge.textContent = data.accounts.length + ' assigned';
                        } else {
                            $('#editAssignAccounts').val(null).trigger('change');
                            badge.textContent = '<?= count($accounts) ?> available';
                        }
                    })
                    .catch(err => {
                        loading.style.display = 'none';
                        badge.style.display = '';
                        console.error('Error:', err);
                    });
            }

            // Upload Price List
            document.getElementById('uploadPriceListForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const uploadBtn = document.getElementById('uploadBtn');
                uploadBtn.disabled = true;
                uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

                try {
                    const response = await fetch('../auth/upload-pricelist.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire('Success!', 'Price list uploaded successfully', 'success');
                        this.reset();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to upload price list', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'An error occurred while uploading', 'error');
                    console.error(error);
                } finally {
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = '<i class="bx bx-upload me-1"></i> Upload';
                }
            });

            // Set Edit Price List ID and name
            function setEditPriceListId(priceListId, fileName) {
                document.getElementById('editPriceListId').value = priceListId;
                document.getElementById('editFileName').value = fileName;
                if ($('#editAssignAccounts').hasClass('select2-hidden-accessible')) {
                    $('#editAssignAccounts').val(null).trigger('change');
                }
            }

            // Edit/Update Price List
            document.getElementById('editPriceListForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const editBtn = document.getElementById('editBtn');
                editBtn.disabled = true;
                editBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

                try {
                    const response = await fetch('../auth/update-pricelist.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire('Success!', 'Price list updated successfully', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('editPriceListModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to update price list', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'An error occurred while updating', 'error');
                    console.error(error);
                } finally {
                    editBtn.disabled = false;
                    editBtn.innerHTML = '<i class="bx bx-check me-1"></i> Update';
                }
            });

            // Delete Price List
            function deletePriceList(priceListId, fileName) {
                Swal.fire({
                    title: 'Delete Price List?',
                    text: `Are you sure you want to delete "${fileName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const formData = new FormData();
                            formData.append('price_list_id', priceListId);

                            const response = await fetch('../auth/delete-pricelist.php', {
                                method: 'POST',
                                body: formData
                            });
                            const data = await response.json();

                            if (data.success) {
                                Swal.fire('Deleted!', 'Price list deleted successfully', 'success');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                Swal.fire('Error!', data.message || 'Failed to delete price list', 'error');
                            }
                        } catch (error) {
                            Swal.fire('Error!', 'An error occurred while deleting', 'error');
                            console.error(error);
                        }
                    }
                });
            }

            // View Assigned Accounts
            function viewAssigned(priceListId) {
                document.getElementById('assignedAccountsList').innerHTML = `
                <div class="text-center py-3">
                    <span class="spinner-border spinner-border-sm me-2" style="color:var(--color-primary);"></span>
                    <span style="color:var(--color-text-sub);">Loading...</span>
                </div>`;

                fetch(`../auth/get-assigned-accounts.php?price_list_id=${priceListId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            let html = '';
                            if (data.accounts.length > 0) {
                                html = `<div class="d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom:1px solid var(--color-border);">
                                <span class="badge" style="background:var(--color-primary-l); color:var(--color-primary); font-weight:600;">
                                    ${data.accounts.length} customer${data.accounts.length !== 1 ? 's' : ''}
                                </span>
                            </div>`;
                                html += '<div class="list-group list-group-flush">';
                                data.accounts.forEach(account => {
                                    const initials = account.name.substring(0, 2).toUpperCase();
                                    html += `<div class="list-group-item d-flex align-items-center gap-3 px-0 py-2">
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--color-primary);
                                        display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                                        ${initials}
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="font-weight:600; color:var(--color-text); font-size:.9rem;">${account.name}</div>
                                        <div style="font-size:.8rem; color:var(--color-text-sub); overflow:hidden; text-overflow:ellipsis;">${account.email}</div>
                                    </div>
                                </div>`;
                                });
                                html += '</div>';
                            } else {
                                html = `<div class="text-center py-4">
                                <i class="bx bx-user-x" style="font-size:2.5rem; color:var(--color-text-dis);"></i>
                                <p class="text-muted mt-2 mb-0">No customers assigned to this price list</p>
                            </div>`;
                            }
                            document.getElementById('assignedAccountsList').innerHTML = html;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        </script>

</body>

</html>