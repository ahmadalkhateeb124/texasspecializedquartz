<?php

class GalleryRepository
{
    public const CATEGORIES = ['kitchen' => 'Kitchen', 'bathroom' => 'Bathroom', 'commercial' => 'Commercial'];
    public const MATERIALS  = ['granite' => 'Granite', 'quartz' => 'Quartz', 'marble' => 'Marble'];

    public function __construct(private PDO $pdo) {}

    public function all(bool $activeOnly = false): array
    {
        $sql = "SELECT * FROM gallery_photos";
        if ($activeOnly) $sql .= " WHERE status = 'active'";
        $sql .= " ORDER BY sort_order ASC, id DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM gallery_photos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO gallery_photos (image, caption, category, material, sort_order, status)
            VALUES (:img, :cap, :cat, :mat, :so, :st)
        ");
        $stmt->execute([
            ':img' => $d['image'],
            ':cap' => $d['caption'],
            ':cat' => $d['category'] ?? 'kitchen',
            ':mat' => $d['material'] ?? 'granite',
            ':so'  => (int)($d['sort_order'] ?? 0),
            ':st'  => $d['status'] ?? 'active',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $set = ['caption=:cap', 'category=:cat', 'material=:mat', 'sort_order=:so', 'status=:st'];
        $params = [
            ':cap' => $d['caption'],
            ':cat' => $d['category'],
            ':mat' => $d['material'],
            ':so'  => (int)$d['sort_order'],
            ':st'  => $d['status'],
            ':id'  => $id,
        ];
        if (!empty($d['image'])) {
            $set[] = 'image=:img';
            $params[':img'] = $d['image'];
        }
        $stmt = $this->pdo->prepare("UPDATE gallery_photos SET " . implode(',', $set) . " WHERE id=:id");
        $stmt->execute($params);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM gallery_photos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
