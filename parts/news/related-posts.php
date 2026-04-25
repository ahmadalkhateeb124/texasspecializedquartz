<?php
/**
 * Related posts — bz-card style to match /blog listing.
 * Expects: $post (current post array with id, slug, tags)
 */
if (!isset($post)) return;

$currentId   = (int)($post['id'] ?? 0);
$currentTags = $post['tags'] ?? '';

$related = [];
try {
    if ($currentTags) {
        $likeTerms = array_slice(array_map('trim', explode(',', $currentTags)), 0, 3);
        $conds  = [];
        $params = [':cid' => $currentId];
        foreach ($likeTerms as $i => $t) {
            $conds[] = "tags LIKE :t$i";
            $params[":t$i"] = "%$t%";
        }
        $where = $conds ? ' AND (' . implode(' OR ', $conds) . ')' : '';
        $stmt = $pdo->prepare("
            SELECT id, title, slug, image, tags, publish_date
            FROM blog_posts
            WHERE status='published' AND id != :cid $where
            ORDER BY publish_date DESC
            LIMIT 3
        ");
        $stmt->execute($params);
        $related = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    if (count($related) < 3) {
        $existingIds = array_merge([$currentId], array_column($related, 'id'));
        $placeholders = implode(',', array_fill(0, count($existingIds), '?'));
        $need = 3 - count($related);
        $stmt = $pdo->prepare("
            SELECT id, title, slug, image, tags, publish_date
            FROM blog_posts
            WHERE status='published' AND id NOT IN ($placeholders)
            ORDER BY publish_date DESC
            LIMIT $need
        ");
        $stmt->execute($existingIds);
        $related = array_merge($related, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (Throwable $e) { return; }

if (!$related) return;
?>
<section class="nd-related" aria-label="Related articles">
    <div class="nd-related-shell">
        <div class="nd-related-head">
            <div class="nd-related-eyebrow">— Continue reading</div>
            <h3>You may also like</h3>
        </div>

        <div class="nd-related-grid">
            <?php
            $baseHref = $base_url ?? '/';
            foreach ($related as $r):
                $url    = $baseHref . 'newsdetail/' . rawurlencode($r['slug']);
                $rImg   = $r['image']
                    ? $baseHref . ltrim($r['image'], '/')
                    : $baseHref . 'images/Granit-Img/logo-gg.jpg';
                $rTags  = array_values(array_filter(array_map('trim', explode(',', (string)($r['tags'] ?? '')))));
            ?>
                <article class="nd-related-card">
                    <a href="<?= htmlspecialchars($url) ?>" class="nd-related-media">
                        <img src="<?= htmlspecialchars($rImg) ?>"
                             alt="<?= htmlspecialchars($r['title']) ?>" loading="lazy">
                        <?php if (!empty($rTags)): ?>
                            <span class="nd-related-tag"><?= htmlspecialchars($rTags[0]) ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="nd-related-body">
                        <time><i class="far fa-calendar"></i>
                            <?= date('M j, Y', strtotime($r['publish_date'])) ?>
                        </time>
                        <h4>
                            <a href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($r['title']) ?></a>
                        </h4>
                        <a href="<?= htmlspecialchars($url) ?>" class="nd-related-cta">
                            Read article <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
