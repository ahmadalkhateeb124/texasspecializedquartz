<?php
/**
* Price List Management Database Connection & PriceListManager Class
*/
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

class PriceListManager
{
    private $pdo;
    private $uploadsDir;
    private $maxFileSize = 50000000; // 50MB
    private $allowedFormats = ['pdf', 'xls', 'xlsx', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'];
    
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        
        // تحديد المسار بناءً على موقع الموقع
        $this->uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/auth/uploads/pricelist/';
        
        // Debug: log the uploads directory
        error_log("PriceListManager initialized. Uploads directory: " . $this->uploadsDir);
        error_log("Document root: " . $_SERVER['DOCUMENT_ROOT']);
        
        // Ensure the uploads directory exists
        if (!is_dir($this->uploadsDir)) {
            @mkdir($this->uploadsDir, 0777, true); // Suppress error if creation fails
        }
        
        // Check if directory is writable; don't throw error if chmod fails
        if (!is_writable($this->uploadsDir)) {
            @chmod($this->uploadsDir, 0777); // Suppress errors
        }
    }
    
    /**
     * Upload a new price list file
     */
    public function uploadPriceList($file, $fileName, $assignedAccounts = [])
    {
        // Check for PHP upload errors
        if (isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    throw new Exception('File size exceeds the maximum allowed by PHP configuration (upload_max_filesize).');
                case UPLOAD_ERR_FORM_SIZE:
                    throw new Exception('File size exceeds the maximum allowed by the form.');
                case UPLOAD_ERR_PARTIAL:
                    throw new Exception('File was only partially uploaded.');
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception('No file was uploaded.');
                case UPLOAD_ERR_NO_TMP_DIR:
                    throw new Exception('Missing temporary upload directory.');
                case UPLOAD_ERR_CANT_WRITE:
                    throw new Exception('Failed to write file to disk.');
                case UPLOAD_ERR_EXTENSION:
                    throw new Exception('File upload stopped by PHP extension.');
                default:
                    throw new Exception('Unknown upload error occurred.');
            }
        }
        
        // Validate file
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            throw new Exception('No file uploaded. Please select a file to upload.');
        }
        
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('File size exceeds maximum allowed size of 50MB.');
        }
        
        // Get file extension
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension']);
        if (!in_array($extension, $this->allowedFormats)) {
            throw new Exception('File format not allowed. Supported formats: ' . implode(', ', $this->allowedFormats));
        }
        
        // Generate unique filename
        $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;
        $filePath = $this->uploadsDir . $uniqueFileName;
        
        // Check if directory is writable before attempting upload
        if (!is_writable($this->uploadsDir)) {
            throw new Exception('Upload directory is not writable: ' . $this->uploadsDir . '. Please check permissions.');
        }
        
        // Check if temp file exists
        if (!file_exists($file['tmp_name'])) {
            throw new Exception('Uploaded file was not found in temporary location.');
        }
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            // Get more specific error information
            $error = error_get_last();
            $errorMsg = isset($error['message']) ? $error['message'] : 'Unknown error';
            throw new Exception('Failed to move uploaded file to destination. Error: ' . $errorMsg . '. Check server permissions and disk space.');
        }
        
        // Get admin user ID
        $adminId = $_SESSION['user_id'] ?? 1;
        
        // Insert into database
        $stmt = $this->pdo->prepare('
            INSERT INTO price_lists (file_name, file_path, file_size, file_type, created_by)
            VALUES (:file_name, :file_path, :file_size, :file_type, :created_by)
        ');
        $stmt->execute([
            ':file_name' => $fileName,
            ':file_path' => $uniqueFileName,
            ':file_size' => $file['size'],
            ':file_type' => $extension,
            ':created_by' => $adminId
        ]);
        
        $priceListId = $this->pdo->lastInsertId();
        
        // Assign to accounts
        if (!empty($assignedAccounts)) {
            $this->assignAccountsToList($priceListId, $assignedAccounts);
        }
        
        return $priceListId;
    }
    
    /**
     * Assign accounts to a price list
     */
    public function assignAccountsToList($priceListId, $accountIds)
    {
        // First, remove existing assignments
        $stmt = $this->pdo->prepare('DELETE FROM price_list_accounts WHERE price_list_id = :price_list_id');
        $stmt->execute([':price_list_id' => $priceListId]);
        
        // Then add new assignments
        $stmt = $this->pdo->prepare('
            INSERT INTO price_list_accounts (price_list_id, account_id)
            VALUES (:price_list_id, :account_id)
        ');
        
        foreach ($accountIds as $accountId) {
            try {
                $stmt->execute([
                    ':price_list_id' => $priceListId,
                    ':account_id' => (int)$accountId
                ]);
            } catch (PDOException $e) {
                // Skip duplicate assignments
            }
        }
        
        return true;
    }
    
    /**
     * Get all price lists (admin view)
     */
    public function getAllPriceLists()
    {
        $stmt = $this->pdo->query('
            SELECT pl.*, COUNT(pla.id) as account_count
            FROM price_lists pl
            LEFT JOIN price_list_accounts pla ON pl.id = pla.price_list_id
            GROUP BY pl.id
            ORDER BY pl.created_at DESC
        ');
        return $stmt->fetchAll();
    }
    
    /**
     * Get price list by ID
     */
    public function getPriceListById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM price_lists WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get assigned accounts for a price list
     */
    public function getAssignedAccounts($priceListId)
    {
        $stmt = $this->pdo->prepare('
            SELECT a.id, a.name, a.email
            FROM accounts a
            JOIN price_list_accounts pla ON a.id = pla.account_id
            WHERE pla.price_list_id = :price_list_id
            ORDER BY a.name
        ');
        $stmt->execute([':price_list_id' => $priceListId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get price lists for a customer (only assigned ones)
     */
    public function getPriceListsForAccount($accountId)
    {
        $stmt = $this->pdo->prepare('
            SELECT pl.*
            FROM price_lists pl
            JOIN price_list_accounts pla ON pl.id = pla.price_list_id
            WHERE pla.account_id = :account_id
            ORDER BY pl.created_at DESC
        ');
        $stmt->execute([':account_id' => $accountId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all active accounts (for assignment dropdown)
     */
    public function getActiveAccounts()
    {
        $stmt = $this->pdo->query('
            SELECT id, name, email
            FROM accounts
            WHERE status = "Active"
            ORDER BY name
        ');
        return $stmt->fetchAll();
    }
    
    /**
     * Get the uploads directory path
     */
    public function getUploadsDir(): string
    {
        return $this->uploadsDir;
    }
    
    /**
     * Delete price list
     */
    public function deletePriceList($id)
    {
        $priceList = $this->getPriceListById($id);
        if (!$priceList) {
            throw new Exception('Price list not found');
        }
        
        // Delete file from server
        $filePath = $this->uploadsDir . $priceList['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete from database (cascade will handle price_list_accounts)
        $stmt = $this->pdo->prepare('DELETE FROM price_lists WHERE id = :id');
        $stmt->execute([':id' => $id]);
        
        return true;
    }
    
    /**
     * Update price list (optionally replace file)
     */
    public function updatePriceList($id, $file, $fileName, $assignedAccounts = [])
    {
        $priceList = $this->getPriceListById($id);
        if (!$priceList) {
            throw new Exception('Price list not found');
        }
        
        $hasNewFile = isset($file) && isset($file['tmp_name']) && !empty($file['tmp_name']) && $file['error'] === UPLOAD_ERR_OK;
        
        if ($hasNewFile) {
            if ($file['size'] > $this->maxFileSize) {
                throw new Exception('File size exceeds maximum allowed size of 50MB.');
            }
            
            $fileInfo = pathinfo($file['name']);
            $extension = strtolower($fileInfo['extension']);
            if (!in_array($extension, $this->allowedFormats)) {
                throw new Exception('File format not allowed. Supported formats: ' . implode(', ', $this->allowedFormats));
            }
            
            if (!is_writable($this->uploadsDir)) {
                throw new Exception('Upload directory is not writable. Please check permissions.');
            }
            
            // Delete old file
            $oldFilePath = $this->uploadsDir . $priceList['file_path'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
            
            // Generate new filename and move
            $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;
            $newFilePath = $this->uploadsDir . $uniqueFileName;
            
            if (!move_uploaded_file($file['tmp_name'], $newFilePath)) {
                $error = error_get_last();
                $errorMsg = isset($error['message']) ? $error['message'] : 'Unknown error';
                throw new Exception('Failed to move uploaded file. Error: ' . $errorMsg);
            }
            
            // Update database with new file
            $stmt = $this->pdo->prepare('
                UPDATE price_lists
                SET file_name = :file_name, file_path = :file_path, file_size = :file_size, file_type = :file_type
                WHERE id = :id
            ');
            $stmt->execute([
                ':file_name' => $fileName,
                ':file_path' => $uniqueFileName,
                ':file_size' => $file['size'],
                ':file_type' => $extension,
                ':id' => $id
            ]);
        } else {
            // Update name only (no new file)
            $stmt = $this->pdo->prepare('UPDATE price_lists SET file_name = :file_name WHERE id = :id');
            $stmt->execute([':file_name' => $fileName, ':id' => $id]);
        }
        
        // Always update account assignments
        $this->assignAccountsToList($id, $assignedAccounts);
        
        return true;
    }
    
    /**
     * Get file URL - FIXED VERSION FOR BOTH ADMIN AND CUSTOMER
     */
    public function getFileUrl($fileName)
    {
        // Debug
        error_log("PriceListManager::getFileUrl called for: " . $fileName);
        
        // Get current script location to determine if we're in admin or customer area
        $currentScript = $_SERVER['SCRIPT_NAME'] ?? '';
        $isAdminPanel = strpos($currentScript, '/admin/') !== false;
        $isCustomerPanel = strpos($currentScript, '/customer/') !== false;
        
        error_log("Current script: " . $currentScript);
        error_log("Is admin panel: " . ($isAdminPanel ? 'YES' : 'NO'));
        error_log("Is customer panel: " . ($isCustomerPanel ? 'YES' : 'NO'));
        
        // First check if file exists
        $filePath = $this->uploadsDir . $fileName;
        
        if (!file_exists($filePath)) {
            // Try alternative locations
            $altPaths = [
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/pricelist/' . $fileName,
                $_SERVER['DOCUMENT_ROOT'] . '/auth/uploads/pricelist/' . $fileName,
                dirname(__DIR__, 2) . '/auth/uploads/pricelist/' . $fileName,
                dirname(__DIR__, 3) . '/auth/uploads/pricelist/' . $fileName
            ];
            
            foreach ($altPaths as $altPath) {
                error_log("Checking alternative path: " . $altPath);
                if (file_exists($altPath)) {
                    // Extract web path from absolute path
                    $webPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $altPath);
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
                    $host = $_SERVER['HTTP_HOST'];
                    $url = $protocol . $host . $webPath;
                    
                    error_log("Found file in alternative path. URL: " . $url);
                    return $url;
                }
            }
            
            // File not found anywhere
            error_log("File not found in any location: " . $fileName);
        } else {
            error_log("File found in primary location: " . $filePath);
        }
        
        // File exists or not found, generate URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        
        // Use different paths based on panel type
        if ($isAdminPanel) {
            // Admin panel needs to go up one level
            $url = $protocol . $host . '/auth/uploads/pricelist/' . $fileName;
        } elseif ($isCustomerPanel) {
            // Customer panel might need different path
            $url = $protocol . $host . '/auth/uploads/pricelist/' . $fileName;
        } else {
            // Default
            $url = $protocol . $host . '/auth/uploads/pricelist/' . $fileName;
        }
        
        error_log("Returning URL: " . $url);
        
        return $url;
    }
    
    /**
     * Get direct download link with proper handling
     */
    public function getDownloadLink($fileName, $displayName = null, $attributes = [])
    {
        $fileUrl = $this->getFileUrl($fileName);
        $displayName = $displayName ?: $fileName;
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // أيقونات حسب نوع الملف
        $fileIcons = [
            'pdf' => 'bx-file',
            'xls' => 'bx-spreadsheet',
            'xlsx' => 'bx-spreadsheet',
            'doc' => 'bx-file',
            'docx' => 'bx-file',
            'jpg' => 'bx-image',
            'jpeg' => 'bx-image',
            'png' => 'bx-image',
            'gif' => 'bx-image'
        ];
        
        $icon = $fileIcons[$fileExtension] ?? 'bx-file';
        
        // بناء السمات الإضافية
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }
        
        // إنشاء رابط التحميل
        return '<a href="' . htmlspecialchars($fileUrl) . '" 
                    download="' . htmlspecialchars($displayName . '.' . $fileExtension) . '"
                    class="download-link btn btn-primary btn-sm"
                    onclick="return handleFileDownload(this)"
                    ' . $attrString . '>
                    <i class="bx ' . $icon . ' me-1"></i> ' . htmlspecialchars($displayName) . '
                </a>';
    }
    
    /**
     * Check if file exists on server
     */
    public function fileExists($fileName)
    {
        $filePath = $this->uploadsDir . $fileName;
        
        // تحقق أيضاً في المسار البديل
        if (!file_exists($filePath)) {
            $altPaths = [
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/pricelist/' . $fileName,
                $_SERVER['DOCUMENT_ROOT'] . '/auth/uploads/pricelist/' . $fileName,
                dirname(__DIR__, 2) . '/auth/uploads/pricelist/' . $fileName,
                dirname(__DIR__, 3) . '/auth/uploads/pricelist/' . $fileName
            ];
            
            foreach ($altPaths as $altPath) {
                if (file_exists($altPath)) {
                    return true;
                }
            }
            return false;
        }
        
        return true;
    }
    
    /**
     * Get absolute file path
     */
    public function getFilePath($fileName)
    {
        $filePath = $this->uploadsDir . $fileName;
        
  if (!file_exists($filePath)) {
    $altPaths = [
        $_SERVER['DOCUMENT_ROOT'] . '/uploads/pricelist/' . $fileName,
        $_SERVER['DOCUMENT_ROOT'] . '/auth/uploads/pricelist/' . $fileName,
        // استخدام المسارات المطلقة بدلاً من النسبية
        dirname(__FILE__, 2) . '/auth/uploads/pricelist/' . $fileName,
        dirname(__FILE__, 3) . '/auth/uploads/pricelist/' . $fileName,
        // مسارات إضافية للتحقق
        '/var/www/html/auth/uploads/pricelist/' . $fileName,
        '/home/*/public_html/auth/uploads/pricelist/' . $fileName
    ];
    
    foreach ($altPaths as $altPath) {
        // التعامل مع المسارات التي تحتوي على wildcard
        if (strpos($altPath, '*') !== false) {
            $globPaths = glob($altPath);
            if (!empty($globPaths) && file_exists($globPaths[0])) {
                return $globPaths[0];
            }
        } elseif (file_exists($altPath)) {
            return $altPath;
        }
    }
    
    // إذا لم يتم العثور على الملف في أي مكان
    return $filePath; // ارجع المسار الأساسي على أي حال
}
        
        return $filePath;
    }
    
    /**
     * Format file size for display
     */
    public static function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Get file icon class based on extension
     */
    public static function getFileIcon($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $icons = [
            'pdf' => 'bx-file',
            'xls' => 'bx-spreadsheet',
            'xlsx' => 'bx-spreadsheet',
            'doc' => 'bx-file',
            'docx' => 'bx-file',
            'jpg' => 'bx-image',
            'jpeg' => 'bx-image',
            'png' => 'bx-image',
            'gif' => 'bx-image'
        ];
        
        return $icons[$extension] ?? 'bx-file';
    }
    
    /**
     * Get file type description
     */
    public static function getFileTypeDescription($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $descriptions = [
            'pdf' => 'PDF Document',
            'xls' => 'Excel Spreadsheet',
            'xlsx' => 'Excel Spreadsheet',
            'doc' => 'Word Document',
            'docx' => 'Word Document',
            'jpg' => 'JPEG Image',
            'jpeg' => 'JPEG Image',
            'png' => 'PNG Image',
            'gif' => 'GIF Image'
        ];
        
        return $descriptions[$extension] ?? 'File';
    }
    
    /**
     * Debug function to check file access
     */
    public function debugFileAccess($fileName): array
    {
        $result = [
            'filename' => $fileName,
            'primary_path' => $this->uploadsDir . $fileName,
            'primary_exists' => file_exists($this->uploadsDir . $fileName),
            'alternative_paths' => [],
            'generated_url' => $this->getFileUrl($fileName),
            'server_info' => [
                'document_root' => $_SERVER['DOCUMENT_ROOT'],
                'https' => isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off',
                'host' => $_SERVER['HTTP_HOST'],
                'script_name' => $_SERVER['SCRIPT_NAME'] ?? ''
            ]
        ];
        
        // Check alternative paths
        $altPaths = [
            $_SERVER['DOCUMENT_ROOT'] . '/uploads/pricelist/' . $fileName,
            $_SERVER['DOCUMENT_ROOT'] . '/auth/uploads/pricelist/' . $fileName,
            dirname(__DIR__, 2) . '/auth/uploads/pricelist/' . $fileName,
            dirname(__DIR__, 3) . '/auth/uploads/pricelist/' . $fileName
        ];
        
        foreach ($altPaths as $path) {
            $result['alternative_paths'][$path] = file_exists($path);
        }
        
        return $result;
    }
}