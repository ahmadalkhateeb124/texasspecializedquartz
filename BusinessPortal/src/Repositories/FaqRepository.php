<?php

/**
 * FaqRepository — manages faq_items.
 */
class FaqRepository
{
    public function __construct(private PDO $pdo) {}

    public function all(bool $publishedOnly = false): array
    {
        $sql = "SELECT * FROM faq_items";
        if ($publishedOnly) $sql .= " WHERE status = 'published'";
        $sql .= " ORDER BY sort_order ASC, id ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM faq_items WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $d): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO faq_items (question, answer, category, sort_order, status)
            VALUES (:q, :a, :c, :so, :st)
        ");
        $stmt->execute([
            ':q'  => $d['question'],
            ':a'  => $d['answer'],
            ':c'  => $d['category'] ?? null,
            ':so' => (int)($d['sort_order'] ?? 0),
            ':st' => $d['status'] ?? 'published',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE faq_items
            SET question = :q, answer = :a, category = :c, sort_order = :so, status = :st
            WHERE id = :id
        ");
        $stmt->execute([
            ':q'  => $d['question'],
            ':a'  => $d['answer'],
            ':c'  => $d['category'] ?? null,
            ':so' => (int)($d['sort_order'] ?? 0),
            ':st' => $d['status'] ?? 'published',
            ':id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM faq_items WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
