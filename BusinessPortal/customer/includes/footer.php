    <footer class="page-footer" style="text-align:center;padding:20px;color:var(--text-sub);font-size:12px;border-top:1px solid var(--border);margin-top:40px;">
        <span>© 2026 Texas Specialized Quartz &amp; Granite. All rights reserved. Developed by <a href="https://webkoit.com/" target="_blank" rel="noopener" style="color:var(--brand,#b08d57);text-decoration:none;font-weight:600;">Webkoit</a></span>
    </footer>

    </div><!-- /.main-wrapper -->

    <!-- Toast container + custom confirm dialog -->
    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    <div id="tsqConfirm" class="tsq-confirm" role="dialog" aria-modal="true" aria-hidden="true" hidden>
        <div class="tsq-confirm-backdrop"></div>
        <div class="tsq-confirm-box" role="document">
            <div class="tsq-confirm-icon" data-icon><i class='bx bx-error-circle'></i></div>
            <h4 class="tsq-confirm-title" data-title>Are you sure?</h4>
            <p class="tsq-confirm-message" data-message>This action cannot be undone.</p>
            <div class="tsq-confirm-actions">
                <button type="button" class="btn btn-default" data-cancel>Cancel</button>
                <button type="button" class="btn btn-primary" data-ok>Confirm</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        /* ═══ Toast ═══ */
        window.showToast = function (message, type = 'info') {
            const icons = {
                success: 'bx-check-circle',
                error:   'bx-x-circle',
                warning: 'bx-error',
                info:    'bx-info-circle',
            };
            const el = document.createElement('div');
            el.className = `toast-msg ${type}`;
            el.innerHTML =
                `<i class='bx ${icons[type] || icons.info}'></i>
                 <span class="toast-msg-text">${message}</span>
                 <button type="button" class="toast-msg-close" aria-label="Close">
                     <i class='bx bx-x'></i>
                 </button>`;
            el.querySelector('.toast-msg-close').addEventListener('click', () => dismiss(el));
            document.getElementById('toast-container').appendChild(el);
            setTimeout(() => dismiss(el), 4500);
        };
        function dismiss(el) {
            if (!el || !el.parentNode) return;
            el.style.animation = 'toastOut .2s ease forwards';
            setTimeout(() => el.remove(), 200);
        }

        /* ═══ Custom confirm dialog ═══ */
        window.confirmDialog = function (opts = {}) {
            const {
                title       = 'Are you sure?',
                message     = 'This action cannot be undone.',
                confirmText = 'Confirm',
                cancelText  = 'Cancel',
                type        = 'danger',
                icon        = 'bx-error-circle',
            } = opts;

            return new Promise((resolve) => {
                const box = document.getElementById('tsqConfirm');
                box.querySelector('[data-title]').textContent   = title;
                box.querySelector('[data-message]').textContent = message;

                const iconEl = box.querySelector('[data-icon]');
                iconEl.className  = `tsq-confirm-icon tsq-${type}`;
                iconEl.innerHTML  = `<i class='bx ${icon}'></i>`;

                const okBtn     = box.querySelector('[data-ok]');
                const cancelBtn = box.querySelector('[data-cancel]');
                okBtn.textContent     = confirmText;
                cancelBtn.textContent = cancelText;
                okBtn.className       = `btn btn-${type === 'danger' ? 'danger' : 'primary'}`;

                box.hidden = false;
                void box.offsetWidth;
                box.classList.add('is-open');
                box.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';

                function close(result) {
                    box.classList.remove('is-open');
                    box.setAttribute('aria-hidden', 'true');
                    setTimeout(() => { box.hidden = true; }, 200);
                    document.body.style.overflow = '';
                    okBtn.onclick = null;
                    cancelBtn.onclick = null;
                    box.querySelector('.tsq-confirm-backdrop').onclick = null;
                    document.removeEventListener('keydown', onKey);
                    resolve(result);
                }
                function onKey(e) {
                    if (e.key === 'Escape') close(false);
                    if (e.key === 'Enter')  close(true);
                }

                okBtn.onclick     = () => close(true);
                cancelBtn.onclick = () => close(false);
                box.querySelector('.tsq-confirm-backdrop').onclick = () => close(false);
                document.addEventListener('keydown', onKey);
                setTimeout(() => okBtn.focus(), 50);
            });
        };

        /* Back-compat alias */
        window.confirmAction = (msg) =>
            confirmDialog({ title: 'Are you sure?', message: msg || 'This action cannot be undone.' });
    </script>

    <style>
        /* ═══ Toast ═══ */
        #toast-container {
            position: fixed;
            top: calc(var(--topbar-h, 60px) + 16px);
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .toast-msg {
            min-width: 300px;
            max-width: 420px;
            padding: 12px 14px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-left: 4px solid var(--text-sub);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(24, 20, 15, .12);
            color: var(--text);
            font-size: 13px;
            display: flex;
            gap: 10px;
            align-items: center;
            pointer-events: auto;
            animation: toastIn .22s ease;
        }
        .toast-msg > i:first-child {
            font-size: 20px;
            flex-shrink: 0;
            color: var(--text-sub);
        }
        .toast-msg-text { flex: 1; line-height: 1.4; }
        .toast-msg-close {
            background: none;
            border: 0;
            color: var(--text-dis);
            cursor: pointer;
            padding: 0;
            font-size: 16px;
            line-height: 1;
            flex-shrink: 0;
        }
        .toast-msg-close:hover { color: var(--text); }

        .toast-msg.success { border-left-color: var(--success); background: var(--success-l); }
        .toast-msg.success > i:first-child { color: var(--success); }

        .toast-msg.error   { border-left-color: var(--danger);  background: var(--danger-l);  }
        .toast-msg.error   > i:first-child { color: var(--danger); }

        .toast-msg.warning { border-left-color: var(--warning); background: var(--warning-l); }
        .toast-msg.warning > i:first-child { color: var(--warning); }

        @keyframes toastIn  { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes toastOut { to   { opacity: 0; transform: translateY(-8px); } }

        /* ═══ Custom confirm ═══ */
        .tsq-confirm {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: opacity .18s ease, visibility 0s .18s;
        }
        .tsq-confirm.is-open {
            opacity: 1;
            visibility: visible;
            transition: opacity .18s ease;
        }
        .tsq-confirm-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(24, 20, 15, .45);
            backdrop-filter: blur(2px);
        }
        .tsq-confirm-box {
            position: relative;
            width: 100%;
            max-width: 400px;
            background: var(--surface);
            border-radius: 14px;
            padding: 28px 24px 20px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(24, 20, 15, .25);
            transform: scale(.96);
            transition: transform .2s ease;
        }
        .tsq-confirm.is-open .tsq-confirm-box { transform: scale(1); }

        .tsq-confirm-icon {
            width: 56px; height: 56px;
            border-radius: 50%;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .tsq-confirm-icon.tsq-danger  { background: var(--danger-l);  color: var(--danger);  }
        .tsq-confirm-icon.tsq-primary { background: var(--brand-l);   color: var(--brand);   }
        .tsq-confirm-icon.tsq-warning { background: var(--warning-l); color: var(--warning); }

        .tsq-confirm-title {
            font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
            font-size: 22px;
            font-weight: 400;
            color: var(--text);
            margin: 0 0 6px;
        }
        .tsq-confirm-message {
            font-size: 13px;
            color: var(--text-sub);
            line-height: 1.55;
            margin: 0 0 20px;
        }
        .tsq-confirm-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .tsq-confirm-actions .btn {
            min-width: 110px;
            justify-content: center;
        }
    </style>

    <?= $extraJs ?? '' ?>
</body>

</html>
