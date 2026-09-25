<?php
/**
 * Installation Completion Sign Off — full HTML document rendered by Dompdf.
 * Matches the approved reference design: navy accent, cream page background,
 * white rounded cards, a tinted confirmation callout, and a proper
 * STATUS/INSPECTION ITEM checklist table.
 *
 * Expects: $business (name, address, city, state, zip, phone, email),
 *          $order (id), $signoff (customer_name, customer_address,
 *          worked_area, checklist, signature_text, signed_at)
 */

$h = fn($v) => htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');

$wa = $signoff['worked_area'] ?? [];
$checklist = $signoff['checklist'] ?? [];
$signedAt = !empty($signoff['signed_at']) ? date('F j, Y', strtotime($signoff['signed_at'])) : date('F j, Y');

$checkbox = function (bool $checked): string {
    $cls = $checked ? 'chk chk-on' : 'chk chk-off';
    return '<span class="' . $cls . '"></span>';
};
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            background-color: #ffffff;
        }

        body {
            margin: 0;
            padding: 13mm 16mm;
            background-color: #ffffff;
            font-family: 'Lato', sans-serif;
            color: #1a1a1a;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .header-table td {
            vertical-align: middle;
        }

        .biz-name {
            font-size: 15px;
            font-weight: bold;
            color: #000000;
        }

        .biz-sub {
            font-size: 8.5px;
            color: #555555;
            line-height: 1.8;
        }

        .rule {
            border-bottom: 1.5px solid #000000;
            margin: 10px 0 14px;
        }

        .doc-title {
            font-size: 19px;
            font-weight: bold;
            text-align: center;
            color: #000000;
        }

        .doc-sub {
            font-size: 8.5px;
            text-align: center;
            color: #555555;
            letter-spacing: 2px;
            margin-top: 4px;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 14px 20px;
            margin-top: 14px;
        }

        .card-heading {
            font-size: 12px;
            font-weight: bold;
            color: #000000;
            border-bottom: 1px solid #e3e3e3;
            padding-bottom: 7px;
            margin-bottom: 11px;
        }

        .field-table td {
            padding-bottom: 8px;
            font-size: 10px;
            vertical-align: top;
        }

        .field-table tr:last-child td {
            padding-bottom: 0;
        }

        .field-label {
            font-weight: bold;
            width: 34%;
            color: #1c1c1c;
        }

        .field-value {
            color: #1c1c1c;
        }

        .chk {
            display: inline-block;
            width: 13px;
            height: 13px;
            border-radius: 3px;
        }

        .chk-on {
            background-color: #000000;
        }

        .chk-off {
            border: 1px solid #999999;
        }

        .wa-label {
            padding-left: 8px;
            padding-right: 24px;
            font-size: 10.5px;
        }

        .checklist-table th {
            background-color: #f0f0f0;
            color: #000000;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-align: left;
            padding: 6px 10px;
        }

        .checklist-table th:first-child {
            border-radius: 6px 0 0 0;
            width: 13%;
        }

        .checklist-table th:last-child {
            border-radius: 0 6px 0 0;
        }

        .checklist-table td {
            padding: 5px 10px;
            font-size: 9.5px;
            color: #1c1c1c;
            border-bottom: 1px solid #ececec;
        }

        .checklist-table tr:last-child td {
            border-bottom: none;
        }

        .checklist-table td.chk-cell {
            text-align: center;
        }

        .callout {
            background-color: #f0f0f0;
            border-left: 4px solid #000000;
            border-radius: 0 6px 6px 0;
            padding: 11px 18px;
            margin-top: 14px;
            font-size: 9.5px;
            line-height: 1.6;
            color: #1a1a1a;
        }

        .sig-value {
            font-family: 'Lato', sans-serif;
            font-style: italic;
            font-size: 16px;
            color: #000000;
        }

        .footer {
            border-top: 1px solid #e3e3e3;
            margin-top: 16px;
            padding-top: 9px;
            text-align: center;
            font-size: 8px;
            color: #888888;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td style="width:14%;">
                <img src="file://<?= __DIR__ ?>/../../../assets/images/company-logo.png" alt="Logo" style="width:74px; height:74px;">
            </td>
            <td style="width:86%; text-align:right;">
                <div class="biz-name"><?= $h($business['name'] ?? '') ?></div>
                <div class="biz-sub">
                    <?= $h($business['address'] ?? '') ?> &middot;
                    <?= $h(trim(($business['city'] ?? '') . ', ' . ($business['state'] ?? '') . ' ' . ($business['zip'] ?? ''), ' ,')) ?><br>
                    Phone: <?= $h($business['phone'] ?? '') ?> &middot; Email: <?= $h($business['email'] ?? '') ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="rule"></div>

    <div class="doc-title">INSTALLATION COMPLETION SIGN-OFF</div>
    <div class="doc-sub">ORDER NO. <?= $h($order['id']) ?></div>

    <div class="card">
        <div class="card-heading">Customer &amp; Job Details</div>
        <table class="field-table">
            <tr>
                <td class="field-label">Customer Name:</td>
                <td class="field-value"><?= $h($signoff['customer_name']) ?></td>
            </tr>
            <tr>
                <td class="field-label">Customer Address:</td>
                <td class="field-value"><?= $h($signoff['customer_address']) ?></td>
            </tr>
            <tr>
                <td class="field-label">Worked Area:</td>
                <td class="field-value">
                    <table style="width:auto;">
                        <tr>
                            <td><?= $checkbox(!empty($wa['kitchen'])) ?></td>
                            <td class="wa-label">Kitchen</td>
                            <td><?= $checkbox(!empty($wa['vanity'])) ?></td>
                            <td class="wa-label">Vanity</td>
                            <td><?= $checkbox(!empty($wa['other'])) ?></td>
                            <td class="wa-label" style="padding-right:0;">
                                Other<?= !empty($wa['other_text']) ? ' — ' . $h($wa['other_text']) : '' ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="card">
        <div class="card-heading">Installation Completion Checklist</div>
        <table class="checklist-table">
            <tr>
                <th>Status</th>
                <th>Inspection Item</th>
            </tr>
            <?php foreach (SignoffRepository::CHECKLIST_ITEMS as $key => $defaultLabel):
                $item = $checklist[$key] ?? ['checked' => false, 'label' => $defaultLabel];
                ?>
                <tr>
                    <td class="chk-cell"><?= $checkbox(!empty($item['checked'])) ?></td>
                    <td><?= $h($item['label'] ?? $defaultLabel) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="callout">
        <strong>Confirmation:</strong> By signing below, the customer confirms that the installation described above was
        completed to their full satisfaction.
    </div>

    <div class="card">
        <table class="field-table">
            <tr>
                <td class="field-label">Customer Name:</td>
                <td class="field-value"><?= $h($signoff['customer_name']) ?></td>
            </tr>
            <tr>
                <td class="field-label">Signature:</td>
                <td class="field-value"><span class="sig-value"><?= $h($signoff['signature_text']) ?></span></td>
            </tr>
            <tr>
                <td class="field-label">Date:</td>
                <td class="field-value"><?= $h($signedAt) ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">Order No. <?= $h($order['id']) ?> &middot; Signed electronically via the Texas Specialized
        Quartz &amp; Granite portal</div>

</body>

</html>