<?php

/**
 * JobScheduleRepository — DB access for job_schedules.
 */
class JobScheduleRepository
{
    public const EVENT_TYPES = [
        'inspection' => 'Inspection',
        'template'   => 'Template',
        'start'      => 'Start Work',
        'install'    => 'Install',
        'complete'   => 'Complete',
        'follow_up'  => 'Follow-up',
    ];

    public const STATUSES = [
        'pending'     => 'Pending',
        'in_progress' => 'In Progress',
        'completed'   => 'Completed',
        'cancelled'   => 'Cancelled',
    ];

    public function __construct(private PDO $pdo) {}

    public function forOrder(int $orderId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT s.*, j.job_type, j.job_type_other
            FROM job_schedules s
            LEFT JOIN job_sections j ON j.id = s.job_section_id
            WHERE s.order_id = :oid
            ORDER BY s.scheduled_date ASC
        ");
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll();
    }

    public function forSection(int $jobSectionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT s.*, j.job_type, j.job_type_other
            FROM job_schedules s
            LEFT JOIN job_sections j ON j.id = s.job_section_id
            WHERE s.job_section_id = :jid
            ORDER BY s.scheduled_date ASC
        ");
        $stmt->execute([':jid' => $jobSectionId]);
        return $stmt->fetchAll();
    }

    public function allForCalendar(): array
    {
        $stmt = $this->pdo->query("
            SELECT s.id, s.order_id, s.job_section_id, s.event_type,
                   s.scheduled_date, s.status, s.notes,
                   j.job_type, j.job_type_other,
                   c.company_name
            FROM job_schedules s
            LEFT JOIN job_sections j      ON j.id = s.job_section_id
            LEFT JOIN fabrication_orders f ON f.id = s.order_id
            LEFT JOIN accounts a           ON a.id = f.account_id
            LEFT JOIN customers_companies c ON c.id = a.company_id
            ORDER BY s.scheduled_date ASC
        ");
        return $stmt->fetchAll();
    }

    /** All job_schedules for orders assigned to one employee — same row shape as allForCalendar(). */
    public function forEmployee(int $employeeId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT s.id, s.order_id, s.job_section_id, s.event_type,
                   s.scheduled_date, s.status, s.notes,
                   j.job_type, j.job_type_other,
                   f.customer_name, f.phone AS order_phone, f.address, f.city,
                   c.company_name
            FROM job_schedules s
            JOIN job_sections j             ON j.id = s.job_section_id
            JOIN fabrication_orders f        ON f.id = s.order_id AND f.assigned_employee_id = :eid
            LEFT JOIN accounts a            ON a.id = f.account_id
            LEFT JOIN customers_companies c ON c.id = a.company_id
            ORDER BY s.scheduled_date ASC
        ");
        $stmt->execute([':eid' => $employeeId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM job_schedules WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO job_schedules
                (order_id, job_section_id, event_type, scheduled_date, status, notes)
            VALUES (:oid, :jid, :etype, :date, :status, :notes)
        ");
        $stmt->execute([
            ':oid'    => $data['order_id'],
            ':jid'    => $data['job_section_id'],
            ':etype'  => $data['event_type'],
            ':date'   => $data['scheduled_date'],
            ':status' => $data['status'] ?? 'pending',
            ':notes'  => $data['notes'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE job_schedules
            SET event_type     = :etype,
                scheduled_date = :date,
                status         = :status,
                notes          = :notes
            WHERE id = :id
        ");
        $stmt->execute([
            ':etype'  => $data['event_type'],
            ':date'   => $data['scheduled_date'],
            ':status' => $data['status'],
            ':notes'  => $data['notes'] ?? null,
            ':id'     => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM job_schedules WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function setOrderStatus(int $orderId, string $status): void
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE fabrication_orders SET admin_status = :s WHERE id = :id");
            $stmt->execute([':s' => $status, ':id' => $orderId]);
        } catch (PDOException $e) {
            /* `admin_status` column missing — user hasn't run the migration yet.
               Throw a friendlier message so the UI shows what to fix. */
            if (str_contains($e->getMessage(), 'admin_status')) {
                throw new RuntimeException(
                    'Database schema is out of date — run migrations/2026-04-25_add_admin_status_to_fabrication_orders.sql in phpMyAdmin first.'
                );
            }
            throw $e;
        }
    }

    public function orderStatus(int $orderId): string
    {
        try {
            $stmt = $this->pdo->prepare("SELECT admin_status FROM fabrication_orders WHERE id = :id");
            $stmt->execute([':id' => $orderId]);
            return (string)($stmt->fetchColumn() ?: 'new');
        } catch (PDOException $e) {
            /* Column missing on legacy DBs — fall back to default
               so the page still renders. Run the migration to enable
               status tracking properly. */
            return 'new';
        }
    }

    public static function eventColor(string $type): string
    {
        return [
            'inspection' => '#3b82f6',
            'template'   => '#8b5cf6',
            'start'      => '#f59e0b',
            'install'    => '#10b981',
            'complete'   => '#059669',
            'follow_up'  => '#6b7280',
        ][$type] ?? '#6b7280';
    }

    public static function statusBadge(string $status): string
    {
        $map = [
            'pending'     => ['#fef3c7', '#92400e', 'Pending'],
            'in_progress' => ['#dbeafe', '#1e40af', 'In Progress'],
            'completed'   => ['#d1fae5', '#065f46', 'Completed'],
            'cancelled'   => ['#fee2e2', '#991b1b', 'Cancelled'],
        ];
        [$bg, $fg, $label] = $map[$status] ?? ['#f3f4f6', '#374151', ucfirst($status)];
        return "<span class='badge' style='background:{$bg};color:{$fg};'>{$label}</span>";
    }
}
