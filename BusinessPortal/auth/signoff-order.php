<?php
/**
 * auth/signoff-order.php — Employee submits the installation completion
 * sign-off for an order: builds the PDF, stores the record, marks the
 * order completed. One sign-off per order (enforced by a unique DB key).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/Repositories/SiteSettingsRepository.php';

/**
 * Emails the sign-off PDF (short message + attachment) to the customer who
 * placed the order and to the admin. Failures are logged, never fatal.
 */
function notifySignoffCompleted(PDO $pdo, int $orderId, array $signoffData, string $pdfPath, string $pdfFilename): void
{
    try {
        $mailConfig = require __DIR__ . '/../config/mail.php';
        if (empty($mailConfig['enabled']) || empty($mailConfig['password'])) {
            return;
        }

        $stmt = $pdo->prepare("
            SELECT fo.id, fo.customer_name, a.email AS customer_email
            FROM fabrication_orders fo
            LEFT JOIN accounts a ON a.id = fo.account_id
            WHERE fo.id = ?
        ");
        $stmt->execute([$orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return;
        }

        $order = ['id' => $row['id'], 'customer_name' => $row['customer_name']];
        $built = SignoffCompletedEmailBuilder::build($order, $signoffData);
        $mailer = new Mailer($mailConfig);

        $recipients = array_filter([$row['customer_email'] ?? null, $mailConfig['admin_email'] ?? null]);
        foreach (array_unique($recipients) as $to) {
            $mailer->send($to, $built['subject'], $built['body'], [
                'attachment_path' => $pdfPath,
                'attachment_name' => $pdfFilename,
            ]);
        }
    } catch (\Throwable $e) {
        Logger::exception($e, 'signoff');
    }
}

requireEmployee('../auth-login-minimal.php');

$employeeId = (int)($_SESSION['user_id'] ?? 0);
$orderId    = (int)($_POST['order_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $orderId <= 0) {
    header('Location: ../employee/index.php');
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals(csrfToken(), $_POST['csrf_token'])) {
    http_response_code(403);
    die('Invalid CSRF token.');
}

$empRepo     = new EmployeeRepository($pdo);
$signoffRepo = new SignoffRepository($pdo);

$assigned = $empRepo->employeeForOrder($orderId);
if (!$assigned || (int)$assigned['id'] !== $employeeId) {
    http_response_code(403);
    die('You are not assigned to this order.');
}

if ($signoffRepo->exists($orderId)) {
    header('Location: ../employee/order-view.php?id=' . $orderId . '&signoff=exists');
    exit;
}

$customerName    = trim($_POST['customer_name'] ?? '');
$customerAddress = trim($_POST['customer_address'] ?? '');
$signatureName   = trim($_POST['signature_name'] ?? '');

if ($customerName === '' || $customerAddress === '') {
    header('Location: ../employee/order-signoff.php?id=' . $orderId . '&error=missing_customer');
    exit;
}

if ($signatureName === '') {
    header('Location: ../employee/order-signoff.php?id=' . $orderId . '&error=missing_signature');
    exit;
}

/* Reflects what the customer actually confirmed — items they aren't
   satisfied with are recorded unchecked, not forced. */
$checklist = [];
foreach (SignoffRepository::CHECKLIST_ITEMS as $key => $label) {
    $checklist[$key] = ['label' => $label, 'checked' => !empty($_POST['checklist'][$key])];
}

$workedArea = [
    'kitchen'    => !empty($_POST['worked_area']['kitchen']),
    'vanity'     => !empty($_POST['worked_area']['vanity']),
    'other'      => !empty($_POST['worked_area']['other']),
    'other_text' => trim($_POST['worked_area_other_text'] ?? ''),
];

$signatureText = SignoffRepository::generateSignatureText($signatureName);
if ($signatureText === '') {
    header('Location: ../employee/order-signoff.php?id=' . $orderId . '&error=missing_signature');
    exit;
}

try {
    $business = [
        'name'    => setting('business_name', 'Texas Specialized Quartz & Granite'),
        'address' => setting('business_address', ''),
        'city'    => setting('business_city', ''),
        'state'   => setting('business_state', ''),
        'zip'     => setting('business_zip', ''),
        'phone'   => setting('business_phone', ''),
        'email'   => setting('business_email', ''),
    ];

    $signoffData = [
        'customer_name'    => $customerName,
        'customer_address' => $customerAddress,
        'worked_area'      => $workedArea,
        'checklist'        => $checklist,
        'signature_text'   => $signatureText,
        'signed_at'        => date('Y-m-d H:i:s'),
    ];

    $pdfBytes = SignoffPdfBuilder::build($business, ['id' => $orderId], $signoffData);

    $pdfConfig  = require __DIR__ . '/../config/pdf.php';
    $storageDir = rtrim($pdfConfig['storage_dir'], '/');
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0755, true);
    }
    $filename = 'signoff_order' . $orderId . '_' . time() . '.pdf';
    file_put_contents($storageDir . '/' . $filename, $pdfBytes);

    $signoffRepo->create([
        'order_id'              => $orderId,
        'signed_by_employee_id' => $employeeId,
        'customer_name'         => $customerName,
        'customer_address'      => $customerAddress,
        'worked_area'           => $workedArea,
        'checklist'             => $checklist,
        'signature_text'        => $signatureText,
        'pdf_filename'          => $filename,
    ]);

    $pdo->prepare("UPDATE fabrication_orders SET admin_status = 'completed' WHERE id = ?")->execute([$orderId]);

    notifySignoffCompleted($pdo, $orderId, $signoffData, $storageDir . '/' . $filename, $filename);

    header('Location: ../employee/order-view.php?id=' . $orderId . '&signoff=success');
    exit;
} catch (Throwable $e) {
    Logger::exception($e, 'signoff');
    header('Location: ../employee/order-signoff.php?id=' . $orderId . '&error=server_error');
    exit;
}
