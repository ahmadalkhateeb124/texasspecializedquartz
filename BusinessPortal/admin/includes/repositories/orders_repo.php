<?php

/**
 * admin/includes/repositories/orders_repo.php
 * Data access for fabrication orders (admin).
 */

/**
 * Fetch all fabrication orders with their job sections, grouped by order.
 *
 * @return array{orders: array, error: ?string}
 */
function fetchOrdersGrouped(PDO $pdo): array
{
    try {
        $stmt = $pdo->query("
            SELECT
                f.id          AS order_id,
                f.created_at,
                f.updated_at,
                c.company_name,
                j.id          AS job_id,
                j.job_type,
                j.job_type_other,
                j.material_type,
                j.material_color,
                j.thickness,
                j.edge_profile,
                j.sink_provider,
                j.sink_type,
                j.tear_out
            FROM fabrication_orders f
            LEFT JOIN accounts a ON a.id = f.account_id
            LEFT JOIN customers_companies c ON c.id = a.company_id
            LEFT JOIN job_sections j ON j.order_id = f.id
            ORDER BY f.created_at DESC, j.id ASC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ['orders' => [], 'error' => htmlspecialchars($e->getMessage())];
    }

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
        if ($r['job_id']) {
            $orders[$oid]['jobs'][] = $r;
        }
    }

    return ['orders' => $orders, 'error' => null];
}

/**
 * Summary stats derived from the grouped orders array.
 */
function summarizeOrders(array $orders): array
{
    $thisMonth = array_filter(
        $orders,
        fn($o) => date('Y-m', strtotime($o['created_at'])) === date('Y-m')
    );
    $thisWeek = array_filter(
        $orders,
        fn($o) => strtotime($o['created_at']) >= strtotime('-7 days')
    );
    $totalJobs = array_sum(array_map(fn($o) => count($o['jobs']), $orders));

    return [
        'total'      => count($orders),
        'this_month' => count($thisMonth),
        'this_week'  => count($thisWeek),
        'total_jobs' => $totalJobs,
    ];
}

/**
 * Pretty-format material thickness for display.
 */
function formatThickness(?string $thickness, ?string $custom = null): string
{
    if ($thickness === '2cm') return '2 Cm';
    if ($thickness === '3cm') return '3 Cm';
    if ($thickness === 'custom' && !empty($custom)) return $custom . ' Cm';
    return (string)$thickness;
}
