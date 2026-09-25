<?php

/**
 * auth/AddFabricationOrders.php
 * Creates a fabrication order + job sections, saves uploaded attachment,
 * and sends the admin notification email.
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'errors' => []];

/* ── Auth guard ─────────────────────────────────────────────── */
if (!isLoggedIn()) {
    http_response_code(401);
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$user_id    = isAdmin()    ? ($_SESSION['user_id']    ?? null) : null;
$account_id = isCustomer() ? ($_SESSION['account_id'] ?? null) : null;

if ($account_id && !isAdmin()) {
    $stmt = $pdo->prepare("SELECT status FROM accounts WHERE id = ?");
    $stmt->execute([$account_id]);
    $account = $stmt->fetch();
    if (!$account || $account['status'] !== 'Active') {
        http_response_code(403);
        $response['message'] = 'Account access denied.';
        echo json_encode($response);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response['message'] = 'Method not allowed.';
    echo json_encode($response);
    exit;
}

/* ── Input + validation ─────────────────────────────────────── */
$input = [
    'customer_name'   => trim($_POST['customer_name']   ?? ''),
    'phone'           => trim($_POST['phone']           ?? ''),
    'address'         => trim($_POST['address']         ?? ''),
    'sales_rep'       => trim($_POST['sales_rep']       ?? ''),
    'sales_rep_phone' => trim($_POST['sales_rep_phone'] ?? ''),
    'city'            => trim($_POST['city']            ?? ''),
    'zip_code'        => trim($_POST['ZipCode']         ?? ''),
    'po_number'       => trim($_POST['po_number']       ?? ''),
    'notes'           => trim($_POST['notes']           ?? ''),
];

$errors = [];
if (!$input['customer_name']) $errors[] = 'Customer name is required';
if (!$input['phone'])         $errors[] = 'Phone number is required';
if (!$input['address'])       $errors[] = 'Address is required';
if (!$input['city'])          $errors[] = 'City is required';
if (empty($_POST['job_types']) || !is_array($_POST['job_types'])) {
    $errors[] = 'Select at least one job area (Kitchen, Bathroom, etc.) and fill in its details.';
}

if ($errors) {
    $response['message'] = 'Validation failed';
    $response['errors']  = $errors;
    echo json_encode($response);
    exit;
}

/* ── File upload ────────────────────────────────────────────── */
$uploaded = null;
try {
    if (!empty($_FILES['image']['name'])) {
        $uploader = new FileUploader(__DIR__ . '/../assets/products');
        $uploaded = $uploader->save($_FILES['image']);
        $input['image'] = $uploaded['stored_name'];
    } else {
        $input['image'] = '';
    }
} catch (RuntimeException $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit;
}

/* ── Collect job sections ────────────────────────────────────── */
$jobs = [];
if (!empty($_POST['job_types']) && is_array($_POST['job_types'])) {
    foreach ($_POST['job_types'] as $i => $type) {
        $type = trim($type);
        if (!$type) continue;
        $otherType = trim($_POST['job_type_other'][$i] ?? '');
        if ($type === 'other' && !$otherType) continue;

        $jobs[] = [
            'job_type'            => $type,
            'job_type_other'      => $otherType,
            'material_type'       => trim($_POST['material_type'][$i]            ?? ''),
            'material_other'      => trim($_POST['material_other'][$i]           ?? ''),
            'thickness'           => trim($_POST['thickness'][$i]        ?? ''),
            'thickness_custom'    => trim($_POST['thickness_custom'][$i] ?? ''),
            'material_color'      => trim($_POST['material_color'][$i]           ?? ''),
            'sink_provider'       => trim($_POST['sink_provider'][$i]            ?? ''),
            'sink_type'           => trim($_POST['sink_type'][$i]                ?? ''),
            'sink_style'          => trim($_POST['sink_style'][$i]               ?? ''),
            'sink_style_other'    => trim($_POST['sink_style_other'][$i]         ?? ''),
            'edge_profile'        => trim($_POST['edge_profile'][$i]             ?? ''),
            'edge_profile_custom' => trim($_POST['edge_profile_custom'][$i]      ?? ''),
            'tear_out'            => (($_POST['tear_out'][$i] ?? '') === 'yes') ? 'yes' : 'no',
        ];
    }
}

/* ── Persist ─────────────────────────────────────────────────── */
try {
    $orderData = $input + ['user_id' => $user_id, 'account_id' => $account_id];
    $orderRepo = new OrderRepository($pdo);
    $orderId   = $orderRepo->create($orderData, $jobs);
} catch (PDOException $e) {
    $response['message'] = 'Database Error: ' . $e->getMessage();
    echo json_encode($response);
    exit;
}

/* ── Look up sender (for email "From" / reply-to) ────────────── */
$senderEmail = '';
$senderName  = '';
if ($user_id) {
    $s = $pdo->prepare("SELECT email, fullname FROM users WHERE id = ?");
    $s->execute([$user_id]);
    if ($row = $s->fetch()) { $senderEmail = $row['email']; $senderName = $row['fullname']; }
} elseif ($account_id) {
    $s = $pdo->prepare("SELECT email, name FROM accounts WHERE id = ?");
    $s->execute([$account_id]);
    if ($row = $s->fetch()) { $senderEmail = $row['email']; $senderName = $row['name']; }
}

/* ── Build + send notification email ─────────────────────────── */
$mailConfig = require __DIR__ . '/../config/mail.php';
$email = ['success' => false, 'debug' => ['skipped' => 'mail disabled']];

if (!empty($mailConfig['enabled'])) {
    if (empty($mailConfig['password'])) {
        Logger::error('order.mail.no_password', [
            'order_id'    => $orderId,
            'config_file' => is_file(__DIR__ . '/../config/mail.credentials.php') ? 'found' : 'MISSING',
        ]);
        $email = ['success' => false, 'debug' => ['error' => 'SMTP password is empty']];
    } else {
        $built = OrderEmailBuilder::build(
            array_merge($input, [
                'id' => $orderId,
                'user_email' => $senderEmail,
                'user_fullname' => $senderName
            ]),
            $jobs,
            $uploaded ? [
                'name' => $uploaded['original_name'],
                'path' => $uploaded['full_path']
            ] : null
        );

        $mailer = new Mailer($mailConfig);

        // 👇 الإيميلات الخاصة بالأوردر فقط
        $emails = [
            $mailConfig['admin_email'], // الأساسي
            'jay@texasspecializedquartz.com' // الإيميل الثاني
        ];

        $results = [];

        foreach ($emails as $to) {
            $result = $mailer->send(
                $to,
                $built['subject'],
                $built['body'],
                [
                    'reply_to_email'  => $senderEmail,
                    'reply_to_name'   => $senderName,
                    'attachment_path' => $uploaded['full_path']     ?? '',
                    'attachment_name' => $uploaded['original_name'] ?? '',
                ]
            );

            $results[] = $result;
        }

        // تحقق إذا كل الإيميلات انرسلت
        $emailSuccess = true;
        foreach ($results as $r) {
            if (!$r['success']) {
                $emailSuccess = false;
                break;
            }
        }

        $email = [
            'success' => $emailSuccess,
            'debug'   => $results
        ];

        if (!$emailSuccess) {
            Logger::error('order.mail.failed', [
                'order_id' => $orderId,
                'to'       => $emails,
                'debug'    => $results,
            ]);
        } else {
            Logger::info('order.mail.sent', [
                'order_id' => $orderId,
                'to'       => $emails,
            ]);
        }
    }
}

$response['success']     = true;
$response['message']     = 'Order created successfully';
$response['order_id']    = $orderId;
$response['email_sent']  = $email['success'];
$response['email_debug'] = $email['debug'];
echo json_encode($response);
