<?php
/**
 * run.php — database migration runner for local AND live.
 *
 * Every database change (new table, new column, data fix, …) goes into its own
 * .sql file in database/migrations/. This script applies the files that the
 * current database has not run yet, in filename order, and records each one
 * in the `schema_migrations` table so it never runs twice.
 *
 * It picks the database automatically (BusinessPortal/config/database.php):
 *   - localhost       → u557236614_gr  (local XAMPP)
 *   - live domain     → u557236614_tx  (production credentials file)
 *
 * ── Usage ──────────────────────────────────────────────────────────────────
 * Browser (local):  http://localhost/texasspecializedquartz/run.php
 * Browser (live):   https://texasspecializedquartz.com/run.php?key=YOUR_KEY
 *                   (key lives in BusinessPortal/config/migrate.credentials.php
 *                   on the live server — see the .example file)
 *
 * CLI:  php run.php                 apply pending migrations
 *       php run.php --status        list applied / pending, change nothing
 *       php run.php --new "name"    create an empty migration file
 *       php run.php --import-dump   LOCAL ONLY: wipe the local DB and reload it
 *                                   from u557236614_tx.sql (the latest live
 *                                   export), then apply pending migrations
 *       add --live to run from a shell on the live server
 *
 * ── Writing a migration ────────────────────────────────────────────────────
 * File name:  YYYY_MM_DD_HHMMSS_short_description.sql   (sorted by name)
 * Content:    plain SQL, statements separated by ";".  Never edit a file
 *             after it has run on live — add a new one instead.
 * ------------------------------------------------------------------------- */

declare(strict_types=1);

const MIGRATIONS_DIR   = __DIR__ . '/database/migrations';
const BASELINE_DUMP    = __DIR__ . '/u557236614_tx.sql';
const MIGRATIONS_TABLE = 'schema_migrations';
const LOCK_NAME        = 'tsq_run_php_migrations';

$isCli = PHP_SAPI === 'cli';
$args  = $isCli ? array_slice($argv, 1) : [];

if ($isCli && in_array('--live', $args, true)) {
    putenv('APP_ENV=production');
}

/* ── --new "name": scaffold a migration file (no DB needed) ── */
if ($isCli && ($i = array_search('--new', $args, true)) !== false) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '_', $args[$i + 1] ?? 'change'), '_')) ?: 'change';
    if (!is_dir(MIGRATIONS_DIR)) {
        mkdir(MIGRATIONS_DIR, 0775, true);
    }
    $file = MIGRATIONS_DIR . '/' . date('Y_m_d_His') . "_{$slug}.sql";
    file_put_contents($file, "-- " . basename($file) . "\n-- Describe the change here.\n\n");
    echo "Created " . substr($file, strlen(__DIR__) + 1) . "\n";
    exit(0);
}

/* $_isLocal is set by database.php (localhost / CLI → local DB). */
$dbConfig = require __DIR__ . '/BusinessPortal/config/database.php';
$isLocal  = $_isLocal ?? false;
$envLabel = $isLocal ? 'LOCAL' : 'LIVE';

/* ── Web access control ── */
$key = '';
if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
    header('X-Robots-Tag: noindex, nofollow');

    if (!$isLocal) {
        $credFile = __DIR__ . '/BusinessPortal/config/migrate.credentials.php';
        $expected = is_file($credFile) ? (string)((require $credFile)['key'] ?? '') : '';
        $key      = (string)($_POST['key'] ?? $_GET['key'] ?? '');

        if (strlen($expected) < 16 || !hash_equals($expected, $key)) {
            http_response_code(403);
            exit('Forbidden.');
        }
    }
}

/* ── Decide what to do ── */
if ($isCli) {
    $action = in_array('--import-dump', $args, true) ? 'import'
            : (in_array('--status', $args, true) ? 'status' : 'migrate');
} else {
    $action = $_SERVER['REQUEST_METHOD'] === 'POST' ? (string)($_POST['action'] ?? 'status') : 'status';
}

$log = [];
function say(string $level, string $msg): void
{
    global $log, $isCli;
    $log[] = [$level, $msg];
    if ($isCli) {
        $prefix = ['ok' => '[ok]   ', 'err' => '[FAIL] ', 'warn' => '[warn] ', 'info' => '       '][$level] ?? '';
        echo $prefix . $msg . PHP_EOL;
    }
}

/**
 * Split an SQL script into single statements. Understands quoted strings,
 * backtick identifiers, and -- / # / block comments, so semicolons inside
 * blog text etc. don't break statements. Keeps MySQL /*! ... *\/ hints.
 */
function splitSql(string $sql): array
{
    $stmts = [];
    $buf   = '';
    $len   = strlen($sql);

    for ($i = 0; $i < $len; $i++) {
        $c    = $sql[$i];
        $next = $sql[$i + 1] ?? '';

        if ($c === "'" || $c === '"' || $c === '`') {
            $start = $i;
            for ($i++; $i < $len; $i++) {
                if ($sql[$i] === '\\' && $c !== '`') { $i++; continue; }
                if ($sql[$i] === $c) {
                    if (($sql[$i + 1] ?? '') === $c) { $i++; continue; }
                    break;
                }
            }
            $buf .= substr($sql, $start, $i - $start + 1);
            continue;
        }
        if (($c === '-' && $next === '-' && in_array($sql[$i + 2] ?? "\n", [' ', "\t", "\n", "\r"], true)) || $c === '#') {
            $eol = strpos($sql, "\n", $i);
            $i   = $eol === false ? $len : $eol;
            $buf .= "\n";
            continue;
        }
        if ($c === '/' && $next === '*') {
            $end = strpos($sql, '*/', $i + 2);
            $end = $end === false ? $len : $end + 1;
            if (($sql[$i + 2] ?? '') === '!') {
                $buf .= substr($sql, $i, $end - $i + 1);
            }
            $i = $end;
            continue;
        }
        if ($c === ';') {
            if (trim($buf) !== '') $stmts[] = trim($buf);
            $buf = '';
            continue;
        }
        $buf .= $c;
    }
    if (trim($buf) !== '') $stmts[] = trim($buf);

    return $stmts;
}

/** Run every statement of a script; throws with the failing statement on error. */
function runScript(PDO $pdo, string $sql): int
{
    $stmts = splitSql($sql);
    foreach ($stmts as $n => $stmt) {
        try {
            $pdo->query($stmt)->closeCursor();   // query(), not exec(): a SELECT must be drained
        } catch (PDOException $e) {
            $preview = mb_strimwidth(preg_replace('/\s+/', ' ', $stmt), 0, 300, '…');
            throw new RuntimeException('Statement #' . ($n + 1) . ' failed: ' . $e->getMessage() . "\n    SQL: " . $preview, 0, $e);
        }
    }
    return count($stmts);
}

function migrationFiles(): array
{
    $files = glob(MIGRATIONS_DIR . '/*.sql') ?: [];
    sort($files, SORT_STRING);
    return $files;
}

function ensureMigrationsTable(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `" . MIGRATIONS_TABLE . "` (
          `migration`  varchar(255) NOT NULL,
          `checksum`   char(64)     NOT NULL,
          `applied_at` timestamp    NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`migration`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
}

function appliedMigrations(PDO $pdo): array
{
    return $pdo->query("SELECT migration, checksum, applied_at FROM `" . MIGRATIONS_TABLE . "` ORDER BY migration")
               ->fetchAll(PDO::FETCH_UNIQUE);
}

/* ── Connect ── */
try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['options']
    );
} catch (PDOException $e) {
    say('err', "Cannot connect to {$envLabel} database `{$dbConfig['dbname']}`: " . $e->getMessage());
    $action = 'none';
}

$pending = [];
$applied = [];

if (isset($pdo) && $action !== 'none') {
    $gotLock = $pdo->query("SELECT GET_LOCK('" . LOCK_NAME . "', 0)")->fetchAll(PDO::FETCH_COLUMN)[0] ?? 0;
    if (!$gotLock) {
        say('err', 'Another run.php is already running. Try again in a moment.');
        $action = 'none';
    }
}

/* ── Import the latest live export into the LOCAL database ── */
if ($action === 'import') {
    $confirmed = $isCli ? true : !empty($_POST['confirm']);

    if (!$isLocal) {
        say('err', 'Import is disabled on LIVE. It only ever runs against the local database.');
        $action = 'none';
    } elseif (!$confirmed) {
        say('warn', 'Tick the confirmation box to wipe and re-import the local database.');
        $action = 'status';
    } elseif (!is_file(BASELINE_DUMP)) {
        say('err', 'Dump file not found: ' . basename(BASELINE_DUMP));
        $action = 'none';
    } else {
        if ($isCli && !in_array('--yes', $args, true)) {
            echo "This DELETES every table in local database `{$dbConfig['dbname']}` and reloads it from "
               . basename(BASELINE_DUMP) . ". Type yes to continue: ";
            if (trim((string)fgets(STDIN)) !== 'yes') {
                say('info', 'Cancelled.');
                exit(1);
            }
        }
        try {
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
            $tables = $pdo->query('SHOW FULL TABLES')->fetchAll(PDO::FETCH_NUM);
            foreach ($tables as [$name, $type]) {
                $pdo->exec(($type === 'VIEW' ? 'DROP VIEW' : 'DROP TABLE') . ' IF EXISTS `' . str_replace('`', '``', $name) . '`');
            }
            say('info', 'Dropped ' . count($tables) . ' table(s) from local `' . $dbConfig['dbname'] . '`.');

            $count = runScript($pdo, (string)file_get_contents(BASELINE_DUMP));
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
            say('ok', 'Imported ' . basename(BASELINE_DUMP) . " ({$count} statements).");
            $action = 'migrate';
        } catch (Throwable $e) {
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
            say('err', 'Import failed. ' . $e->getMessage());
            $action = 'none';
        }
    }
}

/* ── Status + apply pending migrations ── */
if (in_array($action, ['status', 'migrate'], true)) {
    try {
        ensureMigrationsTable($pdo);
        $applied = appliedMigrations($pdo);

        foreach (migrationFiles() as $file) {
            $name = basename($file);
            if (!isset($applied[$name])) {
                $pending[] = $file;
            } elseif ($applied[$name]['checksum'] !== hash_file('sha256', $file)) {
                say('warn', "{$name} was edited after it ran here. Edits are NOT re-applied — add a new migration instead.");
            }
        }

        if ($action === 'migrate') {
            if (!$pending) {
                say('ok', "{$envLabel} database `{$dbConfig['dbname']}` is up to date.");
            }
            foreach ($pending as $idx => $file) {
                $name = basename($file);
                try {
                    $count = runScript($pdo, (string)file_get_contents($file));
                } catch (Throwable $e) {
                    say('err', "{$name}: " . $e->getMessage());
                    say('warn', 'Stopped here. Statements before the failing one may already be applied — fix the file (or the DB) and run again.');
                    break;
                }
                $pdo->prepare("INSERT INTO `" . MIGRATIONS_TABLE . "` (migration, checksum) VALUES (?, ?)")
                    ->execute([$name, hash_file('sha256', $file)]);
                say('ok', "{$name} ({$count} statements)");
                unset($pending[$idx]);
            }
            $applied = appliedMigrations($pdo);
        } else {
            say('info', count($pending) . ' pending, ' . count($applied) . ' applied.');
            if ($isCli) {
                foreach ($pending as $file) say('info', '  pending: ' . basename($file));
            }
        }
    } catch (Throwable $e) {
        say('err', $e->getMessage());
    }
}

if (isset($pdo)) {
    $pdo->query("SELECT RELEASE_LOCK('" . LOCK_NAME . "')")->fetchAll();
}

if ($isCli) {
    $failed = array_filter($log, fn($l) => $l[0] === 'err');
    exit($failed ? 1 : 0);
}

/* ── Web page ── */
$h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>DB Migrations · <?= $h($envLabel) ?></title>
<style>
  body{font:15px/1.5 system-ui,-apple-system,Segoe UI,sans-serif;background:#f4f1ec;color:#1a1814;margin:0;padding:32px 16px}
  .wrap{max-width:760px;margin:0 auto}
  h1{font-size:22px;margin:0 0 4px}
  .env{display:inline-block;font-size:12px;font-weight:700;letter-spacing:1px;padding:3px 10px;border-radius:999px;color:#fff;background:<?= $isLocal ? '#2f6b3f' : '#b3261e' ?>}
  .card{background:#fff;border-radius:12px;padding:18px 20px;margin:16px 0;box-shadow:0 1px 3px rgba(0,0,0,.06)}
  .card h2{font-size:15px;margin:0 0 10px}
  ul{margin:0;padding-left:18px}
  li{margin:2px 0;font-family:ui-monospace,Menlo,monospace;font-size:13px}
  .muted{color:#8c877f}
  .log div{font-family:ui-monospace,Menlo,monospace;font-size:13px;white-space:pre-wrap;padding:6px 10px;border-radius:6px;margin:4px 0}
  .ok{background:#eaf4ec;color:#2f6b3f}.err{background:#fdecea;color:#b3261e}.warn{background:#fbf0e1;color:#9a5b12}.info{background:#f4f1ec}
  button{font:inherit;font-weight:600;border:0;border-radius:8px;padding:10px 18px;cursor:pointer;background:#1a1814;color:#fff}
  button.danger{background:#b3261e}
  button:disabled{opacity:.4;cursor:default}
  label{font-size:14px}
</style>
</head>
<body>
<div class="wrap">
  <h1>Database migrations <span class="env"><?= $h($envLabel) ?></span></h1>
  <div class="muted">Database: <strong><?= $h($dbConfig['dbname']) ?></strong></div>

  <?php if ($log): ?>
  <div class="card log">
    <?php foreach ($log as [$level, $msg]): ?><div class="<?= $h($level) ?>"><?= $h($msg) ?></div><?php endforeach; ?>
  </div>
  <?php endif; ?>

  <?php if (isset($pdo)): ?>
  <div class="card">
    <h2>Pending (<?= count($pending) ?>)</h2>
    <?php if ($pending): ?>
      <ul><?php foreach ($pending as $f): ?><li><?= $h(basename($f)) ?></li><?php endforeach; ?></ul>
    <?php else: ?>
      <div class="muted">Nothing to run — this database is up to date.</div>
    <?php endif; ?>
    <form method="post" style="margin-top:14px">
      <input type="hidden" name="action" value="migrate">
      <?php if (!$isLocal): ?><input type="hidden" name="key" value="<?= $h($key) ?>"><?php endif; ?>
      <button type="submit" <?= $pending ? '' : 'disabled' ?>>Run <?= count($pending) ?> migration(s) on <?= $h($envLabel) ?></button>
    </form>
  </div>

  <div class="card">
    <h2>Applied (<?= count($applied) ?>)</h2>
    <?php if ($applied): ?>
      <ul><?php foreach (array_reverse($applied, true) as $name => $row): ?><li><?= $h($name) ?> <span class="muted">— <?= $h($row['applied_at']) ?></span></li><?php endforeach; ?></ul>
    <?php else: ?>
      <div class="muted">None yet.</div>
    <?php endif; ?>
  </div>

  <?php if ($isLocal): ?>
  <div class="card">
    <h2>Reload local DB from live export</h2>
    <p class="muted" style="margin-top:0">Deletes every table in <strong><?= $h($dbConfig['dbname']) ?></strong>, imports <strong><?= $h(basename(BASELINE_DUMP)) ?></strong>, then runs pending migrations.</p>
    <form method="post">
      <input type="hidden" name="action" value="import">
      <label><input type="checkbox" name="confirm" value="1"> Yes, wipe my local database</label><br><br>
      <button type="submit" class="danger">Import <?= $h(basename(BASELINE_DUMP)) ?> into local</button>
    </form>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</div>
</body>
</html>
