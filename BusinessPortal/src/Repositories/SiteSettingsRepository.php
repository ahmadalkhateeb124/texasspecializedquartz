<?php

/**
 * SiteSettingsRepository — key/value site configuration.
 * Cached per-request after first load.
 */
class SiteSettingsRepository
{
    private static ?array $cache = null;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        if (self::$cache !== null) return self::$cache;
        try {
            $rows = $this->pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (PDOException $e) {
            $rows = [];
        }
        self::$cache = $rows;
        return $rows;
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $all = $this->all();
        $v = $all[$key] ?? null;
        return ($v === null || $v === '') ? $default : $v;
    }

    public function setMany(array $pairs): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO site_settings (setting_key, setting_value)
            VALUES (:k, :v)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
        ");
        foreach ($pairs as $k => $v) {
            $stmt->execute([':k' => $k, ':v' => $v]);
        }
        self::$cache = null;
    }
}

/**
 * Global helper — reads a setting with caching.
 * Requires global $pdo to be available.
 */
function setting(string $key, ?string $default = null): ?string
{
    static $repo = null;
    if ($repo === null) {
        global $pdo;
        if (!$pdo instanceof PDO) return $default;
        $repo = new SiteSettingsRepository($pdo);
    }
    return $repo->get($key, $default);
}
