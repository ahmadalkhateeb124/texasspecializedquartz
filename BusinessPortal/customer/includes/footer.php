   
   <!-- ══ Page Footer ═════════════════════════════════════════════ -->
   <footer class="page-footer">
       <span>© <script>
               document.write(new Date().getFullYear())
           </script> Granite Artists. All rights reserved.</span>
   </footer>

   </div><!-- /.main-wrapper -->

   <!-- ══ Toast container ══════════════════════════════════════════ -->
   <div id="toast-container"></div>

   <!-- ══ Scripts ═══════════════════════════════════════════════════ -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

   <script>
       /* ── Sidebar (mobile) ─────────────────────────────────────── */
       (function() {
           const sidebar = document.getElementById('appSidebar');
           const overlay = document.getElementById('sidebarOverlay');
           const toggle = document.getElementById('sidebarToggle');
           const open = () => {
               sidebar?.classList.add('sidebar-open');
               overlay?.classList.add('show');
           };
           const close = () => {
               sidebar?.classList.remove('sidebar-open');
               overlay?.classList.remove('show');
           };
           toggle?.addEventListener('click', () => sidebar?.classList.contains('sidebar-open') ? close() : open());
           overlay?.addEventListener('click', close);
       })();

       /* ── Toast ────────────────────────────────────────────────── */
       window.showToast = function(message, type = 'info') {
           const icons = {
               success: 'bx-check-circle',
               error: 'bx-x-circle',
               warning: 'bx-error',
               info: 'bx-info-circle'
           };
           const colors = {
               success: 'var(--success)',
               error: 'var(--danger)',
               warning: 'var(--warning)',
               info: 'var(--info)'
           };
           const el = document.createElement('div');
           el.className = `toast-msg ${type}`;
           el.innerHTML = `<i class='bx ${icons[type]||icons.info}' style="font-size:17px;color:${colors[type]};flex-shrink:0;"></i>
        <span style="flex:1;">${message}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;font-size:15px;color:var(--muted);padding:0;line-height:1;"><i class='bx bx-x'></i></button>`;
           document.getElementById('toast-container').appendChild(el);
           setTimeout(() => {
               el.style.animation = 'slideOut .25s ease forwards';
               setTimeout(() => el.remove(), 250);
           }, 4000);
       };
   </script>

   <?= $extraJs ?? '' ?>

   </body>

   </html>