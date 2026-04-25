<?php /** Expects: $priceLists, $fileCount */
$pdfCount   = count(array_filter($priceLists, fn($p) => strtolower($p['file_type']) === 'pdf'));
$excelCount = count(array_filter($priceLists, fn($p) => in_array(strtolower($p['file_type']), ['xlsx', 'xls'], true)));
$otherCount = $fileCount - $pdfCount - $excelCount;
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:12px 10px;">
            <div style="font-size:20px;font-weight:800;color:var(--text);"><?= $fileCount ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Available Documents</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:12px 10px;">
            <div style="font-size:20px;font-weight:800;color:#b91c1c;"><?= $pdfCount ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">PDF Files</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:12px 10px;">
            <div style="font-size:20px;font-weight:800;color:#3d6b4f;"><?= $excelCount ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Excel Files</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:12px 10px;">
            <div style="font-size:20px;font-weight:800;color:var(--primary);"><?= $otherCount ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Other Files</div>
        </div>
    </div>
</div>
