<?php
/**
 * auth/save-settings.php — bulk-save site settings.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$allowedKeys = [
    'seo_default_title','seo_default_description','seo_default_keywords',
    'seo_og_image','seo_google_verification',
    'social_facebook','social_instagram','social_youtube','social_twitter',
    'social_tiktok','social_linkedin','social_twitter_handle',
    'tracking_ga4_id','tracking_google_ads_id','tracking_meta_pixel',
    'tracking_gtm_id','tracking_tiktok_pixel',
    'business_name','business_phone','business_email','business_address',
    'business_city','business_state','business_zip','business_hours','business_tagline',
    'business_map_embed','business_map_link',
];

$pairs = [];
foreach ($allowedKeys as $k) {
    if (array_key_exists($k, $_POST)) {
        $pairs[$k] = trim((string)$_POST[$k]);
    }
}

try {
    (new SiteSettingsRepository($pdo))->setMany($pairs);
    echo json_encode(['success' => true, 'message' => 'Settings saved.', 'count' => count($pairs)]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
