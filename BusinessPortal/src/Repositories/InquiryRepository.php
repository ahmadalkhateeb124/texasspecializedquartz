<?php

/**
 * InquiryRepository — contact form submissions.
 */
class InquiryRepository
{
    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        return $this->pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM inquiries WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function markRead(int $id, bool $read = true): void
    {
        $stmt = $this->pdo->prepare("UPDATE inquiries SET is_read = :r WHERE id = :id");
        $stmt->execute([':r' => $read ? 1 : 0, ':id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM inquiries WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function unreadCount(): int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM inquiries WHERE is_read = 0")->fetchColumn();
    }
}
