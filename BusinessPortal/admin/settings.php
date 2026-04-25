<?php

/**
 * admin/settings.php — Site settings (SEO / Social / Tracking / Business).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Site Settings';
$breadcrumb  = [['label' => 'Settings']];

$repo     = new SiteSettingsRepository($pdo);
$settings = $repo->all();
$v = fn(string $k, string $def = '') => htmlspecialchars((string)($settings[$k] ?? $def), ENT_QUOTES);

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Site Settings</h1>
                    <p class="page-subtitle">Manage SEO, social media, tracking and business info used across the public site.</p>
                </div>
                <div class="page-actions">
                    <button type="submit" form="settingsForm" class="btn btn-primary">
                        <i class='bx bx-save'></i> Save All Changes
                    </button>
                </div>
            </div>

            <form id="settingsForm" class="row g-4">
                <!-- Sidebar tabs -->
                <div class="col-lg-3">
                    <div class="card" style="padding:8px;">
                        <ul class="nav flex-column settings-tabs" id="settingsTabs">
                            <li><a class="settings-tab active" data-target="tabSeo"><i class='bx bx-search-alt'></i> SEO</a></li>
                            <li><a class="settings-tab" data-target="tabSocial"><i class='bx bx-link'></i> Social Media</a></li>
                            <li><a class="settings-tab" data-target="tabTracking"><i class='bx bx-line-chart'></i> Tracking &amp; Analytics</a></li>
                            <li><a class="settings-tab" data-target="tabBusiness"><i class='bx bx-buildings'></i> Business Info</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-9">

                    <!-- SEO -->
                    <div class="settings-panel" id="tabSeo">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-search-alt text-primary'></i> SEO Defaults</h6>
                            </div>
                            <div class="card-section">
                                <div class="pl-field">
                                    <label class="pl-label">Default Site Title</label>
                                    <input type="text" name="seo_default_title" class="pl-input" value="<?= $v('seo_default_title') ?>">
                                    <div class="pl-hint">Used as fallback `&lt;title&gt;` tag and og:title.</div>
                                </div>
                                <div class="pl-field">
                                    <label class="pl-label">Default Meta Description</label>
                                    <textarea name="seo_default_description" class="pl-input" rows="3"><?= $v('seo_default_description') ?></textarea>
                                    <div class="pl-hint">Ideally 150–160 characters.</div>
                                </div>
                                <div class="pl-field">
                                    <label class="pl-label">Default Meta Keywords</label>
                                    <textarea name="seo_default_keywords" class="pl-input" rows="2"><?= $v('seo_default_keywords') ?></textarea>
                                </div>
                                <div class="pl-field">
                                    <label class="pl-label">Default OG Image URL</label>
                                    <input type="url" name="seo_og_image" class="pl-input" value="<?= $v('seo_og_image') ?>">
                                    <div class="pl-hint">Shown when shared on Facebook / LinkedIn / Twitter. Recommended 1200×630.</div>
                                </div>
                                <div class="pl-field mb-0">
                                    <label class="pl-label">Google Site Verification</label>
                                    <input type="text" name="seo_google_verification" class="pl-input" value="<?= $v('seo_google_verification') ?>"
                                        placeholder="e.g. ciaB6dQnA93KBhXoYEBlFX5R0ODHyobOopZtgTFI_OA">
                                    <div class="pl-hint">Content of the `google-site-verification` meta tag.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social -->
                    <div class="settings-panel" id="tabSocial" style="display:none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-link text-primary'></i> Social Media Profiles</h6>
                            </div>
                            <div class="card-section">
                                <?php
                                $socials = [
                                    ['social_facebook',  'Facebook',  'bxl-facebook-circle', 'https://facebook.com/your-page'],
                                    ['social_instagram', 'Instagram', 'bxl-instagram-alt',   'https://instagram.com/your-handle'],
                                    ['social_youtube',   'YouTube',   'bxl-youtube',         'https://youtube.com/@yourchannel'],
                                    ['social_twitter',   'Twitter / X','bxl-twitter',        'https://x.com/your-handle'],
                                    ['social_linkedin',  'LinkedIn',  'bxl-linkedin-square', 'https://linkedin.com/company/your-company'],
                                    ['social_tiktok',    'TikTok',    'bxl-tiktok',          'https://tiktok.com/@your-handle'],
                                ];
                                foreach ($socials as [$k, $lbl, $ico, $ph]): ?>
                                    <div class="pl-field">
                                        <label class="pl-label"><i class='bx <?= $ico ?>'></i> <?= $lbl ?> URL</label>
                                        <input type="url" name="<?= $k ?>" class="pl-input"
                                            placeholder="<?= $ph ?>" value="<?= $v($k) ?>">
                                    </div>
                                <?php endforeach; ?>
                                <div class="pl-field mb-0">
                                    <label class="pl-label">Twitter Handle (for Twitter Cards)</label>
                                    <input type="text" name="social_twitter_handle" class="pl-input"
                                        placeholder="@yourhandle" value="<?= $v('social_twitter_handle') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tracking -->
                    <div class="settings-panel" id="tabTracking" style="display:none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-line-chart text-primary'></i> Tracking &amp; Analytics</h6>
                            </div>
                            <div class="card-section">
                                <div class="pl-field">
                                    <label class="pl-label">Google Analytics 4 ID</label>
                                    <input type="text" name="tracking_ga4_id" class="pl-input"
                                        placeholder="G-XXXXXXXXXX" value="<?= $v('tracking_ga4_id') ?>">
                                </div>
                                <div class="pl-field">
                                    <label class="pl-label">Google Ads Conversion ID</label>
                                    <input type="text" name="tracking_google_ads_id" class="pl-input"
                                        placeholder="AW-XXXXXXXXXX" value="<?= $v('tracking_google_ads_id') ?>">
                                </div>
                                <div class="pl-field">
                                    <label class="pl-label">Google Tag Manager ID</label>
                                    <input type="text" name="tracking_gtm_id" class="pl-input"
                                        placeholder="GTM-XXXXXXX" value="<?= $v('tracking_gtm_id') ?>">
                                </div>
                                <div class="pl-field">
                                    <label class="pl-label">Meta (Facebook) Pixel ID</label>
                                    <input type="text" name="tracking_meta_pixel" class="pl-input"
                                        placeholder="e.g. 1234567890" value="<?= $v('tracking_meta_pixel') ?>">
                                </div>
                                <div class="pl-field mb-0">
                                    <label class="pl-label">TikTok Pixel ID</label>
                                    <input type="text" name="tracking_tiktok_pixel" class="pl-input"
                                        value="<?= $v('tracking_tiktok_pixel') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business -->
                    <div class="settings-panel" id="tabBusiness" style="display:none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-buildings text-primary'></i> Business Information</h6>
                            </div>
                            <div class="card-section">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <div class="pl-field mb-0">
                                            <label class="pl-label">Business Name</label>
                                            <input type="text" name="business_name" class="pl-input" value="<?= $v('business_name') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="pl-field mb-0">
                                            <label class="pl-label">Tagline</label>
                                            <input type="text" name="business_tagline" class="pl-input" value="<?= $v('business_tagline') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <div class="pl-field mb-0">
                                            <label class="pl-label">Phone</label>
                                            <input type="text" name="business_phone" class="pl-input" value="<?= $v('business_phone') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pl-field mb-0">
                                            <label class="pl-label">Email</label>
                                            <input type="email" name="business_email" class="pl-input" value="<?= $v('business_email') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-field mt-3">
                                    <label class="pl-label">Street Address</label>
                                    <input type="text" name="business_address" class="pl-input" value="<?= $v('business_address') ?>">
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <div class="pl-field mb-0">
                                            <label class="pl-label">City</label>
                                            <input type="text" name="business_city" class="pl-input" value="<?= $v('business_city') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="pl-field mb-0">
                                            <label class="pl-label">State</label>
                                            <input type="text" name="business_state" class="pl-input" value="<?= $v('business_state') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="pl-field mb-0">
                                            <label class="pl-label">ZIP</label>
                                            <input type="text" name="business_zip" class="pl-input" value="<?= $v('business_zip') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-field mt-3">
                                    <label class="pl-label">Working Hours</label>
                                    <textarea name="business_hours" class="pl-input" rows="2"><?= $v('business_hours') ?></textarea>
                                    <div class="pl-hint">Used in footer and local business schema.</div>
                                </div>
                                <div class="pl-field">
                                    <label class="pl-label">Google Maps Embed URL (iframe src)</label>
                                    <textarea name="business_map_embed" class="pl-input" rows="3" placeholder="https://www.google.com/maps/embed?pb=..."><?= $v('business_map_embed') ?></textarea>
                                    <div class="pl-hint">From Google Maps → Share → Embed a map → copy the <b>src</b> URL only (not the full iframe).</div>
                                </div>
                                <div class="pl-field mb-0">
                                    <label class="pl-label">Google Maps Share Link</label>
                                    <input type="url" name="business_map_link" class="pl-input" value="<?= $v('business_map_link') ?>" placeholder="https://maps.app.goo.gl/...">
                                    <div class="pl-hint">Short share link used for the "Get Directions" button on contact page.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <style>
            .settings-tabs { list-style:none; margin:0; padding:0; }
            .settings-tab {
                display:flex; align-items:center; gap:10px;
                padding:12px 14px;
                border-radius:8px;
                font-size:13px; font-weight:500;
                color:var(--text-sub);
                cursor:pointer;
                transition: background .15s, color .15s;
                margin-bottom:2px;
            }
            .settings-tab i { font-size:18px; }
            .settings-tab:hover { background:var(--surface-muted); color:var(--text); }
            .settings-tab.active {
                background:var(--brand-l, #efe6d8);
                color:var(--brand, #8b6f4e);
                font-weight:600;
            }
            .pl-hint { font-size:11px; color:var(--text-sub); margin-top:4px; }
        </style>

        <script>
        (function () {
            const tabs = document.querySelectorAll('.settings-tab');
            const panels = document.querySelectorAll('.settings-panel');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    panels.forEach(p => p.style.display = 'none');
                    const el = document.getElementById(tab.dataset.target);
                    if (el) el.style.display = '';
                });
            });

            document.getElementById('settingsForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(e.target);
                try {
                    const res = await fetch('../auth/save-settings.php', { method:'POST', body:fd });
                    const data = await res.json();
                    showToast(data.success ? 'Settings saved successfully.' : (data.message || 'Failed to save.'),
                              data.success ? 'success' : 'error');
                } catch { showToast('Network error.', 'error'); }
            });
        })();
        </script>
    </div>
</body>
</html>
