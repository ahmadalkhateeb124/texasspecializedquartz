/**
 * sw.js — Minimal service worker for PWA installability.
 *
 * Browsers require a service worker to qualify a site as installable.
 * This worker doesn't aggressively cache — it just lets the browser show
 * the install prompt. We can layer offline caching later if needed.
 */

const CACHE_VERSION = 'tsqg-portal-v1';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((k) => k !== CACHE_VERSION)
                    .map((k) => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

/**
 * Network-first fetch. If offline, fall back to a cached copy of the page
 * (when one exists). HTML responses are cached opportunistically so the
 * dashboard can open at least its last seen state when offline.
 */
self.addEventListener('fetch', (event) => {
    const req = event.request;
    if (req.method !== 'GET') return;

    const url = new URL(req.url);
    if (url.origin !== self.location.origin) return;

    // Don't cache API/auth endpoints
    if (url.pathname.includes('/auth/') || url.pathname.includes('/PHPMail/')) return;

    event.respondWith(
        fetch(req)
            .then((response) => {
                if (response && response.ok && req.mode === 'navigate') {
                    const copy = response.clone();
                    caches.open(CACHE_VERSION).then((cache) => cache.put(req, copy));
                }
                return response;
            })
            .catch(() => caches.match(req))
    );
});
