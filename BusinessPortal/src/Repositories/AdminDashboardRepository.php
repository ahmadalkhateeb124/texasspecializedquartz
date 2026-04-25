<?php

/**
 * AdminDashboardRepository — queries for the admin dashboard page.
 */
class AdminDashboardRepository
{
    public function __construct(private PDO $pdo) {}

    public function totalOrders(): int     { return $this->count("SELECT COUNT(*) FROM fabrication_orders"); }
    public function totalProducts(): int   { return $this->count("SELECT COUNT(*) FROM products"); }
    public function activeCustomers(): int { return $this->count("SELECT COUNT(*) FROM accounts WHERE status = 'Active'"); }

    public function monthlyOrders(): int
    {
        return $this->count("
            SELECT COUNT(*) FROM fabrication_orders
            WHERE MONTH(created_at)=MONTH(CURRENT_DATE())
              AND YEAR(created_at)=YEAR(CURRENT_DATE())
        ");
    }

    public function ordersLast7Days(): array
    {
        try {
            return $this->pdo->query("
                SELECT DATE(created_at) AS d, COUNT(*) AS n
                FROM fabrication_orders
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(created_at)
                ORDER BY d ASC
            ")->fetchAll();
        } catch (PDOException $e) { return []; }
    }

    public function productAvailabilityBreakdown(): array
    {
        try {
            $rows = $this->pdo->query("
                SELECT availability, COUNT(*) AS n
                FROM products
                GROUP BY availability
            ")->fetchAll();
        } catch (PDOException $e) { $rows = []; }

        $available = $reserved = $soldOut = 0;
        foreach ($rows as $r) {
            if ($r['availability'] === 'Available in store') $available = (int)$r['n'];
            elseif ($r['availability'] === 'Reserved')       $reserved  = (int)$r['n'];
            elseif ($r['availability'] === 'Sold Out')       $soldOut   = (int)$r['n'];
        }
        return ['rows' => $rows, 'available' => $available, 'reserved' => $reserved, 'sold_out' => $soldOut];
    }

    public function recentOrders(int $limit = 8): array
    {
        try {
            return $this->pdo->query("
                SELECT f.id, f.created_at, c.company_name, COUNT(j.id) AS jobs
                FROM fabrication_orders f
                LEFT JOIN accounts a ON a.id = f.account_id
                LEFT JOIN customers_companies c ON c.id = a.company_id
                LEFT JOIN job_sections j ON j.order_id = f.id
                GROUP BY f.id
                ORDER BY f.created_at DESC
                LIMIT " . (int)$limit
            )->fetchAll();
        } catch (PDOException $e) { return []; }
    }

    private function count(string $sql): int
    {
        try {
            return (int)($this->pdo->query($sql)->fetchColumn() ?: 0);
        } catch (PDOException $e) { return 0; }
    }
}
