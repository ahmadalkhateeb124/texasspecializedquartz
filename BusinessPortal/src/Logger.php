<?php
/**
 * Logger — lightweight file-based logger for production diagnostics.
 *
 * Writes to /logs/{channel}.log (logs/ is web-blocked via .htaccess).
 * Auto-rotates when a single log exceeds MAX_BYTES.
 *
 * Usage:
 *   Logger::info('order.created', ['order_id' => 42]);
 *   Logger::warn('mail.imap.failed', ['err' => $e->getMessage()]);
 *   Logger::error('payment.gateway.timeout', ['amount' => 199]);
 *   Logger::exception($throwable, 'orders');
 */
final class Logger
{
    /** Max single-file size before rotation (5 MB). */
    private const MAX_BYTES = 5 * 1024 * 1024;
    /** How many rotated copies to keep. */
    private const KEEP      = 5;

    /** Log directory. Created on first write. */
    private static ?string $logDir = null;

    public static function info(string $event, array $context = []): void
    {
        self::write('info', $event, $context);
    }

    public static function warn(string $event, array $context = []): void
    {
        self::write('warn', $event, $context);
    }

    public static function error(string $event, array $context = []): void
    {
        self::write('error', $event, $context);
    }

    /** Convenience: log a Throwable with stack trace. */
    public static function exception(Throwable $e, string $channel = 'app'): void
    {
        self::write('error', 'exception', [
            'channel' => $channel,
            'class'   => get_class($e),
            'msg'     => $e->getMessage(),
            'file'    => $e->getFile() . ':' . $e->getLine(),
            'trace'   => self::trimTrace($e->getTraceAsString()),
        ], $channel);
    }

    /* ───────────────────── internals ───────────────────── */

    private static function dir(): string
    {
        if (self::$logDir === null) {
            self::$logDir = dirname(__DIR__, 2) . '/logs';
            if (!is_dir(self::$logDir)) {
                @mkdir(self::$logDir, 0755, true);
            }
        }
        return self::$logDir;
    }

    private static function write(string $level, string $event, array $ctx, string $channel = 'app'): void
    {
        $file = self::dir() . '/' . preg_replace('~[^a-z0-9_-]~i', '', $channel) . '.log';
        if (is_file($file) && filesize($file) >= self::MAX_BYTES) {
            self::rotate($file);
        }

        $line = sprintf(
            "[%s] %-5s %s %s ip=%s ua=%s %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            session_id() ?: '-',
            $event,
            $_SERVER['REMOTE_ADDR']     ?? '-',
            self::shortUa($_SERVER['HTTP_USER_AGENT'] ?? ''),
            $ctx ? json_encode($ctx, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : ''
        );

        @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
    }

    private static function rotate(string $file): void
    {
        for ($i = self::KEEP - 1; $i >= 1; $i--) {
            $from = $file . '.' . $i;
            $to   = $file . '.' . ($i + 1);
            if (is_file($from)) @rename($from, $to);
        }
        @rename($file, $file . '.1');
    }

    private static function shortUa(string $ua): string
    {
        $ua = trim($ua);
        return $ua === '' ? '-' : substr(preg_replace('~[\s]+~', ' ', $ua), 0, 80);
    }

    private static function trimTrace(string $trace): string
    {
        return implode("\n", array_slice(explode("\n", $trace), 0, 12));
    }
}
