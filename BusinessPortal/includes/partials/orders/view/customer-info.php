<?php /** Expects $order */ ?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-user text-primary'></i> Customer Information</h6>
    </div>
    <div class="card-section">
        <dl style="margin:0;display:grid;gap:10px;">
            <div><dt style="font-size:12px;color:var(--color-text-sub);">Name</dt>
                 <dd style="margin:0;font-weight:500;"><?= orderFieldOrNA($order['customer_name']) ?></dd></div>
            <div><dt style="font-size:12px;color:var(--color-text-sub);">Phone</dt>
                 <dd style="margin:0;"><?= orderFieldOrNA($order['phone'] ?? null) ?></dd></div>
            <div><dt style="font-size:12px;color:var(--color-text-sub);">Address</dt>
                 <dd style="margin:0;"><?= orderFieldOrNA($order['address']) ?></dd></div>
            <div><dt style="font-size:12px;color:var(--color-text-sub);">City</dt>
                 <dd style="margin:0;"><?= orderFieldOrNA($order['city']) ?></dd></div>
            <div><dt style="font-size:12px;color:var(--color-text-sub);">ZIP Code</dt>
                 <dd style="margin:0;"><?= orderFieldOrNA($order['zip_code']) ?></dd></div>
            <div><dt style="font-size:12px;color:var(--color-text-sub);">PO Number</dt>
                 <dd style="margin:0;"><?= orderFieldOrNA($order['po_number'] ?? null) ?></dd></div>
        </dl>
    </div>
</div>
