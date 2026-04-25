<?php /** Expects: $priceLists, $fileCount, $priceListManager */ ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-file'></i> My Price Lists</h6>
        <div class="pl-picker-search" style="width:240px;padding:0;border:0;">
            <i class='bx bx-search' style="left:10px;"></i>
            <input type="text" id="fileSearch" placeholder="Search…">
        </div>
    </div>

    <?php if ($fileCount > 0): ?>
        <div class="cust-pl-grid" id="priceListTable">
            <?php foreach ($priceLists as $p):
                $st         = priceListFileTypeStyle($p['file_type']);
                $downloadUrl = htmlspecialchars($priceListManager->getFileUrl($p['id']));
                $previewUrl  = htmlspecialchars($priceListManager->getFileUrl($p['id'], true));
            ?>
                <div class="cust-pl-card">
                    <div class="cust-pl-thumb" style="background:<?= $st['bg'] ?>;color:<?= $st['color'] ?>;">
                        <i class='bx <?= $st['icon'] ?>'></i>
                    </div>
                    <div class="cust-pl-body">
                        <div class="cust-pl-name"><?= htmlspecialchars($p['file_name']) ?></div>
                        <div class="cust-pl-meta">
                            <span class="cust-pl-badge" style="background:<?= $st['bg'] ?>;color:<?= $st['color'] ?>;">
                                <?= strtoupper(htmlspecialchars($p['file_type'])) ?>
                            </span>
                            <span><i class='bx bx-data'></i> <?= PriceListManager::formatFileSize($p['file_size']) ?></span>
                            <span><i class='bx bx-calendar'></i> <?= date('M j, Y', strtotime($p['created_at'])) ?></span>
                        </div>
                    </div>
                    <div class="cust-pl-actions">
                        <a href="<?= $previewUrl ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm" title="Preview">
                            <i class='bx bx-show'></i> Preview
                        </a>
                        <a href="<?= $downloadUrl ?>" class="btn btn-primary btn-sm">
                            <i class='bx bx-download'></i> Download
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state" style="padding:80px 20px;">
            <i class='bx bx-file-blank empty-state-icon'></i>
            <p class="empty-state-title">No price lists assigned</p>
            <p class="empty-state-desc">
                You don't have any price lists assigned to you yet.<br>
                Please check back later.
            </p>
        </div>
    <?php endif; ?>
</div>

<style>
    .cust-pl-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
        padding: 20px;
    }
    .cust-pl-card {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 18px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        transition: border-color .18s, box-shadow .18s, transform .18s;
    }
    .cust-pl-card:hover {
        border-color: var(--brand);
        box-shadow: 0 6px 20px rgba(24,20,15,.06);
        transform: translateY(-2px);
    }
    .cust-pl-thumb {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    .cust-pl-body { flex: 1; min-width: 0; }
    .cust-pl-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        line-height: 1.35;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 8px;
    }
    .cust-pl-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 11px;
        color: var(--text-sub);
        align-items: center;
    }
    .cust-pl-meta i { font-size: 14px; vertical-align: middle; }
    .cust-pl-badge {
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .5px;
    }
    .cust-pl-actions {
        display: flex;
        gap: 8px;
        margin-top: auto;
    }
    .cust-pl-actions .btn { flex: 1; justify-content: center; }
</style>
