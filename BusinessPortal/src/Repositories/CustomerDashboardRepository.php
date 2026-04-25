<?php

/**
 * CustomerDashboardRepository — queries for the customer dashboard page.
 */
class CustomerDashboardRepository
{
    public function __construct(private PDO $pdo) {}

    /** Company + account meta for a given account id. */
    public function accountMeta(int $accountId): array
    {
        $sql = "SELECT a.name, a.email, a.created_at,
                       c.company_name, c.phone, c.address
                FROM accounts a
                JOIN customers_companies c ON c.id = a.company_id
                WHERE a.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$accountId]);
        return $stmt->fetch() ?: [];
    }

    public function totalOrders(int $accountId): int
    {
        return $this->count(
            "SELECT COUNT(*) FROM fabrication_orders WHERE account_id = ?",
            [$accountId]
        );
    }

    public function monthlyOrders(int $accountId): int
    {
        return $this->count(
            "SELECT COUNT(*) FROM fabrication_orders
             WHERE account_id = ? AND MONTH(created_at)=MONTH(CURDATE())
                                AND YEAR(created_at)=YEAR(CURDATE())",
            [$accountId]
        );
    }

    public function weeklyOrders(int $accountId): int
    {
        return $this->count(
            "SELECT COUNT(*) FROM fabrication_orders
             WHERE account_id = ? AND created_at >= DATE_SUB(CURDATE(),INTERVAL 7 DAY)",
            [$accountId]
        );
    }

    /** 30-day per-day counts for the activity chart. */
    public function dailyCountsLast30(int $accountId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT DATE(created_at) AS d, COUNT(*) AS n
            FROM fabrication_orders
            WHERE account_id = ? AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY d ASC
        ");
        $stmt->execute([$accountId]);
        return $stmt->fetchAll();
    }

    public function recentOrders(int $accountId, int $limit = 5): array
    {
        $stmt = $this->pdo->prepare("
            SELECT f.id, f.created_at, f.updated_at, COUNT(j.id) AS jobs
            FROM fabrication_orders f
            LEFT JOIN job_sections j ON j.order_id = f.id
            WHERE f.account_id = ?
            GROUP BY f.id
            ORDER BY f.created_at DESC
            LIMIT " . (int)$limit
        );
        $stmt->execute([$accountId]);
        return $stmt->fetchAll();
    }

    private function count(string $sql, array $params): int
    {
        try {
            $s = $this->pdo->prepare($sql);
            $s->execute($params);
            return (int)($s->fetchColumn() ?: 0);
        } catch (PDOException $e) {
            return 0;
        }
    }
}
