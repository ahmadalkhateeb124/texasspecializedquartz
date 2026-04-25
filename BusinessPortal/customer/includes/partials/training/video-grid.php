<?php
/**
 * Expects: $videos, $videoManager, $videoCount.
 */
$getMime = function (string $filename): string {
    $ext  = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $map  = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg',
             'mov' => 'video/quicktime', 'avi' => 'video/x-msvideo'];
    return $map[$ext] ?? 'video/mp4';
};
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-video'></i> Training Library</h6>
        <div class="pl-picker-search" style="width:240px;padding:0;border:0;">
            <i class='bx bx-search' style="left:10px;"></i>
            <input type="text" id="videoSearch" placeholder="Search videos…">
        </div>
    </div>

    <?php if ($videoCount > 0): ?>
        <div class="video-grid" id="videosGrid">
            <?php foreach ($videos as $video):
                $videoUrl = $videoManager->getFileUrl($video['video_file']);
                $mime     = $getMime($video['video_file']);
            ?>
                <div class="video-card video-item" id="vw-<?= $video['id'] ?>">
                    <div class="video-card-media">
                        <video controls playsinline preload="metadata">
                            <source src="<?= htmlspecialchars($videoUrl) ?>" type="<?= $mime ?>">
                            <source src="<?= htmlspecialchars($videoUrl) ?>">
                        </video>
                        <div class="video-fallback" style="display:none;">
                            <i class='bx bx-error-circle'></i>
                            <span>Format not supported</span>
                        </div>
                    </div>
                    <div class="video-card-body">
                        <h3 class="video-card-title">
                            <?= htmlspecialchars($video['video_name']) ?>
                        </h3>
                        <div class="video-card-meta">
                            <span><i class='bx bx-calendar'></i><?= date('M j, Y', strtotime($video['created_at'])) ?></span>
                            <?php if ($video['duration']): ?>
                                <span><i class='bx bx-time'></i><?= htmlspecialchars($video['duration']) ?></span>
                            <?php endif; ?>
                            <span><i class='bx bx-data'></i><?= VideoManager::formatFileSize($video['file_size']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state" style="padding:80px 20px;">
            <i class='bx bx-video-off empty-state-icon'></i>
            <p class="empty-state-title">No training videos available</p>
            <p class="empty-state-desc">
                There are no training videos available at this moment.<br>
                Please check back later.
            </p>
        </div>
    <?php endif; ?>
</div>

<style>
    .video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 18px;
        padding: 20px;
    }
    .video-card {
        display: flex;
        flex-direction: column;
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        overflow: hidden;
        transition: border-color .18s, box-shadow .18s, transform .18s;
    }
    .video-card:hover {
        border-color: var(--brand);
        box-shadow: 0 6px 20px rgba(24,20,15,.06);
        transform: translateY(-2px);
    }
    .video-card-media {
        position: relative;
        width: 100%;
        height: 160px;
        background: #0f0e0c;
    }
    .video-card-media video {
        width: 100%; height: 100%;
        object-fit: contain;
        background: #0f0e0c;
        display: block;
    }
    .video-fallback {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--text-dis);
        font-size: 13px;
        gap: 8px;
        background: #0f0e0c;
    }
    .video-fallback i { font-size: 32px; }
    .video-card-body { padding: 14px 16px 18px; flex: 1; }
    .video-card-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 10px;
        line-height: 1.35;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .video-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 11px;
        color: var(--text-sub);
    }
    .video-card-meta i { font-size: 14px; margin-right: 4px; vertical-align: middle; }
</style>
