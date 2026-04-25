<?php
/**
 * Fabrication order notification email (HTML body).
 *
 * Expects:
 *   $order       array with: id, customer_name, phone, address, city, zip_code,
 *                            sales_rep, sales_rep_phone, po_number, notes,
 *                            user_email, user_fullname
 *   $jobs        array of job sections
 *   $attachment  ['name' => string, 'path' => string] | null
 */

$formatThickness = function (array $job): string {
    if ($job['thickness'] === 'custom' && !empty($job['thickness_custom'])) {
        return 'Custom: <span class="custom-value">' . htmlspecialchars($job['thickness_custom']) . ' cm</span>';
    }
    if ($job['thickness'] === '20') return '2 cm';
    if ($job['thickness'] === '30') return '3 cm';
    return !empty($job['thickness']) ? htmlspecialchars($job['thickness']) : '';
};

$formatFileSize = function (int $bytes): string {
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' bytes';
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabrication Order #<?= $order['id'] ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f5f7fa; padding: 20px; }
        .email-container { max-width: 800px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        .email-header { background: #9f8054; padding: 30px 40px; text-align: center; color: white; position: relative; }
        .logo-placeholder { font-size: 32px; font-weight: bold; color: white; margin-bottom: 20px; display: inline-block; padding: 10px 20px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; border: 2px solid rgba(255, 255, 255, 0.3); }
        .order-title { font-size: 28px; font-weight: 600; margin-bottom: 10px; color: white; }
        .order-id { font-size: 36px; font-weight: 700; color: #ffd54f; margin-bottom: 5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); }
        .order-date { font-size: 14px; opacity: 0.9; color: #e3f2fd; }
        .email-content { padding: 40px; }
        .section { margin-bottom: 30px; padding: 25px; background: #f8f9fa; border-radius: 10px; border-left: 5px solid #9f8054; }
        .section-title { font-size: 18px; font-weight: 600; color: #9f8054; margin-bottom: 20px; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; }
        .info-item { display: flex; flex-direction: column; padding: 10px; background: white; border-radius: 6px; border: 1px solid #e0e0e0; margin-bottom: 15px; }
        .info-label { font-size: 12px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
        .info-value { font-size: 15px; font-weight: 500; color: #333; }
        .job-sections-container { display: grid; gap: 20px; }
        .job-section { background: white; border-radius: 8px; padding: 20px; border: 1px solid #e0e0e0; }
        .job-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #e8eaf6; }
        .job-section-title { font-size: 16px; font-weight: 600; color: #9f8054; }
        .job-section-number { background: #9f8054; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; }
        .job-details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
        .job-detail { padding: 8px 12px; background: #f5f7fa; border-radius: 6px; border-left: 3px solid #776852; margin-bottom: 15px; }
        .job-label { font-size: 12px; font-weight: 600; color: #666; margin-bottom: 3px; }
        .job-value { font-size: 14px; font-weight: 500; color: #333; }
        .custom-value { color: #d32f2f; font-weight: 700; }
        .attachment-section { background: #e8f5e9; border-left-color: #4caf50; }
        .attachment-info { display: flex; align-items: center; gap: 15px; padding: 15px; background: white; border-radius: 8px; border: 1px solid #c8e6c9; }
        .attachment-name { font-weight: 600; color: #2e7d32; margin-bottom: 5px; }
        .attachment-meta { font-size: 12px; color: #666; }
        .notes-content { padding: 15px; background: white; border-radius: 8px; border: 1px solid #e0e0e0; font-size: 14px; line-height: 1.8; color: #555; }
        .email-footer { background: #9f8054; color: white; padding: 25px 40px; text-align: center; }
        .footer-logo { font-size: 20px; font-weight: bold; margin-bottom: 15px; color: #ffd54f; }
        .footer-info { font-size: 13px; opacity: 0.9; margin-bottom: 10px; }
        .footer-copyright { font-size: 12px; opacity: 0.7; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; background: #e3f2fd; color: #837562; }
        @media (max-width: 600px) {
            .email-content { padding: 20px; }
            .section { padding: 15px; }
            .info-grid, .job-details-grid { grid-template-columns: 1fr; }
            .job-detail, .info-item { margin-bottom: 10px; }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo-placeholder">Texas Specialized Quartz</div>
            <h1 class="order-title">FABRICATION ORDER</h1>
            <div class="order-id">#<?= $order['id'] ?></div>
            <div class="order-date"><?= date('F j, Y \a\t g:i A') ?></div>
            <div style="margin-top: 15px;"><span class="status-badge">NEW ORDER</span></div>
        </div>

        <div class="email-content">

            <div class="section">
                <div class="section-title">Order Details</div>
                <div class="info-grid">
                    <div class="info-item"><span class="info-label">Order ID</span><span class="info-value">#<?= $order['id'] ?></span></div>
                    <div class="info-item"><span class="info-label">Created By</span><span class="info-value"><?= htmlspecialchars($order['user_fullname']) ?></span></div>
                    <div class="info-item"><span class="info-label">User Email</span><span class="info-value"><?= htmlspecialchars($order['user_email']) ?></span></div>
                    <div class="info-item"><span class="info-label">Order Date</span><span class="info-value"><?= date('M d, Y') ?></span></div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Customer Information</div>
                <div class="info-grid">
                    <div class="info-item"><span class="info-label">Customer Name</span><span class="info-value"><?= htmlspecialchars($order['customer_name']) ?></span></div>
                    <div class="info-item"><span class="info-label">Phone Number</span><span class="info-value"><?= htmlspecialchars($order['phone']) ?></span></div>
                    <div class="info-item"><span class="info-label">Address</span><span class="info-value"><?= htmlspecialchars($order['address']) ?></span></div>
                    <div class="info-item"><span class="info-label">City</span><span class="info-value"><?= htmlspecialchars($order['city']) ?></span></div>
                    <div class="info-item"><span class="info-label">Zip Code</span><span class="info-value"><?= htmlspecialchars($order['zip_code']) ?></span></div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Sales Information</div>
                <div class="info-grid">
                    <div class="info-item"><span class="info-label">Sales Representative</span><span class="info-value"><?= htmlspecialchars($order['sales_rep'] ?: 'Not specified') ?></span></div>
                    <div class="info-item"><span class="info-label">Sales Rep Phone</span><span class="info-value"><?= htmlspecialchars($order['sales_rep_phone'] ?: 'Not specified') ?></span></div>
                    <div class="info-item"><span class="info-label">PO Number</span><span class="info-value"><?= htmlspecialchars($order['po_number'] ?: 'Not specified') ?></span></div>
                </div>
            </div>

            <?php if (!empty($jobs)): ?>
                <div class="section">
                    <div class="section-title">Job Sections (<?= count($jobs) ?>)</div>
                    <div class="job-sections-container">
                        <?php foreach ($jobs as $index => $job):
                            $jobTypeDisplay = ($job['job_type'] === 'other' && !empty($job['job_type_other']))
                                ? htmlspecialchars($job['job_type_other'])
                                : ucfirst($job['job_type']);
                        ?>
                            <div class="job-section">
                                <div class="job-section-header">
                                    <div class="job-section-title"><?= $jobTypeDisplay ?></div>
                                    <div class="job-section-number"><?= $index + 1 ?></div>
                                </div>
                                <div class="job-details-grid">
                                    <div class="job-detail">
                                        <div class="job-label">Material Type</div>
                                        <div class="job-value">
                                            <?= ucfirst(htmlspecialchars($job['material_type'])) ?>
                                            <?php if (!empty($job['material_other'])): ?>
                                                <span class="custom-value">(<?= htmlspecialchars($job['material_other']) ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($job['material_color'])): ?>
                                        <div class="job-detail">
                                            <div class="job-label">Material Color</div>
                                            <div class="job-value"><?= htmlspecialchars($job['material_color']) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php $thicknessDisplay = $formatThickness($job); ?>
                                    <?php if ($thicknessDisplay): ?>
                                        <div class="job-detail">
                                            <div class="job-label">Thickness</div>
                                            <div class="job-value"><?= $thicknessDisplay ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($job['sink_provider'])): ?>
                                        <div class="job-detail">
                                            <div class="job-label">Sink Provider</div>
                                            <div class="job-value"><?= ucfirst(htmlspecialchars($job['sink_provider'])) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($job['sink_type'])): ?>
                                        <div class="job-detail">
                                            <div class="job-label">Sink Type</div>
                                            <div class="job-value"><?= ucfirst(htmlspecialchars($job['sink_type'])) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($job['sink_provider'] === 'ts_granite' && !empty($job['sink_style'])): ?>
                                        <div class="job-detail">
                                            <div class="job-label">Sink Style</div>
                                            <div class="job-value">
                                                <?= ucfirst(str_replace('_', ' ', htmlspecialchars($job['sink_style']))) ?>
                                                <?php if (!empty($job['sink_style_other'])): ?>
                                                    <span class="custom-value">(<?= htmlspecialchars($job['sink_style_other']) ?>)</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($job['edge_profile'])): ?>
                                        <div class="job-detail">
                                            <div class="job-label">Edge Profile</div>
                                            <div class="job-value">
                                                <?= ucfirst(htmlspecialchars($job['edge_profile'])) ?>
                                                <?php if (!empty($job['edge_profile_custom'])): ?>
                                                    <span class="custom-value">(<?= htmlspecialchars($job['edge_profile_custom']) ?>)</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="job-detail">
                                        <div class="job-label">Tear Out Required</div>
                                        <div class="job-value <?= $job['tear_out'] === 'yes' ? 'custom-value' : '' ?>">
                                            <strong><?= ucfirst($job['tear_out']) ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="section">
                    <div class="section-title">Job Sections</div>
                    <div style="text-align: center; padding: 30px; color: #666;">
                        No job sections added to this order
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($attachment && file_exists($attachment['path'])):
                $ext  = strtolower(pathinfo($attachment['name'], PATHINFO_EXTENSION));
                $size = $formatFileSize(filesize($attachment['path']));
            ?>
                <div class="section attachment-section">
                    <div class="section-title">Attached File</div>
                    <div class="attachment-info">
                        <div>
                            <div class="attachment-name"><?= htmlspecialchars($attachment['name']) ?></div>
                            <div class="attachment-meta"><?= strtoupper($ext) ?> file - <?= $size ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="section">
                <div class="section-title">Additional Notes</div>
                <div class="notes-content">
                    <?= nl2br(htmlspecialchars($order['notes'] ?: 'No additional notes provided.')) ?>
                </div>
            </div>

        </div>

        <div class="email-footer">
            <div class="footer-logo">Texas Specialized Quartz</div>
            <div class="footer-info">Professional Fabrication Services</div>
            <div class="footer-info">This email was automatically generated by the Fabrication Order System</div>
            <div class="footer-copyright">&copy; <?= date('Y') ?> Texas Specialized Quartz. All rights reserved.</div>
        </div>
    </div>
</body>

</html>
