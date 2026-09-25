<?php
/**
 * Short "installation completed & signed" notification email.
 * Expects: $order (id, customer_name), $signoff (signature_text, signed_at)
 * The sign-off PDF is attached separately by the Mailer call, not embedded here.
 */

$h = fn($v) => htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');

$signedAt = !empty($signoff['signed_at']) ? date('F j, Y \a\t g:i A', strtotime($signoff['signed_at'])) : date('F j, Y');

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
    <title>Order #<?= $h($order['id']) ?> — Installation Completed &amp; Signed</title>
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
                                Order #<?= $h($order['id']) ?> — installation completed
                            </p>
                            <p style="margin:0 0 20px;color:<?= $muted ?>;font-size:14px;line-height:1.6;">
                                The customer, <strong><?= $h($order['customer_name'] ?? '') ?></strong>, has signed off confirming
                                that installation was completed. The completion sign-off document is attached to this email as a PDF.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid <?= $border ?>;border-radius:8px;">
                                <tr>
                                    <td style="padding:14px 20px;border-bottom:1px solid <?= $border ?>;">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Signed By</span><br>
                                        <span style="font-size:14px;color:<?= $ink ?>;font-style:italic;"><?= $h($signoff['signature_text'] ?? '') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px;">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Signed On</span><br>
                                        <span style="font-size:14px;color:<?= $ink ?>;"><?= $h($signedAt) ?></span>
                                    </td>
                                </tr>
                            </table>
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
