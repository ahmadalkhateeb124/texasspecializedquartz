<?php

/**
 * FileUploader — validates and saves an uploaded file.
 */
class FileUploader
{
    private int $maxBytes;
    private array $allowedMimeTypes;
    private string $targetDir;

    public function __construct(
        string $targetDir,
        int $maxBytes = 20971520,
        ?array $allowedMimeTypes = null
    ) {
        $this->targetDir = rtrim($targetDir, '/') . '/';
        $this->maxBytes  = $maxBytes;
        $this->allowedMimeTypes = $allowedMimeTypes ?? [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'application/zip',
        ];
    }

    /**
     * @param array $file  $_FILES[name] entry
     * @return array{stored_name: string, full_path: string, original_name: string}
     * @throws RuntimeException on validation / IO failure
     */
    public function save(array $file): array
    {
        if (empty($file['name'])) {
            throw new RuntimeException('No file provided.');
        }

        if ($file['size'] > $this->maxBytes) {
            throw new RuntimeException('File too large. Max ' . ($this->maxBytes / 1048576) . 'MB.');
        }

        if (!in_array($file['type'], $this->allowedMimeTypes, true)) {
            throw new RuntimeException('Invalid file type.');
        }

        if (!is_dir($this->targetDir) && !mkdir($this->targetDir, 0777, true)) {
            throw new RuntimeException('Upload directory is not writable.');
        }

        $storedName = time() . '_' . basename($file['name']);
        $fullPath   = $this->targetDir . $storedName;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new RuntimeException('Failed to save uploaded file.');
        }

        return [
            'stored_name'   => $storedName,
            'full_path'     => $fullPath,
            'original_name' => $file['name'],
        ];
    }
}
