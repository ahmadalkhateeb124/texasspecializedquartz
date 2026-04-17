<?php
// download.php
if (empty($_GET['file'])) {
    http_response_code(400);
    exit('No file specified.');
}

$file = '../assets/products/' . basename($_GET['file']);
if (!file_exists($file)) {
    http_response_code(404);
    exit('File not found.');
}

// تحديد MIME type مناسب حسب الامتداد
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'txt' => 'text/plain',
    // أضف أي امتداد آخر
];
$mime = $mimeTypes[$ext] ?? 'application/octet-stream';

// إرسال رؤوس HTTP لإجبار التحميل
header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));

// إرسال الملف للمستخدم
readfile($file);
exit;
