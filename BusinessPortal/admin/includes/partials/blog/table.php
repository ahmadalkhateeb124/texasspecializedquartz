<?php /** Expects: $posts */ ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-list-ul text-primary'></i> All Posts</h6>
        <div class="d-flex gap-2">
            <input type="text" id="blogSearch" class="form-control form-control-sm"
                placeholder="Search…" style="width:220px;">
        </div>
    </div>
    <div class="table-responsive">
        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <i class='bx bx-news empty-state-icon'></i>
                <p class="empty-state-title">No posts yet</p>
                <p class="empty-state-desc">Create your first blog post.</p>
                <a href="blog-new" class="btn btn-primary btn-sm">
                    <i class='bx bx-plus'></i> New Post
                </a>
            </div>
        <?php else: ?>
            <table class="table table-hover mb-0" id="blogTable">
                <thead>
                    <tr>
                        <th style="width:70px;">Cover</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Publish Date</th>
                        <th>Updated</th>
                        <th class="text-end" style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="blogTbody">
                    <?php foreach ($posts as $p):
                        $imgWebPath = site_asset(ltrim($p['image'] ?? '', '/'));
                        $hasImg     = !empty($p['image']) && file_exists(site_path(ltrim($p['image'], '/')));
                    ?>
                        <tr>
                            <td>
                                <?php if ($hasImg): ?>
                                    <img src="<?= htmlspecialchars($imgWebPath) ?>"
                                        alt="" style="width:50px;height:40px;object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <div style="width:50px;height:40px;background:var(--color-bg-subdued);
                                                border-radius:4px;display:flex;align-items:center;
                                                justify-content:center;color:var(--color-text-sub);">
                                        <i class='bx bx-image'></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="blog-edit?id=<?= $p['id'] ?>"
                                    style="font-weight:600;color:var(--color-text);">
                                    <?= htmlspecialchars($p['title']) ?>
                                </a>
                                <div style="font-size:11px;color:var(--color-text-sub);">
                                    /<?= htmlspecialchars($p['slug']) ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($p['status'] === 'published'): ?>
                                    <span class="badge badge-success"><span class="dot"></span>Published</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral"><span class="dot"></span>Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="color:var(--color-text-sub);white-space:nowrap;">
                                <?= date('M j, Y', strtotime($p['publish_date'])) ?>
                            </td>
                            <td style="color:var(--color-text-sub);white-space:nowrap;">
                                <?= date('M j, Y', strtotime($p['updated_at'])) ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="/newsdetail/<?= htmlspecialchars($p['slug']) ?>" target="_blank"
                                        class="btn btn-icon btn-sm btn-outline" title="View">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="blog-edit?id=<?= $p['id'] ?>"
                                        class="btn btn-icon btn-sm btn-outline" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-icon btn-sm btn-outline text-danger delete-post-btn"
                                        data-id="<?= $p['id'] ?>"
                                        data-title="<?= htmlspecialchars($p['title']) ?>"
                                        title="Delete">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
