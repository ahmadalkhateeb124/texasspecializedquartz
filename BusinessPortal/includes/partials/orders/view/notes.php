<?php /** Expects $order */ ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-note text-primary'></i> Notes &amp; Instructions</h6>
    </div>
    <div class="card-section">
        <p style="white-space:pre-wrap;margin:0;font-size:13px;color:var(--color-text);">
            <?= !empty($order['notes'])
                    ? htmlspecialchars($order['notes'])
                    : '<span style="color:var(--color-text-sub)">No special instructions provided.</span>' ?>
        </p>
    </div>
</div>
