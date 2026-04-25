<?php
/**
 * pages/newsdetail.php — Article detail (DB-driven, editorial redesign).
 * Page is wrapped by parts/header.php + parts/footer.php from index.php.
 */

require_once __DIR__ . '/../BusinessPortal/src/bootstrap.php';

$repo = new BlogRepository($pdo);

$slug = $_GET['slug'] ?? '';
$id   = (int)($_GET['id'] ?? 0);
$post = $slug ? $repo->findBySlug($slug) : ($id > 0 ? $repo->findById($id) : null);

if (!$post || ($post['status'] ?? '') !== 'published') {
    header('HTTP/1.0 404 Not Found');
    header('Location: /blog');
    exit;
}

$post['date']    = $post['publish_date'];
$plainContent    = trim(strip_tags($post['content']));
$readingMinutes  = max(1, (int)ceil(max(1, str_word_count($plainContent)) / 220));
$canonicalUrl    = 'https://texasspecializedquartz.com/newsdetail/' . $post['slug'];
$ogImageUrl      = 'https://texasspecializedquartz.com/' . ltrim($post['image'] ?? '', '/');
$tagsList        = array_values(array_filter(array_map('trim', explode(',', (string)($post['tags'] ?? '')))));

$imgPath = $post['image']
    ? '/' . ltrim($post['image'], '/')
    : '/images/Granit-Img/logo-gg.jpg';

include __DIR__ . '/../parts/news/article-schema.php';
include __DIR__ . '/../parts/news/article-body.php';
include __DIR__ . '/../parts/news/related-posts.php';
