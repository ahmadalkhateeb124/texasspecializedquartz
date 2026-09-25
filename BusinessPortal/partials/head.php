<?php
require_once __DIR__ . '/../partials/conn.php';
require_once __DIR__ . '/../src/session.php';

$user_id    = $_SESSION['user_id'] ?? null;
$account_id = $_SESSION['account_id'] ?? null;

/* =========================
   GLOBAL AVATAR (User Only)
   صورة واحدة ثابتة للجميع
========================= */
$globalAvatar = 'https://picsum.photos/120/120';

$stmt = $pdo->query("SELECT avatar FROM users LIMIT 1");
$avatarRow = $stmt->fetch(PDO::FETCH_ASSOC);
if ($avatarRow && !empty($avatarRow['avatar'])) {
    $globalAvatar = $avatarRow['avatar'];
}

/* =========================
   Default User Data
========================= */
$user = [
        'fullname'   => 'Granit Artist',
        'email'      => '',
        'avatar'     => $globalAvatar, // 👈 صورة ثابتة
        'company'    => '',
        'address'    => 'N/A',
        'phone'      => 'N/A',
        'created_at' => null
];

$remainingDays = -1;

/* =========================
   USERS (مستخدم عادي)
========================= */
if ($user_id) {

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($u) {
        $user['fullname']   = $u['fullname'];
        $user['email']      = $u['email'];
        $user['company']      = $u['company'];
        $user['address']    = $u['address'] ?? 'N/A';
        $user['phone']      = $u['phone'] ?? 'N/A';
        $user['website']      = $u['website'] ?? 'N/A';
        $user['designation']      = $u['designation'] ?? 'N/A';
        $user['about']      = $u['about'] ?? 'N/A';
        $user['username']      = $u['username'] ?? 'N/A';
        $user['created_at'] = $u['created_at'];
    }
}

/* =========================
   ACCOUNTS + COMPANY
========================= */
elseif ($account_id) {

    $stmt = $pdo->prepare("
        SELECT 
            a.name,
            a.email,
            c.company_name,
            c.phone,
            c.address,
            c.created_at
        FROM accounts a
        JOIN customers_companies c ON c.id = a.company_id
        WHERE a.id = ?
    ");
    $stmt->execute([$account_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $user['fullname']   = $data['name'];          // اسم الحساب
        $user['email']      = $data['email'];
        $user['company']    = $data['company_name'];  // اسم الشركة
        $user['phone']      = $data['phone'];
        $user['address']    = $data['address'];
        $user['created_at'] = $data['created_at'];
    }
}

/* =========================
   Subscription (1 Year)
========================= */
if ($user['created_at']) {
    $created = new DateTime($user['created_at']);
    $expiry  = (clone $created)->modify('+1 year');
    $now     = new DateTime();

    $remainingDays = (int)$now->diff($expiry)->format('%r%a');
}

/* =========================
   UI Status
========================= */
$bgClass = 'bg-light';
$textClass = 'text-dark';
$displayText = 'Free Plan';

if ($remainingDays >= 0) {
    if ($remainingDays <= 7) {
        $bgClass = 'bg-warning-light';
        $textClass = 'text-warning';
        $displayText = "Expiring in $remainingDays days";
    } else {
        $bgClass = 'bg-success-light';
        $textClass = 'text-success';
        $displayText = "Active ($remainingDays days left)";
    }
} else {
    $bgClass = 'bg-danger-light';
    $textClass = 'text-danger';
    $displayText = 'Expired';
}
?>



<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="maryinparis" />
    <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
    <!--! BEGIN: Apps Title-->
    <title> <?= htmlspecialchars($user['fullname']) ?></title>
    <!--! END:  Apps Title-->
    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="<?= isset($user['avatar']) ? 'auth/uploads/' . $user['avatar'] : 'https://picsum.photos/120/120' ?>" />
    <!--! END: Favicon-->
    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
    <!--! END: Bootstrap CSS-->
    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/daterangepicker.min.css" />

    <link rel="stylesheet" type="text/css" href="assets/vendors/css/jquery-jvectormap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/select2-theme.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/jquery.time-to.min.css">

    <link rel="stylesheet" type="text/css" href="assets/vendors/css/tagify.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/tagify-data.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/quill.min.css">

    <link type="text/css" rel="stylesheet" href="assets/vendors/css/tui-calendar.min.css">
    <link type="text/css" rel="stylesheet" href="assets/vendors/css/tui-theme.min.css">
    <link type="text/css" rel="stylesheet" href="assets/vendors/css/tui-time-picker.min.css">
    <link type="text/css" rel="stylesheet" href="assets/vendors/css/tui-date-picker.min.css">

    <link type="text/css" rel="stylesheet" href="assets/vendors/css/emojionearea.min.css">

    <link rel="stylesheet" type="text/css" href="assets/vendors/css/jquery.time-to.min.css">

    <link rel="stylesheet" type="text/css" href="assets/vendors/css/dataTables.bs5.min.css">
    <!--! END: Vendors CSS-->
    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/theme.min.css" />

    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <!--! END: Custom CSS-->
    <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
    <!--! WARNING: Respond.js doesn"t work if you view the page via file: !-->
    <!--[if lt IE 9]>
			<script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
    <?php echo (isset($css) ? $css   : '') ?>

</head>