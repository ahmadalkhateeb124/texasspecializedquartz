<?php

/**
 * SignoffRepository — the digital "installation completion sign-off" record
 * for an order (one per order, created once by the assigned employee).
 */
class SignoffRepository
{
    /** The fixed checklist items shown on the paper sign-off form, in order. */
    public const CHECKLIST_ITEMS = [
        'surface_cleaned'  => 'Surface cleaned and sealed',
        'joints_smooth'    => 'Joints are acceptable and smooth',
        'cabinetry_clean'  => 'Clean cabinetry and drawers',
        'caulking_applied' => 'Caulking has been applied',
        'faucet_placement' => 'Faucet placement is correct',
        'cooktop_cutout'   => 'Cooktop cutout, if dimensions were provided by customer, customer is responsible for cooktop dimensions',
        'dishwasher'       => 'Dishwasher secured',
        'silicon_sink'     => 'Silicon applied around sink',
        'plumbing_valves'  => 'Plumbing valves shut off',
        'work_area_clean'  => 'Work area is clean',
    ];

    public function __construct(private PDO $pdo) {}

    public function findByOrder(int $orderId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM installation_signoffs WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $row['worked_area'] = json_decode($row['worked_area'], true) ?: [];
        $row['checklist']   = json_decode($row['checklist'], true) ?: [];
        return $row;
    }

    public function exists(int $orderId): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM installation_signoffs WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * @param array $d ['order_id','signed_by_employee_id','customer_name','customer_address',
     *                  'worked_area' => assoc array, 'checklist' => assoc array, 'signature_text', 'pdf_filename']
     */
    public function create(array $d): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO installation_signoffs
                (order_id, signed_by_employee_id, customer_name, customer_address,
                 worked_area, checklist, signature_text, pdf_filename)
            VALUES (:order_id, :employee_id, :customer_name, :customer_address,
                    :worked_area, :checklist, :signature_text, :pdf_filename)
        ");
        $stmt->execute([
            ':order_id'        => $d['order_id'],
            ':employee_id'     => $d['signed_by_employee_id'] ?? null,
            ':customer_name'   => $d['customer_name'],
            ':customer_address' => $d['customer_address'],
            ':worked_area'     => json_encode($d['worked_area'], JSON_UNESCAPED_UNICODE),
            ':checklist'       => json_encode($d['checklist'], JSON_UNESCAPED_UNICODE),
            ':signature_text'  => $d['signature_text'],
            ':pdf_filename'    => $d['pdf_filename'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * "AHMAD KHALED ALKHATEEB" -> "AHMAD K. A." — first name in full caps,
     * then just the first letter of every remaining name part.
     */
    public static function generateSignatureText(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];
        $parts = array_values(array_filter($parts, fn($p) => $p !== ''));
        if (empty($parts)) {
            return '';
        }

        $first    = mb_strtoupper(array_shift($parts));
        $initials = array_map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)) . '.', $parts);

        return trim($first . ' ' . implode(' ', $initials));
    }
}
