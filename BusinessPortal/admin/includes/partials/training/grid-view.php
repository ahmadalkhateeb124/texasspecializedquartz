<?php /** Expects: $videos, $videoManager */ ?>
<div class="video-grid" id="videosGrid">
    <?php foreach ($videos as $video):
        $videoUrl   = $videoManager->getFileUrl($video['video_file']);
        $fileExists = $videoManager->fileExists($video['video_file']);
        $mime       = videoMimeType($video['video_file']);
        $ext        = strtolower(pathinfo($video['video_file'], PATHINFO_EXTENSION));
    ?>
        <div class="video-card video-grid-item">
            <div class="video-card-media">
                <?php if ($fileExists): ?>
                    <video controls playsinline preload="metadata">
                        <source src="<?= htmlspecialchars($videoUrl) ?>" type="<?= $mime ?>">
                        <?php if ($ext === 'mov'): ?>
                            <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/quicktime">
                            <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
                        <?php endif; ?>
                    </video>
                <?php else: ?>
                    <div class="video-card-fallback">
                        <i class='bx bx-error-circle'></i>
                        <span>File not found</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="video-card-body">
                <h3 class="video-card-title"><?= htmlspecialchars($video['video_name']) ?></h3>
                <div class="video-card-meta">
                    <span><i class='bx bx-calendar'></i><?= date('M j, Y', strtotime($video['created_at'])) ?></span>
                    <?php if ($video['duration']): ?>
                        <span><i class='bx bx-time'></i><?= htmlspecialchars($video['duration']) ?></span>
                    <?php endif; ?>
                    <span><i class='bx bx-data'></i><?= VideoManager::formatFileSize($video['file_size']) ?></span>
                </div>
            </div>
            <div class="video-card-actions">
                <button class="btn btn-outline btn-sm edit-video-btn"
                    data-bs-toggle="modal" data-bs-target="#editVideoModal"
                    data-id="<?= $video['id'] ?>"
                    data-name="<?= htmlspecialchars($video['video_name'], ENT_QUOTES) ?>">
                    <i class='bx bx-edit'></i> Edit
                </button>
                <button class="btn btn-sm delete-video-btn"
                    style="background:#dc2626;border:1px solid #dc2626;color:#fff;"
                    data-id="<?= $video['id'] ?>"
                    data-name="<?= htmlspecialchars($video['video_name'], ENT_QUOTES) ?>">
                    <i class='bx bx-trash' style="color:#fff;"></i> Delete
                </button>
            </div>
        </div>
    <?php endforeach; ?>
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
    .video-card-fallback {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--text-dis);
        font-size: 13px;
        gap: 8px;
    }
    .video-card-fallback i { font-size: 32px; }
    .video-card-body { padding: 14px 16px; flex: 1; }
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
    .video-card-meta i {
        font-size: 14px;
        margin-right: 4px;
        vertical-align: middle;
    }
    .video-card-actions {
        display: flex;
        gap: 8px;
        padding: 0 16px 16px;
    }
    .video-card-actions .btn { flex: 1; justify-content: center; }
</style>
