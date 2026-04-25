<?php
/**
 * Renders a single pre-filled job section card for the Edit page.
 * Expects: $job (a row from job_sections).
 * Uses field names matching auth/UpdateFabricationOrders.php (thickness[], thickness_custom[]).
 */
$jobLabels = [
    'kitchen'        => 'Kitchen',
    'bathroom'       => 'Bathroom',
    'MasterBathroom' => 'Master Bath',
    'other'          => 'Other',
];
$jobType       = $job['job_type'];
$jobLabel      = $jobLabels[$jobType] ?? ucfirst($jobType);
$materialType  = $job['material_type']  ?? '';
$thickness     = $job['thickness']      ?? '';
$edgeProfile   = $job['edge_profile']   ?? '';
$sinkProvider  = $job['sink_provider']  ?? '';
$sinkStyle     = $job['sink_style']     ?? '';
$sinkType      = $job['sink_type']      ?? '';

$sel  = fn(string $actual, string $want): string => $actual === $want ? 'selected' : '';
$show = fn(bool $on): string => $on ? 'block' : 'none';
?>
<div class="card mb-3 job-section" data-job-type="<?= htmlspecialchars($jobType) ?>">
    <div class="card-header">
        <h6 class="card-title">
            <i class='bx bx-layer text-primary'></i>
            <span class="job-type-label"><?= htmlspecialchars($jobLabel) ?></span> Details
        </h6>
    </div>
    <div class="card-section">

        <div class="job-type-other-field mb-4" style="display:<?= $show($jobType === 'other') ?>;">
            <label class="form-label">Specify Job Type <span class="text-danger">*</span></label>
            <input type="text" name="job_type_other[]" class="form-control job-type-other-input"
                placeholder="e.g. Laundry Room, Bar, Fireplace"
                value="<?= htmlspecialchars($job['job_type_other'] ?? '') ?>"
                <?= $jobType === 'other' ? 'required' : '' ?>>
        </div>

        <p class="form-section-label">Material Information</p>
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <label class="form-label">Material Type <span class="text-danger">*</span></label>
                <select name="material_type[]" class="form-select material-select" required>
                    <option value="">Select Material</option>
                    <option value="granite" <?= $sel($materialType, 'granite') ?>>Granite</option>
                    <option value="marble"  <?= $sel($materialType, 'marble') ?>>Marble</option>
                    <option value="quartz"  <?= $sel($materialType, 'quartz') ?>>Quartz</option>
                    <option value="other"   <?= $sel($materialType, 'other') ?>>Other (specify)</option>
                </select>
                <div class="other-material-input mt-2" style="display:<?= $show($materialType === 'other') ?>;">
                    <input type="text" name="material_other[]" class="form-control"
                        placeholder="Custom material type"
                        value="<?= htmlspecialchars($job['material_other'] ?? '') ?>"
                        <?= $materialType === 'other' ? 'required' : '' ?>>
                </div>
            </div>
            <div class="col-sm-4">
                <label class="form-label">Thickness <span class="text-danger">*</span></label>
                <select name="thickness[]" class="form-select thickness-select" required>
                    <option value="">Select Thickness</option>
                    <option value="2cm"    <?= $sel($thickness, '2cm') ?>>2 Cm</option>
                    <option value="3cm"    <?= $sel($thickness, '3cm') ?>>3 Cm</option>
                    <option value="custom" <?= $sel($thickness, 'custom') ?>>Custom (specify)</option>
                </select>
                <div class="custom-thickness-input mt-2" style="display:<?= $show($thickness === 'custom') ?>;">
                    <div class="input-group">
                        <input type="text" name="thickness_custom[]" class="form-control"
                            placeholder="Enter cm"
                            value="<?= htmlspecialchars($job['thickness_custom'] ?? '') ?>"
                            <?= $thickness === 'custom' ? 'required' : '' ?>>
                        <span class="input-group-text">Cm</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <label class="form-label">Manufacture / Color <span class="text-danger">*</span></label>
                <input type="text" name="material_color[]" class="form-control" required
                    placeholder="e.g. Bianco Carrara"
                    value="<?= htmlspecialchars($job['material_color'] ?? '') ?>">
            </div>
        </div>

        <p class="form-section-label">Sink Information</p>
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <label class="form-label">Sink Provider <span class="text-danger">*</span></label>
                <select name="sink_provider[]" class="form-select sink-provider-select" required>
                    <option value="">Select Provider</option>
                    <option value="customer"   <?= $sel($sinkProvider, 'customer') ?>>Customer Sink</option>
                    <option value="ts_granite" <?= $sel($sinkProvider, 'ts_granite') ?>>Ts Granite Sinke</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label class="form-label">Sink Type <span class="text-danger">*</span></label>
                <select name="sink_type[]" class="form-select sink-type-select" required>
                    <option value="">Select Sink Type</option>
                    <option value="undermount" <?= $sel($sinkType, 'undermount') ?>>Under Mount</option>
                    <option value="dropin"     <?= $sel($sinkType, 'dropin') ?>>Drop-in</option>
                    <option value="farmhouse"  <?= $sel($sinkType, 'farmhouse') ?>>Farmhouse / Apron</option>
                    <option value="vessel"     <?= $sel($sinkType, 'vessel') ?>>Vessel</option>
                </select>
            </div>
            <div class="col-sm-4 sink-style-field" style="display:<?= $show($sinkProvider === 'ts_granite') ?>;">
                <label class="form-label">Sink Style <span class="text-danger">*</span></label>
                <select name="sink_style[]" class="form-select sink-style-select"
                    <?= $sinkProvider === 'ts_granite' ? 'required' : '' ?>>
                    <option value="">Select Style</option>
                    <?php
                    $sinkStyles = [
                        'standard_single_bowl'   => 'Standard Single Bowl',
                        'standard_single_50_50'  => 'Standard Single 50/50',
                        'standard_single_60_40'  => 'Standard Single 60/40',
                        'zero_radius_single_bowl' => 'Zero Radius Single Bowl',
                        'zero_radius_single_50_50' => 'Zero Radius 50/50',
                        'zero_radius_single_60_40' => 'Zero Radius 60/40',
                        'bathroom_rectangle_white' => 'Bathroom Rectangle White',
                        'bathroom_rectangle_bisque' => 'Bathroom Rectangle Bisque',
                        'bathroom_oval_white'    => 'Bathroom Oval White',
                        'bathroom_oval_bisque'   => 'Bathroom Oval Bisque',
                        'other'                  => 'Other (specify)',
                    ];
                    foreach ($sinkStyles as $v => $label): ?>
                        <option value="<?= $v ?>" <?= $sel($sinkStyle, $v) ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="other-sink-style-input mt-2" style="display:<?= $show($sinkStyle === 'other') ?>;">
                    <input type="text" name="sink_style_other[]" class="form-control"
                        placeholder="Custom sink style"
                        value="<?= htmlspecialchars($job['sink_style_other'] ?? '') ?>"
                        <?= $sinkStyle === 'other' ? 'required' : '' ?>>
                </div>
            </div>
        </div>

        <p class="form-section-label">Additional Details</p>
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label">Edge Profile</label>
                <select name="edge_profile[]" class="form-select edge-select">
                    <option value="">Select Edge</option>
                    <option value="eased"    <?= $sel($edgeProfile, 'eased') ?>>Flat "Ease"</option>
                    <option value="bullnose" <?= $sel($edgeProfile, 'bullnose') ?>>Demi</option>
                    <option value="bevel"    <?= $sel($edgeProfile, 'bevel') ?>>3/8 Bevel</option>
                    <option value="radius"   <?= $sel($edgeProfile, 'radius') ?>>3/8 Radius</option>
                    <option value="custom"   <?= $sel($edgeProfile, 'custom') ?>>Custom (specify)</option>
                </select>
                <div class="custom-edge-input mt-2" style="display:<?= $show($edgeProfile === 'custom') ?>;">
                    <input type="text" name="edge_profile_custom[]" class="form-control"
                        placeholder="Describe edge profile"
                        value="<?= htmlspecialchars($job['edge_profile_custom'] ?? '') ?>"
                        <?= $edgeProfile === 'custom' ? 'required' : '' ?>>
                </div>
            </div>
            <div class="col-sm-6">
                <label class="form-label">Tear Out <span class="text-danger">*</span></label>
                <select name="tear_out[]" class="form-select" required>
                    <option value="">Select</option>
                    <option value="yes" <?= $sel($job['tear_out'] ?? '', 'yes') ?>>Yes</option>
                    <option value="no"  <?= $sel($job['tear_out'] ?? '', 'no') ?>>No</option>
                </select>
            </div>
        </div>

    </div>
</div>
