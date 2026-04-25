<?php

/**
 * BlogRepository — CRUD for the blog_posts table.
 */
class BlogRepository
{
    public function __construct(private PDO $pdo) {}

    /* ───────────────────── Reads ───────────────────── */

    /** All posts (admin list). */
    public function all(): array
    {
        return $this->pdo->query("
            SELECT id, title, slug, image, tags, publish_date, status, created_at, updated_at
            FROM blog_posts
            ORDER BY publish_date DESC, id DESC
        ")->fetchAll();
    }

    /** Published posts only, for public blog listing. */
    public function allPublished(int $limit = 0): array
    {
        $sql = "SELECT id, title, slug, image, tags, publish_date, content
                FROM blog_posts
                WHERE status = 'published'
                ORDER BY publish_date DESC, id DESC";
        if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Paginated published posts (DB-side LIMIT/OFFSET, optionally tag-filtered).
     */
    public function listPublished(int $limit, int $offset = 0, ?string $tag = null): array
    {
        $sql = "SELECT id, title, slug, image, tags, publish_date, content
                FROM blog_posts
                WHERE status = 'published'";
        $params = [];
        if ($tag !== null && $tag !== '') {
            $sql .= " AND CONCAT(',', REPLACE(tags, ', ', ','), ',') LIKE :tag";
            $params[':tag'] = '%,' . $tag . ',%';
        }
        $sql .= " ORDER BY publish_date DESC, id DESC LIMIT :lim OFFSET :off";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':lim', max(1, $limit), PDO::PARAM_INT);
        $stmt->bindValue(':off', max(0, $offset), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Count of published posts (optionally tag-filtered). */
    public function countPublished(?string $tag = null): int
    {
        $sql = "SELECT COUNT(*) FROM blog_posts WHERE status = 'published'";
        $params = [];
        if ($tag !== null && $tag !== '') {
            $sql .= " AND CONCAT(',', REPLACE(tags, ', ', ','), ',') LIKE :tag";
            $params[':tag'] = '%,' . $tag . ',%';
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /** Map of tag => post-count for the public blog (for filter chips). */
    public function tagCounts(int $maxTags = 50): array
    {
        $rows = $this->pdo->query(
            "SELECT tags FROM blog_posts WHERE status = 'published' AND tags <> ''"
        )->fetchAll(PDO::FETCH_COLUMN);

        $counts = [];
        foreach ($rows as $tagStr) {
            foreach (array_filter(array_map('trim', explode(',', (string)$tagStr))) as $t) {
                $counts[$t] = ($counts[$t] ?? 0) + 1;
            }
        }
        arsort($counts);
        return array_slice($counts, 0, $maxTags, true);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM blog_posts WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function slugTaken(string $slug, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT id FROM blog_posts WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT id FROM blog_posts WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        return (bool)$stmt->fetch();
    }

    /* ───────────────────── Writes ───────────────────── */

    /**
     * @param array $d keys: title, slug, image, content, tags, publish_date, status
     */
    public function create(array $d): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO blog_posts (title, slug, image, content, tags, publish_date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $d['title'],
            $d['slug'],
            $d['image']        ?? '',
            $d['content'],
            $d['tags']         ?? '',
            $d['publish_date'] ?? date('Y-m-d'),
            $d['status']       ?? 'published',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE blog_posts
            SET title = ?, slug = ?, image = ?, content = ?, tags = ?, publish_date = ?, status = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $d['title'],
            $d['slug'],
            $d['image']        ?? '',
            $d['content'],
            $d['tags']         ?? '',
            $d['publish_date'] ?? date('Y-m-d'),
            $d['status']       ?? 'published',
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$id]);
    }

    /* ───────────────────── Helpers ───────────────────── */

    /** Generate a URL slug from a title. */
    public static function makeSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }
}
