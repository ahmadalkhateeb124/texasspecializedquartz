<?php

/**
 * AdminUserRepository — data access for admin users (users table).
 */
class AdminUserRepository
{
    public function __construct(private PDO $pdo) {}

    public function find(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT id, fullname, username, email, created_at, avatar FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: [];
    }

    public function emailTakenByOther(string $email, int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        return (bool)$stmt->fetch();
    }

    public function currentPasswordHash(int $id): ?string
    {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row['password'] : null;
    }

    public function updatePassword(int $id, string $hash): void
    {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $id]);
    }

    public function updateProfile(int $id, string $fullname, string $email, ?string $avatar = null): void
    {
        if ($avatar !== null) {
            $stmt = $this->pdo->prepare("UPDATE users SET fullname = ?, email = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$fullname, $email, $avatar, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
            $stmt->execute([$fullname, $email, $id]);
        }
    }

    public function currentAvatar(int $id): ?string
    {
        $stmt = $this->pdo->prepare("SELECT avatar FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?: null;
    }
}
