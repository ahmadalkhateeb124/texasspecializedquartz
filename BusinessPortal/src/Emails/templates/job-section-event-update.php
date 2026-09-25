<?php
/**
 * Job-section schedule update email, sent to the customer.
 * Expects: $order (id, customer_name), $job (job_type, job_type_other),
 *          $event (event_type, scheduled_date, status, notes)
 */

$h = fn($v) => htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');

$jobLabel = ($job['job_type'] ?? '') === 'other' && !empty($job['job_type_other'])
    ? $job['job_type_other']
    : ucfirst((string)($job['job_type'] ?? '—'));

$eventLabel  = JobScheduleRepository::EVENT_TYPES[$event['event_type']] ?? $event['event_type'];
$statusLabel = JobScheduleRepository::STATUSES[$event['status']] ?? ucfirst((string)$event['status']);
$eventColor  = JobScheduleRepository::eventColor($event['event_type']);
$scheduled   = !empty($event['scheduled_date']) ? date('l, F j, Y \a\t g:i A', strtotime($event['scheduled_date'])) : '—';

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
    <title>Order #<?= $h($order['id']) ?> Update</title>
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
                                Hi <?= $h($order['customer_name'] ?? 'there') ?>,
                            </p>
                            <p style="margin:0 0 20px;color:<?= $muted ?>;font-size:14px;line-height:1.6;">
                                There's a schedule update on the <strong><?= $h($jobLabel) ?></strong> section of your order.
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
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Job Section</span><br>
                                        <span style="font-size:14px;color:<?= $ink ?>;"><?= $h($jobLabel) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid <?= $border ?>;">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Event</span><br>
                                        <span style="display:inline-block;margin-top:4px;padding:3px 10px;border-radius:999px;background:<?= $eventColor ?>;color:#fff;font-size:12px;font-weight:bold;">
                                            <?= $h($eventLabel) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid <?= $border ?>;">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Date &amp; Time</span><br>
                                        <span style="font-size:14px;color:<?= $ink ?>;"><?= $h($scheduled) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;<?= empty($event['notes']) ? '' : "border-bottom:1px solid {$border};" ?>">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Status</span><br>
                                        <span style="font-size:14px;color:<?= $ink ?>;"><?= $h($statusLabel) ?></span>
                                    </td>
                                </tr>
                                <?php if (!empty($event['notes'])): ?>
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <span style="font-size:12px;color:<?= $muted ?>;text-transform:uppercase;letter-spacing:.04em;">Notes</span><br>
                                        <span style="font-size:14px;color:<?= $ink ?>;"><?= nl2br($h($event['notes'])) ?></span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>

                            <p style="margin:0;color:<?= $muted ?>;font-size:12px;">
                                Log in to your customer portal to view the full order details at any time.
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
