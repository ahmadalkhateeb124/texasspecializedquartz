/**
 * customer/profile.js — toggle password visibility.
 */
document.querySelectorAll('.toggle-pwd').forEach(btn => {
    btn.addEventListener('click', () => {
        const field = document.getElementById(btn.dataset.target);
        if (!field) return;
        const icon = btn.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'bx bx-hide';
        } else {
            field.type = 'password';
            icon.className = 'bx bx-show';
        }
    });
});
