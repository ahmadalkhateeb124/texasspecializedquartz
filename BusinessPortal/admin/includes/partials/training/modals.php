<!-- ═══ Upload Video Modal ═══ -->
<div class="modal fade" id="uploadVideoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content pl-modal">
            <div class="modal-header pl-modal-header">
                <div class="pl-header-icon"><i class='bx bx-movie-play'></i></div>
                <div>
                    <h5 class="modal-title pl-modal-title">Upload Training Video</h5>
                    <p class="pl-modal-sub">Add a new video to the training library</p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadVideoForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="modal-body pl-modal-body">
                    <div class="pl-field">
                        <label for="videoName" class="pl-label">
                            Video Name <span class="pl-required">*</span>
                        </label>
                        <input type="text" class="pl-input" id="videoName" name="video_name"
                            placeholder="e.g., Introduction to Products" required>
                    </div>

                    <div class="pl-field">
                        <label class="pl-label">
                            Video File <span class="pl-required">*</span>
                        </label>
                        <div class="pl-file-zone" id="uploadVideoZone">
                            <input type="file" class="pl-file-input" id="videoFile" name="video" accept="video/*" required>
                            <div class="pl-file-placeholder">
                                <i class='bx bx-cloud-upload'></i>
                                <div class="pl-file-title">Click to upload or drag a video here</div>
                                <div class="pl-file-hint">MP4, WebM, OGG, MOV, AVI — up to 500 MB</div>
                            </div>
                            <div class="pl-file-selected" id="uploadVideoSelected" style="display:none;">
                                <i class='bx bx-movie'></i>
                                <span class="pl-file-name"></span>
                                <button type="button" class="pl-file-clear" title="Remove">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pl-modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class='bx bx-upload me-1'></i> Upload Video
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══ Edit Video Modal ═══ -->
<div class="modal fade" id="editVideoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content pl-modal">
            <div class="modal-header pl-modal-header">
                <div class="pl-header-icon"><i class='bx bx-edit'></i></div>
                <div>
                    <h5 class="modal-title pl-modal-title">Edit Video</h5>
                    <p class="pl-modal-sub">Update video name or replace the file</p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="editVideoForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" id="editVideoId" name="video_id">
                <div class="modal-body pl-modal-body">
                    <div class="pl-field">
                        <label for="editVideoName" class="pl-label">
                            Video Name <span class="pl-required">*</span>
                        </label>
                        <input type="text" class="pl-input" id="editVideoName" name="video_name" required>
                    </div>

                    <div class="pl-field">
                        <label class="pl-label">
                            Replace Video <span class="pl-optional">(optional)</span>
                        </label>
                        <div class="pl-file-zone" id="editVideoZone">
                            <input type="file" class="pl-file-input" id="editVideoFile" name="video" accept="video/*">
                            <div class="pl-file-placeholder">
                                <i class='bx bx-cloud-upload'></i>
                                <div class="pl-file-title">Click to upload a new video</div>
                                <div class="pl-file-hint">Leave empty to keep the current video — max 500 MB</div>
                            </div>
                            <div class="pl-file-selected" id="editVideoSelected" style="display:none;">
                                <i class='bx bx-movie'></i>
                                <span class="pl-file-name"></span>
                                <button type="button" class="pl-file-clear" title="Remove">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pl-modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editBtn">
                        <i class='bx bx-check me-1'></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
