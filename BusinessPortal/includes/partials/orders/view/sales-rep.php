<?php /** Expects $order */ ?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-briefcase text-primary'></i> Sales Representative</h6>
    </div>
    <div class="card-section">
        <dl style="margin:0;display:grid;gap:10px;">
            <div><dt style="font-size:12px;color:var(--color-text-sub);">Name</dt>
                 <dd style="margin:0;font-weight:500;"><?= orderFieldOrNA($order['sales_rep']) ?></dd></div>
            <div><dt style="font-size:12px;color:var(--color-text-sub);">Phone</dt>
                 <dd style="margin:0;"><?= orderFieldOrNA($order['sales_rep_phone']) ?></dd></div>
        </dl>
    </div>
</div>
