/**
 * customer/training.js — search + video error fallback.
 */
(function () {
    const search = document.getElementById('videoSearch');
    if (search) {
        search.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.video-item').forEach(el => {
                el.style.display = !q || el.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }

    const showFallback = videoEl => {
        const wrapper = videoEl.closest('.video-wrapper');
        if (!wrapper) return;
        videoEl.style.display = 'none';
        const fb = wrapper.querySelector('.video-fallback');
        if (fb) fb.style.display = 'flex';
    };

    document.querySelectorAll('.video-wrapper video').forEach(video => {
        video.addEventListener('error', () => showFallback(video));

        const sources = video.querySelectorAll('source');
        sources.forEach((source, i) => {
            source.addEventListener('error', () => {
                if (i === sources.length - 1) showFallback(video);
            });
        });

        video.addEventListener('loadedmetadata', function () {
            if (this.videoWidth === 0 && this.videoHeight === 0) showFallback(this);
        });
    });
})();
