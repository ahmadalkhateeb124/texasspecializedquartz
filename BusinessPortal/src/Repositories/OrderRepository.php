<?php

/**
 * OrderRepository — all DB access for fabrication_orders + job_sections.
 */
class OrderRepository
{
    public function __construct(private PDO $pdo) {}

    /* ───────────────────── Reads ───────────────────── */

    /** All orders with job sections, grouped by order. For admin list views. */
    public function allGrouped(): array
    {
        $stmt = $this->pdo->query("
            SELECT f.id AS order_id, f.created_at, f.updated_at,
                   c.company_name,
                   j.id AS job_id, j.job_type, j.job_type_other,
                   j.material_type, j.material_color, j.thickness, j.thickness_custom,
                   j.edge_profile, j.sink_provider, j.sink_type, j.tear_out
            FROM fabrication_orders f
            LEFT JOIN accounts a ON a.id = f.account_id
            LEFT JOIN customers_companies c ON c.id = a.company_id
            LEFT JOIN job_sections j ON j.order_id = f.id
            ORDER BY f.created_at DESC, j.id ASC
        ");
        return $this->groupOrderRows($stmt->fetchAll());
    }

    /** Orders for a specific account (customer view). */
    public function allForAccount(int $accountId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT f.id AS order_id, f.created_at, f.updated_at,
                   j.id AS job_id, j.job_type, j.job_type_other,
                   j.material_type, j.material_color, j.thickness, j.thickness_custom,
                   j.edge_profile, j.sink_provider, j.sink_type, j.tear_out
            FROM fabrication_orders f
            LEFT JOIN job_sections j ON j.order_id = f.id
            WHERE f.account_id = ?
            ORDER BY f.created_at DESC, j.id ASC
        ");
        $stmt->execute([$accountId]);
        return $this->groupOrderRows($stmt->fetchAll());
    }

    /** Full order with job sections by id. */
    public function findWithJobs(int $orderId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM fabrication_orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();
        if (!$order) return null;

        $js = $this->pdo->prepare("SELECT * FROM job_sections WHERE order_id = ? ORDER BY id ASC");
        $js->execute([$orderId]);
        $order['jobs'] = $js->fetchAll();

        return $order;
    }

    /* ───────────────────── Writes ───────────────────── */

    /**
     * Create an order and its job sections inside a transaction.
     *
     * @param array $orderData  ['user_id'|'account_id', customer_name, phone, address, sales_rep,
     *                           sales_rep_phone, city, zip_code, po_number, notes, image]
     * @param array $jobs       list of job section rows (see insertJobSection)
     * @return int              new order id
     */
    public function create(array $orderData, array $jobs = []): int
    {
        $this->pdo->beginTransaction();
        try {
            $orderId = $this->insertOrder($orderData);
            foreach ($jobs as $job) {
                $this->insertJobSection($orderId, $job);
            }
            $this->pdo->commit();
            return $orderId;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function delete(int $orderId): bool
    {
        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare("DELETE FROM job_sections WHERE order_id = ?")->execute([$orderId]);
            $this->pdo->prepare("DELETE FROM fabrication_orders WHERE id = ?")->execute([$orderId]);
            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /* ───────────────────── Helpers ───────────────────── */

    private function insertOrder(array $d): int
    {
        $owner = isset($d['user_id']) && $d['user_id']
            ? ['col' => 'user_id',    'val' => $d['user_id']]
            : ['col' => 'account_id', 'val' => $d['account_id'] ?? null];

        $sql = "INSERT INTO fabrication_orders
                ({$owner['col']}, customer_name, phone, address,
                 sales_rep, sales_rep_phone, city, zip_code, po_number, notes, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $owner['val'],
            $d['customer_name'] ?? '',
            $d['phone']         ?? '',
            $d['address']       ?? '',
            $d['sales_rep']     ?? '',
            $d['sales_rep_phone'] ?? '',
            $d['city']          ?? '',
            $d['zip_code']      ?? '',
            $d['po_number']     ?? '',
            $d['notes']         ?? '',
            $d['image']         ?? '',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    private function insertJobSection(int $orderId, array $j): void
    {
        $sql = "INSERT INTO job_sections
                (order_id, job_type, job_type_other, material_type, material_other,
                 thickness, thickness_custom, material_color,
                 sink_provider, sink_type, sink_style, sink_style_other,
                 edge_profile, edge_profile_custom, tear_out)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $orderId,
            $j['job_type'],
            $j['job_type_other'] ?: null,
            $j['material_type']  ?? '',
            $j['material_other'] ?? '',
            $j['thickness']        ?? '',
            $j['thickness_custom'] ?? '',
            $j['material_color']   ?? '',
            $j['sink_provider']    ?? '',
            $j['sink_type']        ?? '',
            $j['sink_style']       ?? '',
            $j['sink_style_other'] ?? '',
            $j['edge_profile']     ?? '',
            $j['edge_profile_custom'] ?? '',
            $j['tear_out']         ?? 'no',
        ]);
    }

    /** Collapses joined rows into [order => ['jobs' => [...]]]. */
    private function groupOrderRows(array $rows): array
    {
        $orders = [];
        foreach ($rows as $r) {
            $oid = $r['order_id'];
            if (!isset($orders[$oid])) {
                $orders[$oid] = [
                    'id'           => $oid,
                    'created_at'   => $r['created_at'],
                    'updated_at'   => $r['updated_at'],
                    'company_name' => $r['company_name'] ?? '—',
                    'jobs'         => [],
                ];
            }
            if (!empty($r['job_id'])) {
                $orders[$oid]['jobs'][] = $r;
            }
        }
        return $orders;
    }
}
