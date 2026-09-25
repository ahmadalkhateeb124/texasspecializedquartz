<?php
/**
 * Employee order-assignment notification email.
 * Expects: $employee (fullname), $order (id, customer_name, address, city, zip_code, sales_rep, sales_rep_phone, po_number)
 */

$h = fn($v) => htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');

$brand     = '#000000';
$brandDark = '#000000';
$ink       = '#1a1814';
$muted     = '#6a635a';
$bg        = '#f5f2ec';
$cardBg    = '#ffffff';
$border    = '#e8e3da';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?= $h($order['id']) ?> Assigned To You</title>
</head>
<body style="margin:0;padding:0;background:<?= $bg ?>;font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:<?= $bg ?>;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:<?= $cardBg ?>;border:1px solid <?= $border ?>;border-radius:10px;overflow:hidden;">
                    <tr>
                        <td style="background:<?= $brandDark ?>;padding:22px 28px;">
                            <span style="color:#fff;font-size:18px;font-weight:bold;">Texas Specialized Quartz &amp; Granite</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px;color:<?= $ink ?>;font-size:16px;">
                                Hi <?= $h($employee['fullname']) ?>,
                            </p>
                            <p style="margin:0 0 20px;color:<?= $muted ?>;font-size:14px;line-height:1.6;">
                                You've been assigned to a new order. You're responsible for every job section in it —
                                log in to your employee dashboard to view the full details and schedule work.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid <?= $border ?>;border-radius:8px;margin-bottom:20px;">
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid <?= $border ?>;">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Order</span><br>
                                        <span style="font-size:15px;color:<?= $ink ?>;font-weight:bold;">#<?= $h($order['id']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid <?= $border ?>;">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Customer</span><br>
                                        <span style="font-size:14px;color:<?= $ink ?>;"><?= $h($order['customer_name'] ?? '—') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Address</span><br>
                                        <span style="font-size:14px;color:<?= $ink ?>;">
                                            <?= $h($order['address'] ?? '—') ?><?= !empty($order['city']) ? ', ' . $h($order['city']) : '' ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0;color:<?= $muted ?>;font-size:12px;">
                                This is an automated notification from your employer's order management system.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:<?= $bg ?>;padding:16px 28px;text-align:center;">
                            <span style="font-size:11px;color:<?= $muted ?>;">© <?= date('Y') ?> Texas Specialized Quartz &amp; Granite</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
