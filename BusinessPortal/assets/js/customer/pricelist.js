/**
 * customer/pricelist.js — live search filter for the price list grid.
 */
(function () {
    const search = document.getElementById('fileSearch');
    if (!search) return;
    search.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#priceListTable .cust-pl-card').forEach(card => {
            card.style.display = !q || card.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
