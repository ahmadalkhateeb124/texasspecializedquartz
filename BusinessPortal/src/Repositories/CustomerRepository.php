<?php

/**
 * CustomerRepository — data access for customers_companies and their linked accounts.
 */
class CustomerRepository
{
    public function __construct(private PDO $pdo) {}

    /** All customers with account status, for the admin list page. */
    public function allWithAccountStatus(): array
    {
        $stmt = $this->pdo->query("
            SELECT cc.*,
                   a.status     AS account_status,
                   a.created_at AS account_created
            FROM customers_companies cc
            LEFT JOIN accounts a ON cc.id = a.company_id
            ORDER BY cc.id DESC
        ");
        return $stmt->fetchAll();
    }

    /** Single customer by id. */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT cc.*, a.id AS account_id, a.name AS account_name,
                   a.email AS account_email, a.status AS account_status,
                   a.created_at AS account_created
            FROM customers_companies cc
            LEFT JOIN accounts a ON cc.id = a.company_id
            WHERE cc.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Count customers grouped by account status. */
    public function statusCounts(array $customers): array
    {
        $active = $inactive = $blacklisted = 0;
        foreach ($customers as $c) {
            $s = $c['account_status'] ?? 'Inactive';
            if      ($s === 'Active')      $active++;
            elseif  ($s === 'Blacklisted') $blacklisted++;
            else                           $inactive++;
        }
        return ['active' => $active, 'inactive' => $inactive, 'blacklisted' => $blacklisted];
    }
}
