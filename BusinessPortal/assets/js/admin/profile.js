/**
 * admin/profile.js — toggle password visibility (shared selector via .toggle-pwd).
 */
document.querySelectorAll('.toggle-pwd').forEach(btn => {
    btn.addEventListener('click', () => {
        const f = document.getElementById(btn.dataset.target);
        if (!f) return;
        const i = btn.querySelector('i');
        if (f.type === 'password') { f.type = 'text';     i.className = 'bx bx-hide'; }
        else                        { f.type = 'password'; i.className = 'bx bx-show'; }
    });
});
