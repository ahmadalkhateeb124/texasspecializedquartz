<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';
$repo = new GalleryRepository($pdo);

$saveImage = function (?array $file): ?string {
    if (!$file || empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/avif' => 'avif', 'image/gif' => 'gif'];
    $info = @getimagesize($file['tmp_name']);
    $mime = $info['mime'] ?? mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) throw new RuntimeException('Invalid image type. Allowed: JPG, PNG, WEBP, AVIF, GIF.');
    if ($file['size'] > 10 * 1024 * 1024) throw new RuntimeException('Max 10 MB.');

    $dir = __DIR__ . '/../assets/gallery/';
    if (!is_dir($dir)) @mkdir($dir, 0777, true);
    if (!is_writable($dir)) @chmod($dir, 0777);

    $name = 'gx-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $target = $dir . $name;
    if (!move_uploaded_file($file['tmp_name'], $target)) throw new RuntimeException('Failed to save uploaded file.');
    return $name;
};

try {
    switch ($action) {
        case 'create': {
            $img = $saveImage($_FILES['image'] ?? null);
            if (!$img) throw new RuntimeException('Image is required.');
            $id = $repo->create([
                'image'      => $img,
                'caption'    => trim($_POST['caption'] ?? ''),
                'category'   => $_POST['category']   ?? 'kitchen',
                'material'   => $_POST['material']   ?? 'granite',
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'status'     => $_POST['status']     ?? 'active',
            ]);
            echo json_encode(['success' => true, 'id' => $id]);
            break;
        }
        case 'update': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('Missing id');
            $existing = $repo->find($id);
            if (!$existing) throw new RuntimeException('Not found');
            $img = $saveImage($_FILES['image'] ?? null);
            $repo->update($id, [
                'image'      => $img, // null if no new upload
                'caption'    => trim($_POST['caption'] ?? ''),
                'category'   => $_POST['category']   ?? 'kitchen',
                'material'   => $_POST['material']   ?? 'granite',
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'status'     => $_POST['status']     ?? 'active',
            ]);
            // delete old image only if a new one was uploaded
            if ($img && !empty($existing['image']) && $existing['image'] !== $img) {
                $isAdminUploaded = str_starts_with((string)$existing['image'], 'gx-');
                if ($isAdminUploaded) {
                    $old = __DIR__ . '/../assets/gallery/' . $existing['image'];
                    if (is_file($old)) @unlink($old);
                }
            }
            echo json_encode(['success' => true]);
            break;
        }
        case 'delete': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('Missing id');
            $existing = $repo->find($id);
            $repo->delete($id);
            if ($existing && str_starts_with((string)$existing['image'], 'gx-')) {
                $f = __DIR__ . '/../assets/gallery/' . $existing['image'];
                if (is_file($f)) @unlink($f);
            }
            echo json_encode(['success' => true]);
            break;
        }
        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
