<?php

/**
 * employee/order-signoff.php — Installation completion sign-off form.
 * The employee walks the customer through this on-site; the customer
 * reviews the checklist and types their own name to confirm — the
 * "signature" is a stylized rendering generated from that name.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireEmployee('../auth-login-minimal.php');

$currentUser = currentUser();
$employeeId  = $currentUser['id'];
$orderId     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($orderId <= 0) {
    header('Location: index.php');
    exit;
}

$empRepo     = new EmployeeRepository($pdo);
$signoffRepo = new SignoffRepository($pdo);

$assigned = $empRepo->employeeForOrder($orderId);
if (!$assigned || (int)$assigned['id'] !== $employeeId) {
    header('Location: index.php');
    exit;
}

if ($signoffRepo->exists($orderId)) {
    header('Location: order-view.php?id=' . $orderId . '&signoff=exists');
    exit;
}

try {
    $order = (new OrderRepository($pdo))->findWithJobs($orderId);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
if (!$order) {
    header('Location: index.php');
    exit;
}


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = csrfToken();
}

$errorMsg = match ($_GET['error'] ?? '') {
    'missing_customer'  => 'Please enter the customer name and address.',
    'missing_signature' => 'The customer must type their name to sign.',
    'server_error'      => 'Something went wrong generating the sign-off. Please try again.',
    default             => '',
};

$pageTitle  = 'Sign Off — Order #' . $orderId;
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => 'index.php'],
    ['label' => 'Order #' . $orderId, 'url' => 'order-view?id=' . $orderId],
    ['label' => 'Sign Off'],
];

include __DIR__ . '/includes/head.php';
?>
<style>
    .so-wrap { max-width: 680px; margin: 0 auto; }

    /* ── Hero ── */
    .so-hero {
        display: flex; align-items: flex-start; gap: 16px;
        padding: 24px; margin-bottom: 20px;
        background: var(--brand-l, #f4ebe0);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg, 14px);
    }
    .so-hero-icon {
        width: 48px; height: 48px; flex-shrink: 0;
        border-radius: 12px;
        background: var(--brand, #8b5a2b); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
    }
    .so-hero h2 {
        font-family: 'Fraunces', Georgia, serif;
        font-size: 20px; font-weight: 600; color: var(--text);
        margin: 0 0 4px;
    }
    .so-hero p { font-size: 13.5px; color: var(--text-sub); margin: 0; line-height: 1.55; }

    /* ── Section card ── */
    .so-card {
        background: var(--surface, #fff);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg, 14px);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .so-card-head {
        display: flex; align-items: center; gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
    }
    .so-card-head i { font-size: 18px; color: var(--brand); }
    .so-card-head h3 {
        font-family: 'Fraunces', Georgia, serif;
        font-size: 15.5px; font-weight: 600; color: var(--text); margin: 0;
    }
    .so-card-body { padding: 18px 20px; }
    .so-hint { font-size: 12.5px; color: var(--text-sub); line-height: 1.5; margin: 0 0 16px; }

    /* ── Read/edit info fields ── */
    .so-field { margin-bottom: 14px; }
    .so-field:last-child { margin-bottom: 0; }
    .so-field label {
        display: block; font-size: 11.5px; font-weight: 600; color: var(--text-sub);
        text-transform: uppercase; letter-spacing: .04em; margin-bottom: 6px;
    }
    .so-field input[type="text"] {
        width: 100%; border: 0; border-bottom: 1.5px solid var(--border);
        background: transparent; padding: 8px 2px; font-size: 15px; color: var(--text);
        font-family: inherit; transition: border-color .2s ease;
    }
    .so-field input[type="text"]:focus-visible {
        outline: none; border-bottom-color: var(--brand);
    }
    .so-field-static {
        padding: 8px 2px; font-size: 15px; color: var(--text);
        border-bottom: 1.5px solid var(--border);
    }

    /* ── Worked area pills ── */
    .so-pills { display: flex; flex-wrap: wrap; gap: 10px; }
    .so-pill {
        position: relative;
        display: inline-flex; align-items: center; gap: 8px;
        min-height: 44px; padding: 0 18px;
        border: 1.5px solid var(--border); border-radius: 999px;
        font-size: 14px; font-weight: 500; color: var(--text-sub);
        cursor: pointer; user-select: none;
        transition: background .18s ease, border-color .18s ease, color .18s ease;
        touch-action: manipulation;
    }
    .so-pill input {
        position: absolute; inset: 0; opacity: 0; cursor: pointer;
        width: 100%; height: 100%; margin: 0;
    }
    .so-pill i { font-size: 17px; }
    .so-pill:has(input:checked) {
        background: var(--brand); border-color: var(--brand); color: #fff;
    }
    .so-pill input:focus-visible {
        outline: 2px solid var(--brand); outline-offset: 2px;
    }
    .so-other-text {
        margin-top: 12px; width: 100%; max-width: 320px;
        border: 1.5px solid var(--border); border-radius: 10px;
        padding: 10px 12px; font-size: 14px; font-family: inherit;
        display: none;
    }
    .so-other-text.is-visible { display: block; }

    /* ── Checklist ── */
    .so-progress {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px; font-size: 12.5px; color: var(--text-sub); font-weight: 600;
    }
    .so-progress-bar {
        flex: 1; height: 6px; margin: 0 12px; border-radius: 999px;
        background: var(--bg, #f4f1ea); overflow: hidden;
    }
    .so-progress-fill {
        height: 100%; width: 0%; background: var(--brand);
        border-radius: 999px; transition: width .25s ease;
    }
    .so-check-row {
        display: flex; align-items: center; gap: 14px;
        min-height: 56px; padding: 10px 14px;
        border: 1.5px solid var(--border); border-radius: 12px;
        margin-bottom: 10px; cursor: pointer; user-select: none;
        transition: background .18s ease, border-color .18s ease;
        touch-action: manipulation;
    }
    .so-check-row:last-child { margin-bottom: 0; }
    .so-check-row:has(input:checked) {
        background: var(--brand-l, #f4ebe0); border-color: var(--brand);
    }
    .so-check-box {
        flex-shrink: 0; width: 26px; height: 26px; border-radius: 7px;
        border: 2px solid var(--border); background: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; color: transparent;
        transition: background .18s ease, border-color .18s ease, color .18s ease;
    }
    .so-check-row input {
        position: absolute; opacity: 0; width: 26px; height: 26px; margin: 0; cursor: pointer;
    }
    .so-check-row input:checked ~ .so-check-box {
        background: var(--brand); border-color: var(--brand); color: #fff;
    }
    .so-check-row input:focus-visible ~ .so-check-box {
        outline: 2px solid var(--brand); outline-offset: 2px;
    }
    .so-check-label { font-size: 14px; color: var(--text); line-height: 1.4; }

    /* ── Hand-off banner ── */
    .so-handoff {
        display: flex; align-items: center; gap: 12px;
        padding: 16px 20px; margin: 24px 0;
        background: var(--text, #1a1814); color: #fff;
        border-radius: var(--radius-lg, 14px);
    }
    .so-handoff i { font-size: 22px; color: var(--brand-l, #d9c2a3); flex-shrink: 0; }
    .so-handoff strong { display: block; font-size: 14px; }
    .so-handoff span { font-size: 12.5px; opacity: .8; }

    /* ── Signature ── */
    .so-sig-input {
        width: 100%; border: 0; border-bottom: 2px solid var(--brand);
        background: transparent; padding: 10px 2px; font-size: 17px; color: var(--text);
        font-family: inherit;
    }
    .so-sig-input:focus-visible { outline: none; }
    .so-sig-preview-wrap {
        margin-top: 18px; padding: 22px 20px;
        background: var(--bg, #f7f4ef); border: 1.5px dashed var(--border);
        border-radius: 12px; text-align: center;
    }
    .so-sig-preview-label {
        font-size: 11px; color: var(--text-sub); text-transform: uppercase;
        letter-spacing: .06em; margin-bottom: 8px;
    }
    .so-sig-preview {
        font-family: 'Fraunces', Georgia, serif; font-style: italic; font-weight: 600;
        font-size: 30px; color: var(--text); min-height: 40px;
        border-bottom: 1.5px solid var(--border); display: inline-block; padding: 0 12px 6px;
    }

    /* ── Submit ── */
    .so-submit {
        width: 100%; min-height: 52px; border: 0; border-radius: 12px;
        background: var(--brand); color: #fff; font-size: 15px; font-weight: 700;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        cursor: pointer; transition: background .18s ease; touch-action: manipulation;
    }
    .so-submit:hover { background: var(--brand-h, #6e4621); }
    .so-submit:disabled { opacity: .65; cursor: wait; }
    .so-submit i { font-size: 19px; }

    @media (prefers-reduced-motion: reduce) {
        .so-pill, .so-check-row, .so-check-box, .so-progress-fill, .so-submit { transition: none; }
    }
</style>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">
            <div class="so-wrap">

                <div class="page-header">
                    <div class="page-header-left">
                        <h1 class="page-title">Sign Off</h1>
                        <p class="page-desc">Order #<?= $orderId ?></p>
                    </div>
                    <a href="order-view?id=<?= $orderId ?>" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back
                    </a>
                </div>

                <?php if ($errorMsg): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                        <i class='bx bx-error-circle'></i> <?= htmlspecialchars($errorMsg) ?>
                    </div>
                <?php endif; ?>

                <div class="so-hero">
                    <div class="so-hero-icon"><i class='bx bx-shield-quarter'></i></div>
                    <div>
                        <h2>Installation Completion Sign Off</h2>
                        <p>Fill in the job details below, walk the customer through the checklist, then hand them
                           the device to confirm and sign. This closes the order once submitted.</p>
                    </div>
                </div>

                <form method="POST" action="../auth/signoff-order.php" id="signoffForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="order_id" value="<?= $orderId ?>">

                    <div class="so-card">
                        <div class="so-card-head">
                            <i class='bx bx-user'></i>
                            <h3>Customer Being Serviced</h3>
                        </div>
                        <div class="so-card-body">
                            <?php $customerAddressVal = trim(($order['address'] ?? '') . ', ' . ($order['city'] ?? ''), ', '); ?>
                            <div class="so-field">
                                <label>Customer Name</label>
                                <div class="so-field-static"><?= htmlspecialchars($order['customer_name'] ?? '—') ?></div>
                            </div>
                            <div class="so-field">
                                <label>Customer Address</label>
                                <div class="so-field-static"><?= htmlspecialchars($customerAddressVal ?: '—') ?></div>
                            </div>
                            <input type="hidden" name="customer_name" value="<?= htmlspecialchars($order['customer_name'] ?? '') ?>">
                            <input type="hidden" name="customer_address" value="<?= htmlspecialchars($customerAddressVal) ?>">
                        </div>
                    </div>

                    <div class="so-card">
                        <div class="so-card-head">
                            <i class='bx bx-layer'></i>
                            <h3>Worked Area</h3>
                        </div>
                        <div class="so-card-body">
                            <div class="so-pills">
                                <label class="so-pill">
                                    <input type="checkbox" name="worked_area[kitchen]" value="1">
                                    <i class='bx bx-restaurant'></i> Kitchen
                                </label>
                                <label class="so-pill">
                                    <input type="checkbox" name="worked_area[vanity]" value="1">
                                    <i class='bx bx-bath'></i> Vanity
                                </label>
                                <label class="so-pill">
                                    <input type="checkbox" name="worked_area[other]" id="otherAreaCheck" value="1">
                                    <i class='bx bx-dots-horizontal-rounded'></i> Other
                                </label>
                            </div>
                            <input type="text" name="worked_area_other_text" id="otherAreaText" class="so-other-text"
                                placeholder="Describe the other area">
                        </div>
                    </div>

                    <div class="so-card">
                        <div class="so-card-head">
                            <i class='bx bx-check-square'></i>
                            <h3>Completion Checklist</h3>
                        </div>
                        <div class="so-card-body">
                            <p class="so-hint">
                                Review each item with the customer and check off only what they're satisfied with —
                                leave anything unchecked if they aren't.
                            </p>
                            <div class="so-progress">
                                <span>Reviewed together</span>
                                <span class="so-progress-bar"><span class="so-progress-fill" id="checklistFill"></span></span>
                                <span id="checklistCount">0 / <?= count(SignoffRepository::CHECKLIST_ITEMS) ?></span>
                            </div>
                            <?php foreach (SignoffRepository::CHECKLIST_ITEMS as $key => $label): ?>
                                <label class="so-check-row checklist-row">
                                    <input type="checkbox" name="checklist[<?= $key ?>]" value="1" class="checklist-input">
                                    <span class="so-check-box"><i class='bx bx-check'></i></span>
                                    <span class="so-check-label"><?= htmlspecialchars($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="so-handoff">
                        <i class='bx bx-mobile-alt'></i>
                        <div>
                            <strong>Hand the device to the customer now</strong>
                            <span>They'll type their own name below to confirm and sign.</span>
                        </div>
                    </div>

                    <div class="so-card">
                        <div class="so-card-head">
                            <i class='bx bx-edit-alt'></i>
                            <h3>Customer Confirmation</h3>
                        </div>
                        <div class="so-card-body">
                            <p class="so-hint">
                                By typing your name below, you verify that installation was completed to your satisfaction.
                            </p>
                            <label for="signatureNameInput" style="display:block;font-size:11.5px;font-weight:600;color:var(--text-sub);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">
                                Type your full name to sign
                            </label>
                            <input type="text" name="signature_name" id="signatureNameInput" class="so-sig-input"
                                placeholder="Your full name" autocomplete="off" required>

                            <div class="so-sig-preview-wrap">
                                <div class="so-sig-preview-label">Signature Preview</div>
                                <div class="so-sig-preview" id="signaturePreview">&nbsp;</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="so-submit" id="submitBtn">
                        <i class='bx bx-check-shield'></i> Confirm &amp; Sign — Close Order
                    </button>
                </form>

            </div>
        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script>
        (function () {
            function generateSignature(name) {
                const parts = name.trim().split(/\s+/).filter(Boolean);
                if (!parts.length) return '';
                const first = parts.shift().toUpperCase();
                const initials = parts.map(p => p.charAt(0).toUpperCase() + '.');
                return [first, ...initials].join(' ');
            }

            const nameInput = document.getElementById('signatureNameInput');
            const preview   = document.getElementById('signaturePreview');
            function updateSignature() {
                const sig = generateSignature(nameInput.value);
                preview.textContent = sig || ' ';
            }
            nameInput.addEventListener('input', updateSignature);
            updateSignature();

            const otherCheck = document.getElementById('otherAreaCheck');
            const otherText  = document.getElementById('otherAreaText');
            otherCheck.addEventListener('change', () => {
                otherText.classList.toggle('is-visible', otherCheck.checked);
            });

            const checklistInputs = document.querySelectorAll('.checklist-input');
            const fill  = document.getElementById('checklistFill');
            const count = document.getElementById('checklistCount');
            const total = checklistInputs.length;
            function updateProgress() {
                const checked = document.querySelectorAll('.checklist-input:checked').length;
                fill.style.width = (checked / total * 100) + '%';
                count.textContent = checked + ' / ' + total;
            }
            checklistInputs.forEach(el => el.addEventListener('change', updateProgress));
            updateProgress();

            document.getElementById('signoffForm').addEventListener('submit', function () {
                document.getElementById('submitBtn').disabled = true;
            });
        })();
        </script>
