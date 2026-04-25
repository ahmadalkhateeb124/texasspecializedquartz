<?php

/**
 * ProductRepository — data access for products table.
 */
class ProductRepository
{
    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        return $this->pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function availabilityCounts(array $products): array
    {
        $a = $r = $s = 0;
        foreach ($products as $p) {
            if ($p['availability'] === 'Available in store')   $a++;
            elseif ($p['availability'] === 'Reserved')         $r++;
            elseif ($p['availability'] === 'Sold Out')         $s++;
        }
        return ['available' => $a, 'reserved' => $r, 'sold_out' => $s];
    }

    public static function availabilityBadge(string $v): string
    {
        return match ($v) {
            'Available in store' => '<span class="badge badge-success"><span class="dot"></span>Available</span>',
            'Reserved'           => '<span class="badge badge-warning"><span class="dot"></span>Reserved</span>',
            'Sold Out'           => '<span class="badge badge-critical"><span class="dot"></span>Sold Out</span>',
            default              => '<span class="badge badge-neutral">' . htmlspecialchars($v) . '</span>',
        };
    }
}
