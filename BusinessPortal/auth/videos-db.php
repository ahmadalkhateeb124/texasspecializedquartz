<?php
/**
* Video Management Class
* Handles video uploads, updates, deletions, and database interactions.
*/
class VideoManager
{
    private $pdo;
    private $uploadsDir;
    private $maxFileSize = 500000000; // 500MB
    private $allowedFormats = ['mp4', 'webm', 'ogg', 'mov', 'avi'];
    
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        
        // تحديد المسار بناءً على موقع الموقع
        $this->uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/auth/uploads/videos/';
        
        // Debug: log the uploads directory
        error_log("VideoManager initialized. Uploads directory: " . $this->uploadsDir);
        error_log("Document root: " . $_SERVER['DOCUMENT_ROOT']);
        
        // Ensure the uploads directory exists
        if (!is_dir($this->uploadsDir)) {
            if (!mkdir($this->uploadsDir, 0777, true)) {
                throw new Exception('Failed to create upload directory: ' . $this->uploadsDir);
            }
        }
        
        // Check if directory is writable; don't throw error if chmod fails
        if (!is_writable($this->uploadsDir)) {
            @chmod($this->uploadsDir, 0777); // Suppress errors if chmod fails
        }
    }
    
    /**
     * Upload a new video
     */
    public function uploadVideo(array $file, string $videoName): int
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
        
        // Validate file existence
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            throw new Exception('No file uploaded. Please select a video file to upload.');
        }
        
        // Validate file size
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('File size exceeds the maximum allowed size of 500MB.');
        }
        
        // Validate file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedFormats)) {
            throw new Exception('Invalid file format. Allowed formats: ' . implode(', ', $this->allowedFormats));
        }
        
        // Generate unique filename and move the file
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
        
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            // Get more specific error information
            $error = error_get_last();
            $errorMsg = isset($error['message']) ? $error['message'] : 'Unknown error';
            throw new Exception('Failed to move uploaded file to destination. Error: ' . $errorMsg . '. Check server permissions and disk space.');
        }
        
        // Calculate video duration (if possible)
        $duration = $this->getVideoDuration($filePath);
        
        // Save the video file details to the database
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO videos (video_name, video_file, file_size, duration) VALUES (:video_name, :video_file, :file_size, :duration)'
            );
            $stmt->execute([
                ':video_name' => $videoName,
                ':video_file' => $uniqueFileName,
                ':file_size' => $file['size'],
                ':duration' => $duration
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Rollback file upload if database insertion fails
            unlink($filePath);
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }
    
    /**
     * Get video duration (requires ffmpeg/ffprobe installed)
     */
    private function getVideoDuration(string $filePath): ?string
    {
        if (!function_exists('exec') || !shell_exec('which ffprobe')) {
            return null;
        }
        
        $cmd = 'ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ' . escapeshellarg($filePath);
        $output = trim(shell_exec($cmd));
        
        if (is_numeric($output)) {
            $hours = (int) ($output / 3600);
            $minutes = (int) (($output % 3600) / 60);
            $seconds = (int) ($output % 60);
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        
        return null;
    }
    
    /**
     * Update an existing video (replace file, update name)
     */
    public function updateVideo(int $id, ?array $file, ?string $videoName): bool
    {
        $video = $this->getVideoById($id);
        if (!$video) {
            throw new Exception('Video not found.');
        }
        
        $newFileName = $video['video_file'];
        $newFileSize = $video['file_size'];
        $newDuration = $video['duration'];
        
        // Handle new file upload if provided
        if ($file && isset($file['tmp_name']) && !empty($file['tmp_name'])) {
            // Validate file size
            if ($file['size'] > $this->maxFileSize) {
                throw new Exception('File size exceeds the maximum allowed size of 500MB.');
            }
            
            // Validate file extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $this->allowedFormats)) {
                throw new Exception('Invalid file format. Allowed formats: ' . implode(', ', $this->allowedFormats));
            }
            
            // Remove old file
            $oldFilePath = $this->uploadsDir . $video['video_file'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
            
            // Save new file
            $newFileName = time() . '_' . uniqid() . '.' . $extension;
            $newFilePath = $this->uploadsDir . $newFileName;
            
            if (!move_uploaded_file($file['tmp_name'], $newFilePath)) {
                throw new Exception('Failed to upload new file.');
            }
            
            // Get new file duration
            $newDuration = $this->getVideoDuration($newFilePath);
            $newFileSize = $file['size'];
        }
        
        // Fallback to existing video name if not provided
        $finalVideoName = $videoName ?? $video['video_name'];
        
        // Update database record
        $stmt = $this->pdo->prepare(
            'UPDATE videos SET video_name = :video_name, video_file = :video_file, file_size = :file_size, duration = :duration, updated_at = NOW() WHERE id = :id'
        );
        $stmt->execute([
            ':video_name' => $finalVideoName,
            ':video_file' => $newFileName,
            ':file_size' => $newFileSize,
            ':duration' => $newDuration,
            ':id' => $id
        ]);
        
        return true;
    }
    
    /**
     * Delete a video
     */
    public function deleteVideo(int $id): bool
    {
        $video = $this->getVideoById($id);
        if (!$video) {
            throw new Exception('Video not found.');
        }
        
        // Delete file from server
        $filePath = $this->uploadsDir . $video['video_file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Remove from database
        $stmt = $this->pdo->prepare('DELETE FROM videos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        
        return true;
    }
    
    /**
     * Get video by ID
     */
    public function getVideoById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM videos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Format file size for display
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return round($bytes / (1024 ** $power), 2) . ' ' . $units[$power];
    }
    
    /**
     * Get the full URL for a video file - FIXED VERSION
     */
    public function getFileUrl(string $filename): string
    {
        // Debug
        error_log("VideoManager::getFileUrl called for: " . $filename);
        error_log("Uploads directory: " . $this->uploadsDir);
        
        // First check if file exists
        $filePath = $this->uploadsDir . $filename;
        
        if (!file_exists($filePath)) {
            // Try alternative locations
            $altPaths = [
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/videos/' . $filename,
                $_SERVER['DOCUMENT_ROOT'] . '/auth/uploads/videos/' . $filename,
                dirname(__DIR__, 2) . '/auth/uploads/videos/' . $filename,
                dirname(__DIR__, 3) . '/auth/uploads/videos/' . $filename
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
            error_log("File not found in any location: " . $filename);
        } else {
            error_log("File found in primary location: " . $filePath);
        }
        
        // File exists or not found, generate URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $url = $protocol . $host . '/auth/uploads/videos/' . $filename;
        
        error_log("Returning URL: " . $url);
        
        return $url;
    }
    
    /**
     * Get video MIME type
     */
    public function getVideoMimeType(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeTypes = [
            'mp4' => 'video/mp4',
            'm4v' => 'video/mp4',
            'webm' => 'video/webm',
            'ogv' => 'video/ogg',
            'ogg' => 'video/ogg',
            'mov' => 'video/quicktime',
            'avi' => 'video/x-msvideo',
            'wmv' => 'video/x-ms-wmv',
            'flv' => 'video/x-flv',
            'mkv' => 'video/x-matroska'
        ];
        return $mimeTypes[$extension] ?? 'video/mp4';
    }
    
    /**
     * Check if file exists on server
     */
    public function fileExists(string $filename): bool
    {
        $filePath = $this->uploadsDir . $filename;
        
        // تحقق أيضاً في المسار البديل
        if (!file_exists($filePath)) {
            $altPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/videos/' . $filename;
            return file_exists($altPath);
        }
        
        return file_exists($filePath);
    }
    
    /**
     * Get absolute file path
     */
    public function getFilePath(string $filename): string
    {
        $filePath = $this->uploadsDir . $filename;
        
        if (!file_exists($filePath)) {
            $altPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/videos/' . $filename;
            if (file_exists($altPath)) {
                return $altPath;
            }
        }
        
        return $filePath;
    }
    
    /**
     * Get the uploads directory path
     */
    public function getUploadsDir(): string
    {
        return $this->uploadsDir;
    }
    
    /**
     * Debug function to check video access
     */
    public function debugVideoAccess(string $filename): array
    {
        $result = [
            'filename' => $filename,
            'primary_path' => $this->uploadsDir . $filename,
            'primary_exists' => file_exists($this->uploadsDir . $filename),
            'alternative_paths' => [],
            'generated_url' => $this->getFileUrl($filename),
            'server_info' => [
                'document_root' => $_SERVER['DOCUMENT_ROOT'],
                'https' => isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off',
                'host' => $_SERVER['HTTP_HOST']
            ]
        ];
        
        // Check alternative paths
        $altPaths = [
            $_SERVER['DOCUMENT_ROOT'] . '/uploads/videos/' . $filename,
            $_SERVER['DOCUMENT_ROOT'] . '/auth/uploads/videos/' . $filename,
            dirname(__DIR__, 2) . '/auth/uploads/videos/' . $filename,
            dirname(__DIR__, 3) . '/auth/uploads/videos/' . $filename
        ];
        
        foreach ($altPaths as $path) {
            $result['alternative_paths'][$path] = file_exists($path);
        }
        
        return $result;
    }
}