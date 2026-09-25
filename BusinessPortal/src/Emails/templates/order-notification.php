<?php
/**
 * Fabrication order notification email — bulletproof, table-based HTML.
 *
 * Renders identically in Gmail, Outlook 2007+, Apple Mail, iOS Mail,
 * Yahoo, ProtonMail and most other clients.
 *
 * Expects:
 *   $order       array with: id, customer_name, phone, address, city, zip_code,
 *                            sales_rep, sales_rep_phone, po_number, notes,
 *                            user_email, user_fullname
 *   $jobs        array of job sections
 *   $attachment  ['name' => string, 'path' => string] | null
 */

$h = fn($v) => htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');

$formatThickness = function (array $job): string {
    if (($job['thickness'] ?? '') === 'custom' && !empty($job['thickness_custom'])) {
        return htmlspecialchars($job['thickness_custom']) . ' cm (custom)';
    }
    if (($job['thickness'] ?? '') === '2cm' || ($job['thickness'] ?? '') === '20') return '2 cm';
    if (($job['thickness'] ?? '') === '3cm' || ($job['thickness'] ?? '') === '30') return '3 cm';
    return htmlspecialchars((string)($job['thickness'] ?? '—'));
};

$formatFileSize = function (int $bytes): string {
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' bytes';
};

$jobLabel = function (array $job): string {
    $type = $job['job_type'] ?? '';
    $other = $job['job_type_other'] ?? '';
    $labels = [
        'kitchen' => 'Kitchen', 'bathroom' => 'Bathroom',
        'MasterBathroom' => 'Master Bathroom', 'other' => 'Other',
    ];
    $base = $labels[$type] ?? ucfirst($type);
    return $other ? "$base — " . htmlspecialchars($other) : $base;
};

$attachmentSize = null;
if ($attachment && !empty($attachment['path']) && file_exists($attachment['path'])) {
    $attachmentSize = filesize($attachment['path']);
}

$brand     = '#9f8054';
$brandDark = '#5a4530';
$ink       = '#1a1814';
$muted     = '#6a635a';
$bg        = '#f5f2ec';
$cardBg    = '#ffffff';
$border    = '#e8e3da';

$totalJobs   = count($jobs);
$createdDate = date('F j, Y \a\t g:i A');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta name="format-detection" content="telephone=no, address=no, email=no, date=no, url=no" />
    <title>Order #<?= $h($order['id']) ?> — Texas Specialized Quartz</title>
    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            <o:AllowPNG />
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <!--[if mso]>
    <style type="text/css">
        table, td, div, h1, h2, h3, p { font-family: Arial, sans-serif !important; }
    </style>
    <![endif]-->
    <style type="text/css">
        body, table, td, div, p, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; }
        img { -ms-interpolation-mode:bicubic; border:0; outline:none; text-decoration:none; display:block; }
        body { margin:0; padding:0; width:100% !important; }
        @media screen and (max-width: 620px) {
            .email-shell      { width: 100% !important; }
            .stack-on-mobile  { display: block !important; width: 100% !important; }
            .px-mobile        { padding-left: 18px !important; padding-right: 18px !important; }
            .py-mobile        { padding-top: 22px !important; padding-bottom: 22px !important; }
            .h1-mobile        { font-size: 26px !important; line-height: 32px !important; }
            .order-id-mobile  { font-size: 32px !important; }
        }
        @media (prefers-color-scheme: dark) {
            .dark-bg    { background-color: #181613 !important; }
            .dark-card  { background-color: #221f1a !important; border-color: #322d24 !important; }
            .dark-text  { color: #f0ece2 !important; }
            .dark-muted { color: #b0a99c !important; }
        }
    </style>
</head>
<body class="dark-bg" style="margin:0;padding:0;background-color:<?= $bg ?>;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;color:<?= $ink ?>;">

<div style="display:none;max-height:0;overflow:hidden;mso-hide:all;font-size:1px;line-height:1px;color:<?= $bg ?>;">
    New fabrication order #<?= $h($order['id']) ?> from <?= $h($order['customer_name']) ?> — <?= $totalJobs ?> job<?= $totalJobs !== 1 ? 's' : '' ?>.
</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="dark-bg" style="background-color:<?= $bg ?>;">
    <tr>
        <td align="center" style="padding:24px 12px;">

            <table role="presentation" class="email-shell" width="600" cellpadding="0" cellspacing="0" border="0"
                   style="width:600px;max-width:600px;background-color:<?= $cardBg ?>;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(26,24,20,0.08);">

                <tr>
                    <td class="px-mobile py-mobile" style="background-color:<?= $brand ?>;padding:36px 40px;text-align:left;">
                        <p style="margin:0 0 6px 0;font-family:Arial,sans-serif;font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#ffe7c4;font-weight:700;">
                            Texas Specialized Quartz &amp; Granite
                        </p>
                        <h1 class="h1-mobile" style="margin:0 0 14px 0;font-family:Georgia, 'Times New Roman', serif;font-size:28px;line-height:34px;font-weight:700;color:#ffffff;">
                            New Fabrication Order
                        </h1>
                        <p class="order-id-mobile" style="margin:0;font-family:Georgia,'Times New Roman',serif;font-size:42px;line-height:1;font-weight:700;color:#ffd54f;letter-spacing:-1px;">
                            #<?= $h($order['id']) ?>
                        </p>
                        <p style="margin:10px 0 0 0;font-family:Arial,sans-serif;font-size:13px;color:#ffe7c4;">
                            Submitted <?= $createdDate ?>
                        </p>
                    </td>
                </tr>

                <tr>
                    <td class="px-mobile dark-card" style="background-color:#fbf8f1;padding:18px 40px;border-bottom:1px solid <?= $border ?>;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="stack-on-mobile" width="33%" valign="top" style="padding:6px 8px;">
                                    <p style="margin:0;font-family:Arial,sans-serif;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;">Customer</p>
                                    <p style="margin:4px 0 0 0;font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;font-weight:600;">
                                        <?= $h($order['customer_name']) ?>
                                    </p>
                                </td>
                                <td class="stack-on-mobile" width="33%" valign="top" style="padding:6px 8px;">
                                    <p style="margin:0;font-family:Arial,sans-serif;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;">Job Sections</p>
                                    <p style="margin:4px 0 0 0;font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;font-weight:600;">
                                        <?= $totalJobs ?> section<?= $totalJobs !== 1 ? 's' : '' ?>
                                    </p>
                                </td>
                                <td class="stack-on-mobile" width="33%" valign="top" style="padding:6px 8px;">
                                    <p style="margin:0;font-family:Arial,sans-serif;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;">PO Number</p>
                                    <p style="margin:4px 0 0 0;font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;font-weight:600;">
                                        <?= $order['po_number'] ? $h($order['po_number']) : '—' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="px-mobile py-mobile dark-card" style="padding:34px 40px;background-color:<?= $cardBg ?>;">

                        <h2 class="dark-text" style="margin:0 0 14px 0;font-family:Georgia,'Times New Roman',serif;font-size:18px;color:<?= $ink ?>;border-bottom:2px solid <?= $brand ?>;padding-bottom:8px;">
                            Customer Information
                        </h2>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:30px;">
                            <tr>
                                <td class="stack-on-mobile" width="50%" valign="top" style="padding:8px 12px 8px 0;">
                                    <p style="margin:0 0 14px 0;">
                                        <span class="dark-muted" style="display:block;font-family:Arial,sans-serif;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;margin-bottom:3px;">Phone</span>
                                        <span class="dark-text" style="font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;"><?= $order['phone'] ? $h($order['phone']) : '—' ?></span>
                                    </p>
                                    <p style="margin:0 0 14px 0;">
                                        <span class="dark-muted" style="display:block;font-family:Arial,sans-serif;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;margin-bottom:3px;">Address</span>
                                        <span class="dark-text" style="font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;"><?= $order['address'] ? $h($order['address']) : '—' ?></span>
                                    </p>
                                </td>
                                <td class="stack-on-mobile" width="50%" valign="top" style="padding:8px 0 8px 12px;">
                                    <p style="margin:0 0 14px 0;">
                                        <span class="dark-muted" style="display:block;font-family:Arial,sans-serif;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;margin-bottom:3px;">City</span>
                                        <span class="dark-text" style="font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;"><?= $order['city'] ? $h($order['city']) : '—' ?></span>
                                    </p>
                                    <p style="margin:0 0 14px 0;">
                                        <span class="dark-muted" style="display:block;font-family:Arial,sans-serif;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;margin-bottom:3px;">ZIP Code</span>
                                        <span class="dark-text" style="font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;"><?= $order['zip_code'] ? $h($order['zip_code']) : '—' ?></span>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <?php if (!empty($order['sales_rep']) || !empty($order['sales_rep_phone'])): ?>
                        <h2 class="dark-text" style="margin:0 0 14px 0;font-family:Georgia,'Times New Roman',serif;font-size:18px;color:<?= $ink ?>;border-bottom:2px solid <?= $brand ?>;padding-bottom:8px;">
                            Sales Representative
                        </h2>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:30px;">
                            <tr>
                                <td class="stack-on-mobile" width="50%" valign="top" style="padding:8px 12px 8px 0;">
                                    <p style="margin:0;">
                                        <span class="dark-muted" style="display:block;font-family:Arial,sans-serif;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;margin-bottom:3px;">Name</span>
                                        <span class="dark-text" style="font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;"><?= $h($order['sales_rep'] ?? '—') ?></span>
                                    </p>
                                </td>
                                <td class="stack-on-mobile" width="50%" valign="top" style="padding:8px 0 8px 12px;">
                                    <p style="margin:0;">
                                        <span class="dark-muted" style="display:block;font-family:Arial,sans-serif;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;margin-bottom:3px;">Phone</span>
                                        <span class="dark-text" style="font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;"><?= $h($order['sales_rep_phone'] ?? '—') ?></span>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <?php endif; ?>

                        <h2 class="dark-text" style="margin:0 0 18px 0;font-family:Georgia,'Times New Roman',serif;font-size:18px;color:<?= $ink ?>;border-bottom:2px solid <?= $brand ?>;padding-bottom:8px;">
                            Job Sections (<?= $totalJobs ?>)
                        </h2>

                        <?php if (empty($jobs)): ?>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:30px;background-color:#fff7eb;border-left:4px solid #d98e2a;border-radius:6px;">
                                <tr>
                                    <td style="padding:16px 20px;font-family:Arial,sans-serif;font-size:13px;color:#7a4a05;">
                                        ⚠ This order was submitted without any job area selected.
                                        Edit it in the admin dashboard to add fabrication details.
                                    </td>
                                </tr>
                            </table>
                        <?php else: foreach ($jobs as $idx => $job): ?>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="margin-bottom:18px;background-color:#fbf8f1;border:1px solid <?= $border ?>;border-radius:8px;">
                                <tr>
                                    <td style="padding:14px 18px;background-color:<?= $brandDark ?>;border-radius:8px 8px 0 0;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="font-family:Arial,sans-serif;font-size:13px;font-weight:700;color:#ffffff;">
                                                    <span style="background-color:<?= $brand ?>;color:#ffffff;padding:2px 9px;border-radius:10px;font-size:11px;font-weight:700;margin-right:8px;">#<?= $idx + 1 ?></span>
                                                    <?= $jobLabel($job) ?>
                                                </td>
                                                <?php if (($job['tear_out'] ?? '') === 'yes' || ($job['tear_out'] ?? '') === 'Yes'): ?>
                                                <td align="right" style="font-family:Arial,sans-serif;">
                                                    <span style="background-color:#d98e2a;color:#ffffff;padding:2px 9px;border-radius:10px;font-size:10px;font-weight:700;letter-spacing:0.5px;">TEAR OUT</span>
                                                </td>
                                                <?php endif; ?>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:18px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <?php
                                            $material = trim(($job['material_type'] ?? '') . (($job['material_other'] ?? '') ? ' ' . $job['material_other'] : ''));
                                            $sink     = trim(implode(' / ', array_filter([
                                                $job['sink_provider'] ?? '',
                                                $job['sink_type']     ?? '',
                                                $job['sink_style']    ?? '',
                                                $job['sink_style_other'] ?? '',
                                            ])));
                                            $edge = trim(($job['edge_profile'] ?? '') . (($job['edge_profile_custom'] ?? '') ? ' (' . $job['edge_profile_custom'] . ')' : ''));
                                            $rows = [
                                                ['Material',     $material],
                                                ['Color',        $job['material_color'] ?? ''],
                                                ['Thickness',    $formatThickness($job)],
                                                ['Edge Profile', $edge],
                                                ['Sink',         $sink],
                                            ];
                                            foreach ($rows as $i => [$label, $value]):
                                                if (!$value) continue;
                                            ?>
                                            <tr>
                                                <td width="35%" style="padding:6px 8px 6px 0;font-family:Arial,sans-serif;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:<?= $muted ?>;font-weight:700;border-bottom:1px solid <?= $border ?>;vertical-align:top;">
                                                    <?= $label ?>
                                                </td>
                                                <td style="padding:6px 0;font-family:Arial,sans-serif;font-size:14px;color:<?= $ink ?>;border-bottom:1px solid <?= $border ?>;vertical-align:top;">
                                                    <?= $h($value) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        <?php endforeach; endif; ?>

                        <?php if (!empty($order['notes'])): ?>
                        <h2 class="dark-text" style="margin:18px 0 14px 0;font-family:Georgia,'Times New Roman',serif;font-size:18px;color:<?= $ink ?>;border-bottom:2px solid <?= $brand ?>;padding-bottom:8px;">
                            Notes &amp; Instructions
                        </h2>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;background-color:#fbf8f1;border-left:4px solid <?= $brand ?>;border-radius:6px;">
                            <tr>
                                <td style="padding:16px 20px;font-family:Arial,sans-serif;font-size:14px;line-height:1.6;color:<?= $ink ?>;">
                                    <?= nl2br($h($order['notes'])) ?>
                                </td>
                            </tr>
                        </table>
                        <?php endif; ?>

                        <?php if ($attachment && !empty($attachment['name'])): ?>
                        <h2 class="dark-text" style="margin:18px 0 14px 0;font-family:Georgia,'Times New Roman',serif;font-size:18px;color:<?= $ink ?>;border-bottom:2px solid <?= $brand ?>;padding-bottom:8px;">
                            Attached File
                        </h2>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;background-color:#eef7ee;border:1px solid #c8e6c9;border-radius:8px;">
                            <tr>
                                <td valign="middle" style="padding:14px 18px;font-family:Arial,sans-serif;">
                                    <p style="margin:0;font-size:14px;font-weight:600;color:#1b5e20;">📎 <?= $h($attachment['name']) ?></p>
                                    <p style="margin:3px 0 0 0;font-size:12px;color:#558b2f;">
                                        <?= $attachmentSize ? $formatFileSize($attachmentSize) : 'attached to this email' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <?php endif; ?>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:14px;">
                            <tr>
                                <td align="center" style="padding:8px 0;">
                                    <!--[if mso]>
                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
                                                 href="https://texasspecializedquartz.com/BusinessPortal/admin/order-view?id=<?= (int)$order['id'] ?>"
                                                 style="height:48px;v-text-anchor:middle;width:280px;" arcsize="50%" stroke="f" fillcolor="<?= $brand ?>">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:14px;font-weight:700;">View order in dashboard</center>
                                    </v:roundrect>
                                    <![endif]-->
                                    <!--[if !mso]><!-- -->
                                    <a href="https://texasspecializedquartz.com/BusinessPortal/admin/order-view?id=<?= (int)$order['id'] ?>"
                                       style="display:inline-block;background-color:<?= $brand ?>;color:#ffffff;padding:14px 32px;font-family:Arial,sans-serif;font-size:14px;font-weight:700;letter-spacing:1px;text-transform:uppercase;text-decoration:none;border-radius:24px;">
                                        View order in dashboard
                                    </a>
                                    <!--<![endif]-->
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <tr>
                    <td class="px-mobile" style="background-color:<?= $ink ?>;padding:28px 40px;text-align:center;">
                        <p style="margin:0 0 6px 0;font-family:Georgia,'Times New Roman',serif;font-size:16px;font-weight:700;color:#ffffff;">
                            Texas Specialized Quartz &amp; Granite
                        </p>
                        <p style="margin:0 0 14px 0;font-family:Arial,sans-serif;font-size:12px;color:#9a9285;">
                            Premium granite, quartz &amp; marble fabrication
                        </p>
                        <p style="margin:0 0 4px 0;font-family:Arial,sans-serif;font-size:12px;">
                            <a href="tel:+14698140555" style="color:<?= $brand ?>;text-decoration:none;font-weight:700;">(469) 814-0555</a>
                            <span style="color:#5a524a;">&nbsp;·&nbsp;</span>
                            <a href="mailto:Cs@TexasSpecializedQuartz.com" style="color:<?= $brand ?>;text-decoration:none;">Cs@TexasSpecializedQuartz.com</a>
                        </p>
                        <p style="margin:0;font-family:Arial,sans-serif;font-size:11px;color:#6a635a;">
                           2943 Ladybird Ln, Dallas, TX 75220, United States
                        </p>
                        <p style="margin:18px 0 0 0;font-family:Arial,sans-serif;font-size:10px;color:#5a524a;">
                            © <?= date('Y') ?> Texas Specialized Quartz &amp; Granite. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
