<?php
/**
 * Job-area selection cards. Prefills from $existingJobTypes if set.
 */
$selected = $existingJobTypes ?? [];
$jobAreas = [
    ['key' => 'kitchen',        'cb' => 'jobKitchen',        'icon' => 'bx-restaurant', 'label' => 'Kitchen',     'desc' => 'Countertops, islands'],
    ['key' => 'bathroom',       'cb' => 'jobBathroom',       'icon' => 'bx-bath',       'label' => 'Bathroom',    'desc' => 'Vanities, shower walls'],
    ['key' => 'MasterBathroom', 'cb' => 'jobMasterBathroom', 'icon' => 'bxs-bath',      'label' => 'Master Bath', 'desc' => 'Tub decks, large vanities'],
    ['key' => 'other',          'cb' => 'jobOther',          'icon' => 'bx-plus-circle','label' => 'Other',       'desc' => 'Custom, commercial'],
];
?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title">
            <i class='bx bx-layer text-primary'></i> Select Job Areas
        </h6>
        <span style="font-size:12px;color:var(--color-text-sub);">Choose one or more</span>
    </div>
    <div class="card-section">
        <div class="row g-3">
            <?php foreach ($jobAreas as $a):
                $active = isset($selected[$a['key']]);
            ?>
                <div class="col-6 col-md-3">
                    <div class="job-type-card <?= $active ? 'active' : '' ?>"
                         id="card-<?= $a['key'] ?>" onclick="toggleJobCard('<?= $a['key'] ?>')">
                        <input type="checkbox" id="<?= $a['cb'] ?>" name="job_types[]"
                            value="<?= $a['key'] ?>" class="visually-hidden"
                            onchange="toggleJobSections()" <?= $active ? 'checked' : '' ?>>
                        <div class="job-card-icon"><i class='bx <?= $a['icon'] ?>'></i></div>
                        <div class="job-card-label"><?= $a['label'] ?></div>
                        <div class="job-card-desc"><?= $a['desc'] ?></div>
                        <div class="job-card-check"><i class='bx bx-check'></i></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="jobSelectionAlert" class="mt-3 d-none"
            style="padding:10px 14px;background:var(--color-primary-l,#e6f7f5);
                   border-radius:var(--radius-sm);border-left:3px solid var(--color-primary);
                   font-size:13px;color:var(--color-primary);">
            <i class='bx bx-info-circle me-1'></i>
            <span id="jobAlertText"></span>
        </div>
    </div>
</div>
