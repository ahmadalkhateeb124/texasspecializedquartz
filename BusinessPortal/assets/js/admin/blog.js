/**
 * admin/blog.js — list page: search + delete flow.
 */
(function () {
    const search = document.getElementById('blogSearch');
    search?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#blogTbody tr').forEach(tr => {
            tr.style.display = !q || tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    document.querySelectorAll('.delete-post-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id    = btn.dataset.id;
            const title = btn.dataset.title;
            const ok = await confirmDialog({
                title: 'Delete blog post?',
                message: `"${title}" will be permanently deleted.`,
                confirmText: 'Delete post',
                icon: 'bx-trash',
            });
            if (!ok) return;

            const fd = new FormData();
            fd.append('id', id);
            try {
                const res  = await fetch('../auth/delete_blog_post.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, 'success');
                    btn.closest('tr')?.remove();
                } else {
                    showToast(data.message || 'Failed to delete post.', 'error');
                }
            } catch {
                showToast('Network error.', 'error');
            }
        });
    });
})();
