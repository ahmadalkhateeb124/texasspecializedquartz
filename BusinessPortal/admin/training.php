<?php

/**
 * admin/training.php — Video Training Management
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/videos-db.php';

requireAdmin();

// Generate CSRF token if not exists
csrfToken();

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
    .video-thumb {
        width: 160px;
        height: 96px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        background: #111;
        flex-shrink: 0;
    }

    .video-thumb video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Grid view cards */
    .video-grid-card {
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--color-surface);
        transition: var(--t);
    }



    .video-grid-card video {
        width: 100%;
        height: 200px;
        display: block;
        background: #111;
    }

    .video-grid-body {
        padding: 1.25rem;
    }

    .video-grid-title {
        font-weight: 600;
        color: var(--color-text);
        font-size: 1rem;
        margin-bottom: .75rem;
        line-height: 1.4;
    }

    .video-grid-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        font-size: .8rem;
        color: var(--color-text-sub);
        padding-top: .75rem;
        border-top: 1px solid var(--color-border);
    }

    .video-grid-meta i {
        font-size: .9rem;
        vertical-align: middle;
    }

    /* View toggle */
    .view-toggle {
        display: inline-flex;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-sm);
        overflow: hidden;
    }

    .view-toggle .btn {
        border: none;
        border-radius: 0;
        padding: .35rem .75rem;
        font-size: .85rem;
        color: var(--color-text-sub);
        background: transparent;
    }

    .view-toggle .btn.active {
        background: var(--color-primary);
        color: #fff;
    }
</style>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title"><i class='bx bx-video me-2'></i>Video Training</h1>
                    <p class="page-subtitle"><?= $videoCount ?> total video<?= $videoCount != 1 ? 's' : '' ?></p>
                </div>
                <div class="page-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">
                        <i class='bx bx-plus'></i> Upload Video
                    </button>
                </div>
            </div>

            <?php if (isset($dbError)): ?>
                <div class="alert alert-danger mb-3"><i class='bx bx-error me-2'></i><?= $dbError ?></div>
            <?php endif; ?>

            <!-- KPI strip -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label">Total Videos</div>
                                <div class="kpi-value"><?= $videoCount ?></div>
                            </div>
                            <div class="kpi-icon" style="background:var(--color-primary-l);">
                                <i class='bx bx-video' style="color:var(--color-primary);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Videos Display -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-play-circle text-primary'></i> All Videos</h6>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text" style="border-right:none;">
                                <i class='bx bx-search' style="font-size:15px;"></i>
                            </span>
                            <input type="text" id="videoSearch" class="form-control"
                                placeholder="Search videos…" style="border-left:none;">
                        </div>
                        <div class="view-toggle">
                            <button class="btn active" id="tableViewBtn" title="Table view"><i class='bx bx-list-ul'></i></button>
                            <button class="btn" id="gridViewBtn" title="Grid view"><i class='bx bx-grid-alt'></i></button>
                        </div>
                    </div>
                </div>

                <?php if ($videoCount > 0): ?>
                    <!-- Table View -->
                    <div id="tableView">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Preview</th>
                                        <th>Video Name</th>
                                        <th>File Size</th>
                                        <th>Duration</th>
                                        <th>Uploaded</th>
                                        <th class="text-end" style="width:140px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="videosTbody">
                                    <?php foreach ($videos as $video): ?>
                                        <tr>
                                            <td>
                                                <div class="video-thumb">
                                                    <video
                                                        preload="metadata"
                                                     
                                                        <source src="<?= htmlspecialchars($videoManager->getFileUrl($video['video_file'])) ?>" type="<?= getVideoMimeType($video['video_file']) ?>">
                                                    </video>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class='bx bx-film' style="font-size:1.25rem; color:var(--color-primary);"></i>
                                                    <div>
                                                        <div style="font-weight:600; color:var(--color-text);"><?= htmlspecialchars($video['video_name']) ?></div>
                                                        <div style="font-size:11px; color:var(--color-text-sub);">ID #<?= $video['id'] ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="color:var(--color-text-sub);"><?= VideoManager::formatFileSize($video['file_size']) ?></td>
                                            <td style="color:var(--color-text-sub);"><?= htmlspecialchars($video['duration'] ?? '—') ?></td>
                                            <td style="color:var(--color-text-sub); white-space:nowrap;"><?= date('M j, Y', strtotime($video['created_at'])) ?></td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                               
                                                    <button class="btn btn-icon btn-sm btn-outline" title="Edit"
                                                        data-bs-toggle="modal" data-bs-target="#editVideoModal"
                                                        onclick="setEditVideoId(<?= $video['id'] ?>, '<?= htmlspecialchars($video['video_name'], ENT_QUOTES) ?>')">
                                                        <i class='bx bx-edit'></i>
                                                    </button>
                                                    <button class="btn btn-icon btn-sm btn-outline text-danger" title="Delete"
                                                        onclick="deleteVideo(<?= $video['id'] ?>, '<?= htmlspecialchars($video['video_name'], ENT_QUOTES) ?>')">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Grid View -->
<!-- Grid View -->
<div id="gridView" style="display:none;">
    <div class="card-body">
        <div class="row g-3" id="videosGrid">
            <?php foreach ($videos as $video): 
                $videoUrl = $videoManager->getFileUrl($video['video_file']);
                $fileExists = $videoManager->fileExists($video['video_file']);
                $mimeType = getVideoMimeType($video['video_file']);
                $extension = strtolower(pathinfo($video['video_file'], PATHINFO_EXTENSION));
            ?>
                <div class="col-md-6 col-lg-4 video-grid-item">
                    <div class="video-grid-card h-100">
                        <div class="video-wrapper">
                            <?php if ($fileExists): ?>
                                <video
                                     controls
                                        playsinline
                                        preload="metadata"
                                        height="200"
                                        class="training-video"
    
                                    class="training-video"
                                    
                                   
                                    <!-- Multiple sources for compatibility -->
                                    <source src="<?= htmlspecialchars($videoUrl) ?>" type="<?= $mimeType ?>">
                                    <?php if ($extension === 'mp4'): ?>
                                        <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/mp4; codecs=avc1.42E01E,mp4a.40.2">
                                    <?php elseif ($extension === 'mov'): ?>
                                        <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/quicktime">
                                        <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
                                    <?php endif; ?>
                                    Your browser does not support the video tag.
                                </video>
                                
                                <!-- Download button -->
                                <div class="video-actions mt-2 text-center">
                                
                                </div>
                            <?php else: ?>
                                <div class="video-fallback" style="height:200px;">
                                    <i class='bx bx-error-circle'></i>
                                    <span>Video file not found</span>
                                    <div class="mt-2 text-muted small">
                                        <?= htmlspecialchars($video['video_file']) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="video-grid-body">
                            <div class="video-grid-title"><?= htmlspecialchars($video['video_name']) ?></div>
                            <div class="d-flex gap-1 mb-3">
                                <button class="btn btn-icon btn-sm btn-outline flex-fill" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#editVideoModal"
                                    onclick="setEditVideoId(<?= $video['id'] ?>, '<?= htmlspecialchars($video['video_name'], ENT_QUOTES) ?>')">
                                    <i class='bx bx-edit'></i> Edit
                                </button>
                                <button class="btn btn-icon btn-sm btn-outline text-danger flex-fill" title="Delete"
                                    onclick="deleteVideo(<?= $video['id'] ?>, '<?= htmlspecialchars($video['video_name'], ENT_QUOTES) ?>')">
                                    <i class='bx bx-trash'></i> Delete
                                </button>
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
</div>                <?php else: ?>
                
                
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class='bx bx-video'></i></div>
                        <p class="empty-state-title">No videos uploaded yet</p>
                        <p class="empty-state-desc">Upload your first training video to get started.</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">
                            <i class='bx bx-plus'></i> Upload Video
                        </button>
                    </div>
                <?php endif; ?>
            </div>

        </main>

 

    <!-- Upload Video Modal -->
    <div class="modal fade" id="uploadVideoModal" tabindex="-1" aria-labelledby="uploadVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadVideoModalLabel"><i class='bx bx-upload me-2'></i>Upload New Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadVideoForm" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="videoName" class="form-label">Video Name *</label>
                            <input type="text" class="form-control" id="videoName" name="video_name" placeholder="e.g., Introduction to Products" required>
                        </div>
                        <div class="mb-3">
                            <label for="videoFile" class="form-label">Video File (MP4, WebM, OGG, MOV, AVI) *</label>
                            <input type="file" class="form-control" id="videoFile" name="video" accept="video/*" required>
                            <small class="form-text text-muted">Max file size: 500MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="uploadBtn"><i class='bx bx-upload me-1'></i> Upload Video</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Video Modal -->
    <div class="modal fade" id="editVideoModal" tabindex="-1" aria-labelledby="editVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVideoModalLabel"><i class='bx bx-edit me-2'></i>Edit Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editVideoForm" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" id="editVideoId" name="video_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editVideoName" class="form-label">Video Name *</label>
                            <input type="text" class="form-control" id="editVideoName" name="video_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editVideoFile" class="form-label">Replace with New Video File (Optional)</label>
                            <input type="file" class="form-control" id="editVideoFile" name="video" accept="video/*">
                            <small class="form-text text-muted">Leave empty to keep the current video. Max file size: 500MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="editBtn"><i class='bx bx-check me-1'></i> Update Video</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Search
        (function() {
            const search = document.getElementById('videoSearch');
            const tableRows = () => document.querySelectorAll('#videosTbody tr');
            const gridItems = () => document.querySelectorAll('.video-grid-item');

            function applySearch() {
                const q = search?.value.toLowerCase() || '';
                tableRows().forEach(tr => {
                    tr.style.display = !q || tr.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
                gridItems().forEach(el => {
                    el.style.display = !q || el.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            }
            search?.addEventListener('input', applySearch);
        })();

        // View Toggle
        document.getElementById('tableViewBtn')?.addEventListener('click', function() {
            document.getElementById('tableView').style.display = '';
            document.getElementById('gridView').style.display = 'none';
            this.classList.add('active');
            document.getElementById('gridViewBtn').classList.remove('active');
        });

        document.getElementById('gridViewBtn')?.addEventListener('click', function() {
            document.getElementById('gridView').style.display = '';
            document.getElementById('tableView').style.display = 'none';
            this.classList.add('active');
            document.getElementById('tableViewBtn').classList.remove('active');
        });

        // Upload Video
        document.getElementById('uploadVideoForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const uploadBtn = document.getElementById('uploadBtn');
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

            try {
                const response = await fetch('../auth/upload-video.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire('Success!', 'Video uploaded successfully', 'success');
                    this.reset();
                    bootstrap.Modal.getInstance(document.getElementById('uploadVideoModal')).hide();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Error!', data.message || 'Failed to upload video', 'error');
                }
            } catch (error) {
                Swal.fire('Error!', 'An error occurred while uploading video', 'error');
                console.error(error);
            } finally {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="bx bx-upload me-1"></i> Upload Video';
            }
        });

        // Set Edit Video ID
        function setEditVideoId(videoId, videoName) {
            document.getElementById('editVideoId').value = videoId;
            document.getElementById('editVideoName').value = videoName;
        }

        // Edit/Update Video
        document.getElementById('editVideoForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const editBtn = document.getElementById('editBtn');
            editBtn.disabled = true;
            editBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

            try {
                const response = await fetch('../auth/update-video.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire('Success!', 'Video updated successfully', 'success');
                    this.reset();
                    bootstrap.Modal.getInstance(document.getElementById('editVideoModal')).hide();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Error!', data.message || 'Failed to update video', 'error');
                }
            } catch (error) {
                Swal.fire('Error!', 'An error occurred while updating video', 'error');
                console.error(error);
            } finally {
                editBtn.disabled = false;
                editBtn.innerHTML = '<i class="bx bx-check me-1"></i> Update Video';
            }
        });

        // Delete Video
        function deleteVideo(videoId, videoName) {
            Swal.fire({
                title: 'Delete Video?',
                text: `Are you sure you want to delete "${videoName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const formData = new FormData();
                        formData.append('video_id', videoId);

                        const response = await fetch('../auth/delete-video.php', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Deleted!', 'Video has been deleted successfully', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to delete video', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error!', 'An error occurred while deleting video', 'error');
                        console.error(error);
                    }
                }
            });
        }

        // Handle video loading errors
        function handleVideoError(videoElement) {
            console.log('Video failed to load:', videoElement.currentSrc);
        }
    </script>

