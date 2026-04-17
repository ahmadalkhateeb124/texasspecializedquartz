<?php

/**
 * customer/training.php — Video Training for Customers
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/videos-db.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$pageTitle   = 'Video Training';
$breadcrumb  = [['label' => 'Video Training']];

/* ── Fetch videos from database ──────────────────────────────────── */
try {
    $stmt = $pdo->query("SELECT * FROM videos ORDER BY created_at DESC");
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $videos = [];
    $dbError = htmlspecialchars($e->getMessage());
}

$videoCount = count($videos);

// Create VideoManager instance to get file URLs
$videoManager = new VideoManager($pdo);

/**
 * Get MIME type for video file based on extension
 */
function getVideoMimeType(string $filename): string
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mimeTypes = [
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'ogg' => 'video/ogg',
        'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo'
    ];
    return $mimeTypes[$extension] ?? 'video/mp4';
}

include __DIR__ . '/includes/head.php';
?>

<style>
    .video-grid-card {
        border: 1px solid var(--border);
        border-radius: var(--radius-lg, 12px);
        overflow: hidden;
        background: var(--surface);
        transition: var(--transition);
    }

  

    .video-grid-card video {
        width: 100%;
        height: 200px;
   
        display: block;
        background: #111;
    }

    .video-fallback {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 200px;
        background: #1a1a1a;
        color: #999;
        font-size: .85rem;
        text-align: center;
        padding: 1rem;
        gap: 4px;
    }

    .video-fallback i {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 4px;
    }

    .video-grid-body {
        padding: 1.25rem;
    }

    .video-grid-title {
        font-weight: 600;
        color: var(--text);
        font-size: 1rem;
        margin-bottom: .75rem;
        line-height: 1.4;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .video-grid-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        font-size: .8rem;
        color: var(--muted);
        padding-top: .75rem;
        border-top: 1px solid var(--border);
    }

    .video-grid-meta i {
        font-size: .9rem;
        vertical-align: middle;
    }
</style>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title"><i class='bx bx-video me-2'></i>Video Training</h1>
                    <p class="page-desc">Learn from our educational video library</p>
                </div>
            </div>

            <?php if (isset($dbError)): ?>
                <div class="alert alert-danger mb-3"><i class='bx bx-error me-2'></i><?= $dbError ?></div>
            <?php endif; ?>

            <!-- Summary strip -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:12px 10px;">
                        <div style="font-size:20px;font-weight:800;color:var(--text);"><?= $videoCount ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Available Videos</div>
                    </div>
                </div>
            </div>

            <!-- Videos Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-video text-primary'></i> Training Library</h6>
                    <input type="text" id="videoSearch" class="form-control form-control-sm"
                        placeholder="Search…" style="width:200px;">
                </div>

                <?php if ($videoCount > 0): ?>
                    <div class="card-body">
                        <div class="row g-3" id="videosGrid">
                            <?php foreach ($videos as $video):
                                $videoUrl = $videoManager->getFileUrl($video['video_file']);
                                $videoMime = getVideoMimeType($video['video_file']);
                            ?>
                                <div class="col-md-6 col-lg-4 video-item">
                                    <div class="video-grid-card h-100">
                                        <div class="video-wrapper" id="vw-<?= $video['id'] ?>">
                                            <video
                                                controls
                                                playsinline
                                                preload="metadata"
                                          
                                                <source src="<?= htmlspecialchars($videoUrl) ?>" type="<?= $videoMime ?>">
                                                <source src="<?= htmlspecialchars($videoUrl) ?>">
                                            </video>
                                            <div class="video-fallback" style="display:none;">
                                                <i class='bx bx-error-circle'></i>
                                                <span>Video format not supported by your browser</span>
                                            
                                            </div>
                                        </div>
                                        <div class="video-grid-body">
                                            <div class="video-grid-title"><?= htmlspecialchars($video['video_name']) ?></div>
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                            
                                            </div>
                                            <div class="video-grid-meta">
                                                <span><i class='bx bx-calendar me-1'></i><?= date('M j, Y', strtotime($video['created_at'])) ?></span>
                                                <?php if ($video['duration']): ?>
                                                    <span><i class='bx bx-time me-1'></i><?= htmlspecialchars($video['duration']) ?></span>
                                                <?php endif; ?>
                                                <span><i class='bx bx-data me-1'></i><?= VideoManager::formatFileSize($video['file_size']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-body">
                        <div class="empty-state" style="padding:80px 20px;">
                            <i class='bx bx-video-off empty-state-icon'></i>
                            <p class="empty-state-title">No training videos available</p>
                            <p class="empty-state-desc">There are no training videos available at this moment.<br>Please check back later.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </main>



    <?php include __DIR__ . '/includes/footer.php'; ?>


    <script>
        // Search
        (function() {
            const search = document.getElementById('videoSearch');
            if (!search) return;
            const items = () => document.querySelectorAll('.video-item');

            search.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                items().forEach(el => {
                    el.style.display = !q || el.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        })();

        // Handle video errors — show download fallback
        document.querySelectorAll('.video-wrapper video').forEach(video => {
            video.addEventListener('error', function() {
                showFallback(this);
            });

            video.querySelectorAll('source').forEach((source, i, sources) => {
                source.addEventListener('error', function() {
                    if (i === sources.length - 1) {
                        showFallback(video);
                    }
                });
            });

            video.addEventListener('loadedmetadata', function() {
                if (this.videoWidth === 0 && this.videoHeight === 0) {
                    showFallback(this);
                }
            });
        });

        function showFallback(videoEl) {
            const wrapper = videoEl.closest('.video-wrapper');
            if (wrapper) {
                videoEl.style.display = 'none';
                const fallback = wrapper.querySelector('.video-fallback');
                if (fallback) fallback.style.display = 'flex';
            }
        }
    </script>

