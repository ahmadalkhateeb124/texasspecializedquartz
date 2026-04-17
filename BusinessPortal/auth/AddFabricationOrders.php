<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

/* ===================== AUTH ===================== */
$user_id    = isAdmin()    ? ($_SESSION['user_id']    ?? null) : null;
$account_id = isCustomer() ? ($_SESSION['account_id'] ?? null) : null;

$response = [
    'success'     => false,
    'message'     => '',
    'errors'      => [],
    'email_debug' => []
];

if (!isLoggedIn()) {
    http_response_code(401);
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

// Check customer account status
if ($account_id && !isAdmin()) {
    $stmt = $pdo->prepare("SELECT status FROM accounts WHERE id = ?");
    $stmt->execute([$account_id]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$account || $account['status'] !== 'Active') {
        http_response_code(403);
        $response['message'] = 'Account access denied.';
        echo json_encode($response);
        exit;
    }
}

/* ===================== POST ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name   = trim($_POST['customer_name'] ?? '');
    $phone           = trim($_POST['phone'] ?? '');
    $address         = trim($_POST['address'] ?? '');
    $sales_rep       = trim($_POST['sales_rep'] ?? '');
    $sales_rep_phone = trim($_POST['sales_rep_phone'] ?? '');
    $city            = trim($_POST['city'] ?? '');
    $zip_code        = trim($_POST['ZipCode'] ?? '');
    $po_number       = trim($_POST['po_number'] ?? '');
    $notes           = trim($_POST['notes'] ?? '');

    /* ===================== VALIDATION ===================== */
    $errors = [];
    if (empty($customer_name)) $errors[] = 'Customer name is required';
    if (empty($phone)) $errors[] = 'Phone number is required';
    if (empty($address)) $errors[] = 'Address is required';
    if (empty($city)) $errors[] = 'City is required';

    if (!empty($errors)) {
        $response['message'] = 'Validation failed';
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }

    /* ===================== FILE UPLOAD ===================== */
    $image = '';
    $uploaded_file_path = '';
    $uploaded_file_name = '';

    if (!empty($_FILES['image']['name'])) {
        $file_name  = time() . '_' . basename($_FILES['image']['name']);
        $target_dir = __DIR__ . '/../assets/products/';

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if ($_FILES['image']['size'] > 20 * 1024 * 1024) {
            $response['message'] = 'File too large. Max 20MB.';
            echo json_encode($response);
            exit;
        }

        $allowedTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'application/zip'
        ];

        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $response['message'] = 'Invalid file type.';
            echo json_encode($response);
            exit;
        }

        $target_file = $target_dir . $file_name;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $response['message'] = 'Failed to upload file.';
            echo json_encode($response);
            exit;
        }

        $image = $file_name;
        $uploaded_file_path = $target_file;
        $uploaded_file_name = $_FILES['image']['name'];
    }

    try {
        /* ===================== USER INFO ===================== */
        $user_email = '';
        $user_fullname = '';

        if ($user_id) {
            $stmt = $pdo->prepare("SELECT email, fullname FROM users WHERE id=?");
            $stmt->execute([$user_id]);
            $u = $stmt->fetch();
            if ($u) {
                $user_email = $u['email'];
                $user_fullname = $u['fullname'];
            }
        } else {
            $stmt = $pdo->prepare("SELECT email, name FROM accounts WHERE id=?");
            $stmt->execute([$account_id]);
            $a = $stmt->fetch();
            if ($a) {
                $user_email = $a['email'];
                $user_fullname = $a['name'];
            }
        }

        /* ===================== MAIN ORDER ===================== */
        if ($user_id) {
            $stmt = $pdo->prepare("
                INSERT INTO fabrication_orders
                (user_id, customer_name, phone, address, sales_rep, sales_rep_phone, city, zip_code, po_number, notes, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $customer_name,
                $phone,
                $address,
                $sales_rep,
                $sales_rep_phone,
                $city,
                $zip_code,
                $po_number,
                $notes,
                $image
            ]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO fabrication_orders
                (account_id, customer_name, phone, address, sales_rep, sales_rep_phone, city, zip_code, po_number, notes, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $account_id,
                $customer_name,
                $phone,
                $address,
                $sales_rep,
                $sales_rep_phone,
                $city,
                $zip_code,
                $po_number,
                $notes,
                $image
            ]);
        }

        $order_id = $pdo->lastInsertId();

        /* ===================== JOB SECTIONS ===================== */
        $job_sections_data = [];
        if (!empty($_POST['job_types']) && is_array($_POST['job_types'])) {
            $job_stmt = $pdo->prepare("
                INSERT INTO job_sections
                (order_id, job_type, job_type_other, material_type, material_other,
                 thickness, thickness_custom, material_color,
                 sink_provider, sink_type, sink_style, sink_style_other,
                 edge_profile, edge_profile_custom, tear_out)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($_POST['job_types'] as $index => $job_type) {
                $job_type = trim($job_type);
                if (empty($job_type)) continue;

                $job_type_other = '';
                if ($job_type === 'other') {
                    $job_type_other = trim($_POST['job_type_other'][$index] ?? '');
                    if (empty($job_type_other)) continue;
                }

                $material_type = trim($_POST['material_type'][$index] ?? '');
                $material_other = trim($_POST['material_other'][$index] ?? '');
                $thickness = trim($_POST['material_thickness'][$index] ?? '');
                $thickness_custom = trim($_POST['material_thickness_custom'][$index] ?? '');
                $material_color = trim($_POST['material_color'][$index] ?? '');
                $sink_provider = trim($_POST['sink_provider'][$index] ?? '');
                $sink_type = trim($_POST['sink_type'][$index] ?? '');
                $sink_style = trim($_POST['sink_style'][$index] ?? '');
                $sink_style_other = trim($_POST['sink_style_other'][$index] ?? '');
                $edge_profile = trim($_POST['edge_profile'][$index] ?? '');
                $edge_profile_custom = trim($_POST['edge_profile_custom'][$index] ?? '');
                $tear_out = (isset($_POST['tear_out'][$index]) && $_POST['tear_out'][$index] === 'yes') ? 'yes' : 'no';

                $job_sections_data[] = [
                    'job_type' => $job_type,
                    'job_type_other' => $job_type_other,
                    'material_type' => $material_type,
                    'material_other' => $material_other,
                    'thickness' => $thickness,
                    'thickness_custom' => $thickness_custom,
                    'material_color' => $material_color,
                    'sink_provider' => $sink_provider,
                    'sink_type' => $sink_type,
                    'sink_style' => $sink_style,
                    'sink_style_other' => $sink_style_other,
                    'edge_profile' => $edge_profile,
                    'edge_profile_custom' => $edge_profile_custom,
                    'tear_out' => $tear_out
                ];

                $job_stmt->execute([
                    $order_id,
                    $job_type,
                    $job_type_other ?: null,
                    $material_type,
                    $material_other,
                    $thickness,
                    $thickness_custom,
                    $material_color,
                    $sink_provider,
                    $sink_type,
                    $sink_style,
                    $sink_style_other,
                    $edge_profile,
                    $edge_profile_custom,
                    $tear_out
                ]);
            }
        }

        /* ===================== EMAIL ===================== */
        $email_result = sendOrderEmail(
            $order_id,
            $customer_name,
            $phone,
            $address,
            $sales_rep,
            $sales_rep_phone,
            $city,
            $zip_code,
            $po_number,
            $notes,
            $user_email,
            $user_fullname,
            $job_sections_data,
            $uploaded_file_path,
            $uploaded_file_name
        );

        $response['success'] = true;
        $response['message'] = 'Order created successfully';
        $response['order_id'] = $order_id;
        $response['email_sent'] = $email_result['success'];
        $response['email_debug'] = $email_result['debug'];

        echo json_encode($response);
        exit;
    } catch (PDOException $e) {
        $response['message'] = 'Database Error: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }
}

function sendOrderEmail(
    $order_id,
    $customer_name,
    $phone,
    $address,
    $sales_rep,
    $sales_rep_phone,
    $city,
    $zip_code,
    $po_number,
    $notes,
    $user_email,
    $user_fullname,
    $job_sections_data = [],
    $attachment_path = '',
    $attachment_name = ''
) {
    $result = [
        'success' => false,
        'debug' => []
    ];

    $receiver = 'Cs@TexasSpecializedQuartz.com';
    $sender = 'inquiry@texasspecializedquartz.com';
    $passwd = 'Cs@texasspecializedquartz#1';

    $subject = "New Fabrication Order #$order_id - $customer_name";

    $result['debug']['receiver'] = $receiver;
    $result['debug']['sender'] = $sender;
    $result['debug']['order_id'] = $order_id;
    $result['debug']['attachment_exists'] = (!empty($attachment_path) && file_exists($attachment_path)) ? 'Yes' : 'No';
    $result['debug']['attachment_path'] = $attachment_path;
    $result['debug']['attachment_name'] = $attachment_name;

    $message = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Fabrication Order #$order_id</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                line-height: 1.6;
                color: #333;
                background-color: #f5f7fa;
                padding: 20px;
            }
            
            .email-container {
                max-width: 800px;
                margin: 0 auto;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            
            .email-header {
                background: #9f8054;
                padding: 30px 40px;
                text-align: center;
                color: white;
                position: relative;
            }
            
            .logo-placeholder {
                font-size: 32px;
                font-weight: bold;
                color: white;
                margin-bottom: 20px;
                display: inline-block;
                padding: 10px 20px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                border: 2px solid rgba(255, 255, 255, 0.3);
            }
            
            .order-title {
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 10px;
                color: white;
            }
            
            .order-id {
                font-size: 36px;
                font-weight: 700;
                color: #ffd54f;
                margin-bottom: 5px;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            }
            
            .order-date {
                font-size: 14px;
                opacity: 0.9;
                color: #e3f2fd;
            }
            
            .email-content {
                padding: 40px;
            }
            
            .section {
                margin-bottom: 30px;
                padding: 25px;
                background: #f8f9fa;
                border-radius: 10px;
                border-left: 5px solid #9f8054;
            }
            
            .section-title {
                font-size: 18px;
                font-weight: 600;
                color: #9f8054;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 15px;
            }
            
            .info-item {
                display: flex;
                flex-direction: column;
                padding: 10px;
                background: white;
                border-radius: 6px;
                border: 1px solid #e0e0e0;
                margin-bottom: 15px;
            }
            
            .info-label {
                font-size: 12px;
                font-weight: 600;
                color: #666;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 5px;
            }
            
            .info-value {
                font-size: 15px;
                font-weight: 500;
                color: #333;
            }
            
            .job-sections-container {
                display: grid;
                gap: 20px;
            }
            
            .job-section {
                background: white;
                border-radius: 8px;
                padding: 20px;
                border: 1px solid #e0e0e0;
                position: relative;
            }
            
            .job-section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 2px solid #e8eaf6;
            }
            
            .job-section-title {
                font-size: 16px;
                font-weight: 600;
                color: #9f8054;
            }
            
            .job-section-number {
                background: #9f8054;
                color: white;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 14px;
            }
            
            .job-details-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 12px;
            }
            
            .job-detail {
                padding: 8px 12px;
                background: #f5f7fa;
                border-radius: 6px;
                border-left: 3px solid #776852;
                margin-bottom: 15px;
            }
            
            .job-label {
                font-size: 12px;
                font-weight: 600;
                color: #666;
                margin-bottom: 3px;
            }
            
            .job-value {
                font-size: 14px;
                font-weight: 500;
                color: #333;
            }
            
            .custom-value {
                color: #d32f2f;
                font-weight: 600;
                font-style: italic;
            }
            
            .attachment-section {
                background: #e8f5e9;
                border-left-color: #4caf50;
            }
            
            .attachment-info {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 15px;
                background: white;
                border-radius: 8px;
                border: 1px solid #c8e6c9;
            }
            
            .attachment-details {
                flex: 1;
            }
            
            .attachment-name {
                font-weight: 600;
                color: #2e7d32;
                margin-bottom: 5px;
            }
            
            .attachment-meta {
                font-size: 12px;
                color: #666;
            }
            
            .notes-content {
                padding: 15px;
                background: white;
                border-radius: 8px;
                border: 1px solid #e0e0e0;
                font-size: 14px;
                line-height: 1.8;
                color: #555;
            }
            
            .email-footer {
                background: #9f8054;
                color: white;
                padding: 25px 40px;
                text-align: center;
            }
            
            .footer-logo {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 15px;
                color: #ffd54f;
            }
            
            .footer-info {
                font-size: 13px;
                opacity: 0.9;
                margin-bottom: 10px;
            }
            
            .footer-copyright {
                font-size: 12px;
                opacity: 0.7;
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .status-badge {
                display: inline-block;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .status-new {
                background: #e3f2fd;
                color: #837562;
            }
            
            @media (max-width: 600px) {
                .email-content {
                    padding: 20px;
                }
                
                .section {
                    padding: 15px;
                }
                
                .info-grid {
                    grid-template-columns: 1fr;
                }
                
                .job-details-grid {
                    grid-template-columns: 1fr;
                }
                .job-detail, .info-item {
                    margin-bottom: 10px;
                }
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                <div class='logo-placeholder'>Texas Specialized Quartz</div>
                <h1 class='order-title'>FABRICATION ORDER</h1>
                <div class='order-id'>#$order_id</div>
                <div class='order-date'>" . date('F j, Y \a\t g:i A') . "</div>
                <div style='margin-top: 15px;'>
                    <span class='status-badge status-new'>NEW ORDER</span>
                </div>
            </div>
            
            <div class='email-content'>
                <div class='section'>
                    <div class='section-title'>
                        Order Details
                    </div>
                    <div class='info-grid'>
                        <div class='info-item'>
                            <span class='info-label'>Order ID</span>
                            <span class='info-value'>#$order_id</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>Created By</span>
                            <span class='info-value'>$user_fullname</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>User Email</span>
                            <span class='info-value'>$user_email</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>Order Date</span>
                            <span class='info-value'>" . date('M d, Y') . "</span>
                        </div>
                    </div>
                </div>
                
                <div class='section'>
                    <div class='section-title'>
                        Customer Information
                    </div>
                    <div class='info-grid'>
                        <div class='info-item'>
                            <span class='info-label'>Customer Name</span>
                            <span class='info-value'>$customer_name</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>Phone Number</span>
                            <span class='info-value'>$phone</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>Address</span>
                            <span class='info-value'>$address</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>City</span>
                            <span class='info-value'>$city</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>Zip Code</span>
                            <span class='info-value'>$zip_code</span>
                        </div>
                    </div>
                </div>
                
                <div class='section'>
                    <div class='section-title'>
                        Sales Information
                    </div>
                    <div class='info-grid'>
                        <div class='info-item'>
                            <span class='info-label'>Sales Representative</span>
                            <span class='info-value'>" . ($sales_rep ?: 'Not specified') . "</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>Sales Rep Phone</span>
                            <span class='info-value'>" . ($sales_rep_phone ?: 'Not specified') . "</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>PO Number</span>
                            <span class='info-value'>" . ($po_number ?: 'Not specified') . "</span>
                        </div>
                    </div>
                </div>
    ";

    if (!empty($job_sections_data)) {
        $message .= "
                <div class='section'>
                    <div class='section-title'>
                        Job Sections (" . count($job_sections_data) . ")
                    </div>
                    <div class='job-sections-container'>
        ";

        foreach ($job_sections_data as $index => $job) {
            $job_type_display = ucfirst($job['job_type']);
            if ($job['job_type'] == 'other' && !empty($job['job_type_other'])) {
                $job_type_display = htmlspecialchars($job['job_type_other']);
            }

            $message .= "
                        <div class='job-section'>
                            <div class='job-section-header'>
                                <div class='job-section-title'>$job_type_display</div>
                                <div class='job-section-number'>" . ($index + 1) . "</div>
                            </div>
                            <div class='job-details-grid'>
            ";

            $material_display = ucfirst(htmlspecialchars($job['material_type']));
            if (!empty($job['material_other'])) {
                $material_display .= " <span class='custom-value'>(" . htmlspecialchars($job['material_other']) . ")</span>";
            }

            $message .= "
                                <div class='job-detail'>
                                    <div class='job-label'>Material Type</div>
                                    <div class='job-value'>$material_display</div>
                                </div>
            ";

            if (!empty($job['material_color'])) {
                $message .= "
                                <div class='job-detail'>
                                    <div class='job-label'>Material Color</div>
                                    <div class='job-value'>" . htmlspecialchars($job['material_color']) . "</div>
                                </div>
                ";
            }

            $thickness_display = '';
            if ($job['thickness'] == 'custom' && !empty($job['thickness_custom'])) {
                $thickness_display = "Custom: <span class='custom-value'>" . htmlspecialchars($job['thickness_custom']) . " cm</span>";
            } elseif ($job['thickness'] == '20') {
                $thickness_display = "2 cm";
            } elseif ($job['thickness'] == '30') {
                $thickness_display = "3 cm";
            } elseif (!empty($job['thickness'])) {
                $thickness_display = htmlspecialchars($job['thickness']);
            }

            if ($thickness_display) {
                $message .= "
                                <div class='job-detail'>
                                    <div class='job-label'>Thickness</div>
                                    <div class='job-value'>$thickness_display</div>
                                </div>
                ";
            }

            if (!empty($job['sink_provider'])) {
                $message .= "
                                <div class='job-detail'>
                                    <div class='job-label'>Sink Provider</div>
                                    <div class='job-value'>" . ucfirst(htmlspecialchars($job['sink_provider'])) . "</div>
                                </div>
                ";
            }

            if (!empty($job['sink_type'])) {
                $message .= "
                                <div class='job-detail'>
                                    <div class='job-label'>Sink Type</div>
                                    <div class='job-value'>" . ucfirst(htmlspecialchars($job['sink_type'])) . "</div>
                                </div>
                ";
            }

            if ($job['sink_provider'] == 'aa_granite' && !empty($job['sink_style'])) {
                $sink_style_display = ucfirst(str_replace('_', ' ', htmlspecialchars($job['sink_style'])));
                if (!empty($job['sink_style_other'])) {
                    $sink_style_display .= " <span class='custom-value'>(" . htmlspecialchars($job['sink_style_other']) . ")</span>";
                }

                $message .= "
                                <div class='job-detail'>
                                    <div class='job-label'>Sink Style</div>
                                    <div class='job-value'>$sink_style_display</div>
                                </div>
                ";
            }

            if (!empty($job['edge_profile'])) {
                $edge_display = ucfirst(htmlspecialchars($job['edge_profile']));
                if (!empty($job['edge_profile_custom'])) {
                    $edge_display .= " <span class='custom-value'>(" . htmlspecialchars($job['edge_profile_custom']) . ")</span>";
                }

                $message .= "
                                <div class='job-detail'>
                                    <div class='job-label'>Edge Profile</div>
                                    <div class='job-value'>$edge_display</div>
                                </div>
                ";
            }

            $tear_out_display = ucfirst($job['tear_out']);
            $tear_out_class = ($job['tear_out'] == 'yes') ? 'custom-value' : '';

            $message .= "
                                <div class='job-detail'>
                                    <div class='job-label'>Tear Out Required</div>
                                    <div class='job-value $tear_out_class'><strong>$tear_out_display</strong></div>
                                </div>
            ";

            $message .= "
                            </div>
                        </div>
            ";
        }

        $message .= "
                    </div>
                </div>
        ";
    } else {
        $message .= "
                <div class='section'>
                    <div class='section-title'>
                        Job Sections
                    </div>
                    <div style='text-align: center; padding: 30px; color: #666;'>
                        No job sections added to this order
                    </div>
                </div>
        ";
    }

    if (!empty($attachment_name) && !empty($attachment_path) && file_exists($attachment_path)) {
        $file_size = filesize($attachment_path);
        $file_size_formatted = formatFileSize($file_size);
        $file_extension = strtolower(pathinfo($attachment_name, PATHINFO_EXTENSION));

        $message .= "
                <div class='section attachment-section'>
                    <div class='section-title'>
                        Attached File
                    </div>
                    <div class='attachment-info'>
                        <div class='attachment-details'>
                            <div class='attachment-name'>$attachment_name</div>
                            <div class='attachment-meta'>
                                " . strtoupper($file_extension) . " file - $file_size_formatted
                            </div>
                        </div>
                    </div>
                </div>
        ";
    }

    $message .= "
                <div class='section'>
                    <div class='section-title'>
                        Additional Notes
                    </div>
                    <div class='notes-content'>
                        " . nl2br(htmlspecialchars($notes ?: 'No additional notes provided.')) . "
                    </div>
                </div>
            </div>
            
            <div class='email-footer'>
                <div class='footer-logo'>Texas Specialized Quartz</div>
                <div class='footer-info'>
                    Professional Fabrication Services
                </div>
                <div class='footer-info'>
                    This email was automatically generated by the Fabrication Order System
                </div>
                <div class='footer-copyright'>
                    &copy; " . date('Y') . " Texas Specialized Quartz. All rights reserved.
                </div>
            </div>
        </div>
    </body>
    </html>
    ";

    try {
        $email_result = sendEmailWithPHPMailer(
            $receiver,
            $subject,
            $message,
            $sender,
            $passwd,
            $user_email,
            $user_fullname,
            $attachment_path,
            $attachment_name
        );

        $result['debug'] = array_merge($result['debug'], $email_result['debug']);
        $result['success'] = $email_result['success'];

        error_log("Order Email Attempt - Order ID: $order_id, To: $receiver, From: $sender, Success: " .
            ($result['success'] ? 'Yes' : 'No') .
            ", Debug: " . json_encode($result['debug']));

        return $result;
    } catch (Exception $e) {
        $result['debug']['exception'] = $e->getMessage();
        error_log("Email sending error: " . $e->getMessage());
        return $result;
    }
}

function sendEmailWithPHPMailer(
    $receiver,
    $subject,
    $message,
    $sender,
    $passwd,
    $reply_to_email,
    $reply_to_name = '',
    $attachment_path = '',
    $attachment_name = ''
) {
    $result = [
        'success' => false,
        'debug' => []
    ];

    try {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $phpmailer_path = __DIR__ . '/../../PHPMail/src/PHPMailer.php';
            if (file_exists($phpmailer_path)) {
                require_once $phpmailer_path;
                require_once __DIR__ . '/../../PHPMail/src/SMTP.php';
                require_once __DIR__ . '/../../PHPMail/src/Exception.php';
            } else {
                $result['debug']['phpmailer_status'] = 'PHPMailer not found at: ' . $phpmailer_path;
                return sendEmailFallback(
                    $receiver,
                    $subject,
                    $message,
                    $sender,
                    'Texas Specialized Quartz',
                    $reply_to_email,
                    $reply_to_name,
                    $attachment_path,
                    $attachment_name
                );
            }
        }

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $sender;
        $mail->Password   = $passwd;
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';
        $mail->isHTML(true);
        $mail->SMTPDebug  = 0;

        $mail->setFrom($sender, 'Texas Specialized Quartz');
        $mail->addAddress($receiver);

        if (!empty($reply_to_email)) {
            $mail->addReplyTo($reply_to_email, $reply_to_name ?: $reply_to_email);
        }

        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);

        if (!empty($attachment_path) && file_exists($attachment_path)) {
            $mail->addAttachment($attachment_path, $attachment_name);
            $result['debug']['attachment_added'] = 'Yes';
        }

        $mail->send();

        $result['success'] = true;
        $result['debug']['status'] = 'Email sent successfully via PHPMailer';
        $result['debug']['smtp_host'] = $mail->Host;
        $result['debug']['smtp_port'] = $mail->Port;
        $result['debug']['encryption'] = $mail->SMTPSecure;
        $result['debug']['sender'] = $sender;
        $result['debug']['receiver'] = $receiver;
    } catch (Exception $e) {
        $result['debug']['error'] = "PHPMailer Error: " . ($mail->ErrorInfo ?? $e->getMessage());
        $result['debug']['exception'] = $e->getMessage();

        $result['debug']['fallback'] = 'Trying fallback method';
        $fallback_result = sendEmailFallback(
            $receiver,
            $subject,
            $message,
            $sender,
            'Texas Specialized Quartz',
            $reply_to_email,
            $reply_to_name,
            $attachment_path,
            $attachment_name
        );
        $result['success'] = $fallback_result['success'];
        $result['debug']['fallback_result'] = $fallback_result['debug'];
    }

    return $result;
}

function sendEmailFallback(
    $to,
    $subject,
    $message,
    $from_email,
    $from_name = '',
    $reply_to_email = '',
    $reply_to_name = '',
    $attachment_path = '',
    $attachment_name = ''
) {
    $result = [
        'success' => false,
        'debug' => []
    ];

    if (empty($attachment_path) || !file_exists($attachment_path)) {
        return sendSimpleEmail($to, $subject, $message, $from_email, $from_name, $reply_to_email, $reply_to_name);
    }

    return sendMultipartEmail(
        $to,
        $subject,
        $message,
        $from_email,
        $from_name,
        $reply_to_email,
        $reply_to_name,
        $attachment_path,
        $attachment_name
    );
}

function sendSimpleEmail(
    $to,
    $subject,
    $message,
    $from_email,
    $from_name = '',
    $reply_to_email = '',
    $reply_to_name = ''
) {
    $result = [
        'success' => false,
        'debug' => []
    ];

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $from_name <$from_email>" . "\r\n";

    if (!empty($reply_to_email)) {
        $reply_to_display = !empty($reply_to_name) ? $reply_to_name : $reply_to_email;
        $headers .= "Reply-To: $reply_to_display <$reply_to_email>" . "\r\n";
    }

    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    $result['debug']['headers'] = $headers;
    $result['debug']['subject'] = $subject;
    $result['debug']['to'] = $to;
    $result['debug']['from'] = $from_email;
    $result['debug']['from_name'] = $from_name;
    $result['debug']['reply_to'] = $reply_to_email;
    $result['debug']['method'] = 'Simple mail() fallback';

    if (!function_exists('mail')) {
        $result['debug']['error'] = 'mail() function is not available';
        return $result;
    }

    $subject_encoded = "=?UTF-8?B?" . base64_encode($subject) . "?=";

    try {
        $mail_sent = @mail($to, $subject_encoded, $message, $headers, "-f$from_email");

        if ($mail_sent) {
            $result['success'] = true;
            $result['debug']['status'] = 'Email sent successfully via fallback';
        } else {
            $result['debug']['status'] = 'Email sending failed via fallback';
            $error = error_get_last();
            if ($error) {
                $result['debug']['last_error'] = $error['message'];
            }
        }
    } catch (Exception $e) {
        $result['debug']['exception'] = $e->getMessage();
    }

    return $result;
}

function sendMultipartEmail(
    $to,
    $subject,
    $message,
    $from_email,
    $from_name = '',
    $reply_to_email = '',
    $reply_to_name = '',
    $attachment_path,
    $attachment_name
) {
    $result = [
        'success' => false,
        'debug' => []
    ];

    if (!file_exists($attachment_path) || !is_readable($attachment_path)) {
        $result['debug']['error'] = "Attachment file not accessible: $attachment_path";
        return sendSimpleEmail($to, $subject, $message, $from_email, $from_name, $reply_to_email, $reply_to_name);
    }

    $file_content = file_get_contents($attachment_path);
    if ($file_content === false) {
        $result['debug']['error'] = "Failed to read attachment file";
        return sendSimpleEmail($to, $subject, $message, $from_email, $from_name, $reply_to_email, $reply_to_name);
    }

    $file_size = filesize($attachment_path);
    $file_content = chunk_split(base64_encode($file_content));

    $file_extension = strtolower(pathinfo($attachment_name, PATHINFO_EXTENSION));
    $mime_types = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'txt'  => 'text/plain',
        'zip'  => 'application/zip'
    ];

    $mime_type = $mime_types[$file_extension] ?? 'application/octet-stream';

    $boundary = md5(uniqid(time()));
    $boundary_mixed = "mixed-$boundary";
    $boundary_alt = "alt-$boundary";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: $from_name <$from_email>\r\n";

    if (!empty($reply_to_email)) {
        $reply_to_display = !empty($reply_to_name) ? $reply_to_name : $reply_to_email;
        $headers .= "Reply-To: $reply_to_display <$reply_to_email>\r\n";
    }

    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary_mixed\"\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    $body = "--$boundary_mixed\r\n";
    $body .= "Content-Type: multipart/alternative; boundary=\"$boundary_alt\"\r\n\r\n";

    $body .= "--$boundary_alt\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= strip_tags($message) . "\r\n\r\n";

    $body .= "--$boundary_alt\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message . "\r\n\r\n";

    $body .= "--$boundary_alt--\r\n\r\n";

    $body .= "--$boundary_mixed\r\n";
    $body .= "Content-Type: $mime_type; name=\"$attachment_name\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$attachment_name\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= $file_content . "\r\n\r\n";
    $body .= "--$boundary_mixed--";

    $result['debug']['method'] = 'Multipart mail() fallback';
    $result['debug']['boundary'] = $boundary;
    $result['debug']['mime_type'] = $mime_type;
    $result['debug']['file_size'] = $file_size;

    if (!function_exists('mail')) {
        $result['debug']['error'] = 'mail() function is not available';
        return $result;
    }

    $subject_encoded = "=?UTF-8?B?" . base64_encode($subject) . "?=";

    try {
        $mail_sent = @mail($to, $subject_encoded, $body, $headers, "-f$from_email");

        if ($mail_sent) {
            $result['success'] = true;
            $result['debug']['status'] = 'Email with attachment sent successfully via fallback';
        } else {
            $result['debug']['status'] = 'Email with attachment sending failed via fallback';
            $error = error_get_last();
            if ($error) {
                $result['debug']['last_error'] = $error['message'];
            }

            $result['debug']['fallback'] = 'Trying without attachment';
            $fallback_result = sendSimpleEmail($to, $subject, $message, $from_email, $from_name, $reply_to_email, $reply_to_name);
            $result['success'] = $fallback_result['success'];
            $result['debug']['fallback_result'] = $fallback_result['debug'];
        }
    } catch (Exception $e) {
        $result['debug']['exception'] = $e->getMessage();
    }

    return $result;
}

function formatFileSize($bytes)
{
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}