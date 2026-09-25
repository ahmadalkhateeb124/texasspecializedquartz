<?php

/**
 * EmployeeRepository — employee accounts (users.role = 'employee') +
 * fabrication_orders.assigned_employee_id (one employee per order, responsible
 * for every job section within that order).
 */
class EmployeeRepository
{
    public function __construct(private PDO $pdo) {}

    /* ───────────────────── Employee CRUD ───────────────────── */

    public function allWithAssignmentCounts(): array
    {
        $stmt = $this->pdo->query("
            SELECT u.*, COUNT(js.id) AS section_count
            FROM users u
            LEFT JOIN fabrication_orders fo ON fo.assigned_employee_id = u.id
            LEFT JOIN job_sections js       ON js.order_id = fo.id
            WHERE u.role = 'employee'
            GROUP BY u.id
            ORDER BY u.id DESC
        ");
        return $stmt->fetchAll();
    }

    /** All employees (id/fullname only) — for assignment checklists. */
    public function allActive(): array
    {
        $stmt = $this->pdo->query("
            SELECT id, fullname, email, designation
            FROM users
            WHERE role = 'employee' AND status = 'Active'
            ORDER BY fullname ASC
        ");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'employee'");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (fullname, email, phone, username, designation, password, role, status)
            VALUES (:fullname, :email, :phone, :username, :designation, :password, 'employee', 'Active')
        ");
        $stmt->execute([
            ':fullname'    => $d['fullname'],
            ':email'       => $d['email'],
            ':phone'       => $d['phone'] ?? null,
            ':username'    => $d['username'],
            ':designation' => $d['designation'] ?? null,
            ':password'    => password_hash($d['password'], PASSWORD_DEFAULT),
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET
                fullname    = :fullname,
                email       = :email,
                phone       = :phone,
                username    = :username,
                designation = :designation,
                status      = :status
            WHERE id = :id AND role = 'employee'
        ");
        $stmt->execute([
            ':fullname'    => $d['fullname'],
            ':email'       => $d['email'],
            ':phone'       => $d['phone'] ?? null,
            ':username'    => $d['username'],
            ':designation' => $d['designation'] ?? null,
            ':status'      => $d['status'] ?? 'Active',
            ':id'          => $id,
        ]);
    }

    public function updatePassword(int $id, string $hash): void
    {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ? AND role = 'employee'");
        $stmt->execute([$hash, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'employee'");
        $stmt->execute([$id]);
    }

    public function emailTakenByOther(string $email, int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        return (bool)$stmt->fetchColumn();
    }

    public function usernameTakenByOther(string $username, int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $id]);
        return (bool)$stmt->fetchColumn();
    }

    /* ───────────────── fabrication_orders.assigned_employee_id ───────────────── */

    /** The single employee assigned to an order, or null if unassigned. */
    public function employeeForOrder(int $orderId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.fullname, u.email, u.phone, u.designation
            FROM fabrication_orders fo
            JOIN users u ON u.id = fo.assigned_employee_id
            WHERE fo.id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetch() ?: null;
    }

    /** Job sections (+ parent order/customer context) for every order assigned to one employee. */
    public function sectionsForEmployee(int $employeeId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT js.*, fo.id AS order_id, fo.customer_name, fo.address, fo.city,
                   fo.admin_status, c.company_name
            FROM fabrication_orders fo
            JOIN job_sections js             ON js.order_id = fo.id
            LEFT JOIN accounts a             ON a.id = fo.account_id
            LEFT JOIN customers_companies c  ON c.id = a.company_id
            WHERE fo.assigned_employee_id = ?
            ORDER BY fo.created_at DESC
        ");
        $stmt->execute([$employeeId]);
        return $stmt->fetchAll();
    }

    /** Assign (or unassign, with null) the single employee responsible for an order. */
    public function assignEmployee(int $orderId, ?int $employeeId): void
    {
        $stmt = $this->pdo->prepare("UPDATE fabrication_orders SET assigned_employee_id = ? WHERE id = ?");
        $stmt->execute([$employeeId ?: null, $orderId]);
    }

    /** Whether an employee is responsible for the order that owns a given job section. */
    public function ownsSection(int $employeeId, int $jobSectionId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT 1
            FROM job_sections js
            JOIN fabrication_orders fo ON fo.id = js.order_id
            WHERE js.id = ? AND fo.assigned_employee_id = ?
        ");
        $stmt->execute([$jobSectionId, $employeeId]);
        return (bool)$stmt->fetchColumn();
    }
}
