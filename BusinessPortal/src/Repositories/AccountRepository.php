<?php

/**
 * AccountRepository — data access for customer accounts + company info.
 */
class AccountRepository
{
    public function __construct(private PDO $pdo) {}

    /** Full profile (account + company) for a given account id. */
    public function findProfile(int $accountId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.id, a.name, a.email, a.status, a.created_at,
                   c.id         AS company_id,
                   c.company_name, c.phone, c.address, c.city, c.state, c.zip_code
            FROM accounts a
            JOIN customers_companies c ON c.id = a.company_id
            WHERE a.id = ?
        ");
        $stmt->execute([$accountId]);
        return $stmt->fetch() ?: [];
    }

    public function orderCount(int $accountId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM fabrication_orders WHERE account_id = ?");
        $stmt->execute([$accountId]);
        return (int)$stmt->fetchColumn();
    }

    public function emailTakenByOther(string $email, int $accountId): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM accounts WHERE email = ? AND id != ?");
        $stmt->execute([$email, $accountId]);
        return (bool)$stmt->fetch();
    }

    public function updateProfile(int $accountId, string $name, string $email): void
    {
        $stmt = $this->pdo->prepare("UPDATE accounts SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $accountId]);
    }

    public function currentPasswordHash(int $accountId): ?string
    {
        $stmt = $this->pdo->prepare("SELECT password FROM accounts WHERE id = ?");
        $stmt->execute([$accountId]);
        $row = $stmt->fetch();
        return $row ? $row['password'] : null;
    }

    public function updatePassword(int $accountId, string $hash): void
    {
        $stmt = $this->pdo->prepare("UPDATE accounts SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $accountId]);
    }
}
