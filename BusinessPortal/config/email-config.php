// <?php
// /**
//  * Secure Email Configuration for Granite Artists
//  *
//  * IMPORTANT: Never commit this file with real credentials to version control
//  * Use environment variables or separate configuration files for production
//  */

// // Email Configuration Constants
// define('EMAIL_ENABLED', true);
// define('EMAIL_DEBUG', false); // Set to true for development, false for production

// // SMTP Configuration (Recommended for production)
// define('SMTP_HOST', 'smtp.hostinger.com'); // Change to your SMTP server
// define('SMTP_PORT', 587);
// define('SMTP_ENCRYPTION', 'tls'); // 'ssl' or 'tls'
// define('SMTP_USERNAME', 'inquiry@graniteartiststx.com'); // Use environment variable in production
// define('SMTP_PASSWORD', '0781140465@Inquiry'); // Use environment variable in production

// // Email Address Configuration
// define('EMAIL_FROM_ADDRESS', 'inquiry@graniteartiststx.com');
// define('EMAIL_FROM_NAME', 'Granite Artists');
// define('EMAIL_ADMIN_ADDRESS', 'cs@webkoit.com');
// define('EMAIL_SUPPORT_ADDRESS', 'Cs@TexasSpecializedQuartz.com');

// // Security Settings
// define('EMAIL_MAX_ATTACHMENT_SIZE', 20971520); // 20MB in bytes
// define('EMAIL_ALLOWED_ATTACHMENT_TYPES', [
//     'image/jpeg',
//     'image/jpg',
//     'image/png',
//     'image/gif',
//     'image/webp',
//     'application/pdf',
//     'application/msword',
//     'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
//     'application/vnd.ms-excel',
//     'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//     'text/plain',
//     'application/zip'
// ]);

// // Rate Limiting (Prevent email abuse)
// define('EMAIL_RATE_LIMIT_PER_HOUR', 50);
// define('EMAIL_RATE_LIMIT_PER_DAY', 200);

// // Logging Configuration
// define('EMAIL_LOG_ENABLED', true);
// define('EMAIL_LOG_FILE', __DIR__ . '/../logs/email.log');

// // Validation Functions
// function isValidEmail($email) {
//     return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
// }

// function isAllowedAttachmentType($mime_type) {
//     return in_array($mime_type, EMAIL_ALLOWED_ATTACHMENT_TYPES);
// }

// function isWithinSizeLimit($file_size) {
//     return $file_size <= EMAIL_MAX_ATTACHMENT_SIZE;
// }

// // Security Headers for Email
// function getSecureEmailHeaders($from_email, $from_name = '') {
//     $headers = [];

//     // Basic headers
//     $headers[] = 'MIME-Version: 1.0';
//     $headers[] = 'Content-type:text/html;charset=UTF-8';

//     // From header with validation
//     $from_address = isValidEmail(EMAIL_FROM_ADDRESS) ? EMAIL_FROM_ADDRESS : 'noreply@graniteartiststx.com';
//     $from_display = EMAIL_FROM_NAME ?: 'Granite Artists';
//     $headers[] = "From: $from_display <$from_address>";

//     // Reply-To with validation
//     if (isValidEmail($from_email)) {
//         $reply_to_name = !empty($from_name) ? "=?UTF-8?B?" . base64_encode($from_name) . "?=" : $from_email;
//         $headers[] = "Reply-To: $reply_to_name <$from_email>";
//     }

//     // Security headers
//     $headers[] = 'X-Mailer: PHP/' . phpversion();
//     $headers[] = 'X-Priority: 1 (Highest)';
//     $headers[] = 'Importance: High';
//     $headers[] = 'X-Content-Type-Options: nosniff';
//     $headers[] = 'X-Auto-Response-Suppress: All';

//     return implode("\r\n", $headers);
// }

// // Logging Function
// function logEmailEvent($event, $details = []) {
//     if (!EMAIL_LOG_ENABLED) {
//         return;
//     }

//     $log_dir = dirname(EMAIL_LOG_FILE);
//     if (!is_dir($log_dir)) {
//         mkdir($log_dir, 0755, true);
//     }

//     $log_entry = [
//         'timestamp' => date('Y-m-d H:i:s'),
//         'event' => $event,
//         'details' => $details,
//         'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
//         'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
//     ];

//     file_put_contents(
//         EMAIL_LOG_FILE,
//         json_encode($log_entry) . PHP_EOL,
//         FILE_APPEND | LOCK_EX
//     );
// }

// // Rate Limiting Function
// function checkEmailRateLimit($user_id) {
//     // Implement rate limiting logic here
//     // This is a basic example - implement proper rate limiting for production
//     return true; // Temporarily disabled for development
// }

// // Email Template Helper
// function getEmailTemplate($template_name, $data = []) {
//     $templates = [
//         'order_confirmation' => [
//             'subject' => 'Fabrication Order #{order_id} Confirmation',
//             'template' => __DIR__ . '/../templates/email/order-confirmation.html'
//         ],
//         'admin_notification' => [
//             'subject' => 'New Fabrication Order #{order_id} - {customer_name}',
//             'template' => __DIR__ . '/../templates/email/admin-notification.html'
//         ]
//     ];

//     if (!isset($templates[$template_name])) {
//         return null;
//     }

//     $template = $templates[$template_name];

//     // Load template file if it exists
//     if (file_exists($template['template'])) {
//         $content = file_get_contents($template['template']);

//         // Replace placeholders with actual data
//         foreach ($data as $key => $value) {
//             $content = str_replace('{' . $key . '}', htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $content);
//             $template['subject'] = str_replace('{' . $key . '}', $value, $template['subject']);
//         }

//         return [
//             'subject' => $template['subject'],
//             'body' => $content
//         ];
//     }

//     return null;
// }