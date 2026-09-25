#!/usr/bin/env bash
###############################################################################
#  scripts/backup.sh — Daily backup for Texas Specialized Quartz
#
#  Backs up:
#    • MySQL database (mysqldump)
#    • images/blog          (admin-uploaded post images)
#    • BusinessPortal/uploads (videos, etc.)
#    • BusinessPortal/auth/uploads (avatars)
#    • auth/uploads          (legacy)
#
#  Output:   /home/<user>/backups/texasspecializedquartz/YYYY-MM-DD/
#  Retention: keeps the last 14 daily backups, removes older ones.
#
#  Usage (manual):       bash scripts/backup.sh
#  Usage (cron, daily):  0 3 * * * /bin/bash /full/path/to/scripts/backup.sh >> /full/path/to/logs/backup.log 2>&1
###############################################################################

set -Eeuo pipefail

# ── Resolve paths ───────────────────────────────────────────────
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

# ── Load DB credentials from the same source the app uses ──────
# Reads BusinessPortal/config/database.credentials.php on prod,
# falls back to the inline defaults when running locally.
read_db_creds() {
    php -r "
        \$cfg = require '$PROJECT_DIR/BusinessPortal/config/database.php';
        echo \$cfg['host']     . \"\n\";
        echo \$cfg['port']     . \"\n\";
        echo \$cfg['dbname']   . \"\n\";
        echo \$cfg['username'] . \"\n\";
        echo \$cfg['password'] . \"\n\";
    "
}

mapfile -t DB <<< "$(read_db_creds)"
DB_HOST="${DB[0]:-localhost}"
DB_PORT="${DB[1]:-3306}"
DB_NAME="${DB[2]:-}"
DB_USER="${DB[3]:-}"
DB_PASS="${DB[4]:-}"

if [[ -z "$DB_NAME" ]]; then
    echo "[backup] ERROR: empty DB_NAME (check database.credentials.php on prod)"
    exit 1
fi

# ── Output directory ───────────────────────────────────────────
TODAY="$(date +%F)"
BACKUP_ROOT="${BACKUP_ROOT:-$HOME/backups/texasspecializedquartz}"
DEST="$BACKUP_ROOT/$TODAY"
mkdir -p "$DEST"

echo "[backup] === $(date '+%F %T') — start ==="
echo "[backup] target: $DEST"

# ── 1. Dump database ───────────────────────────────────────────
DUMP_FILE="$DEST/db-$DB_NAME.sql.gz"
echo "[backup] dumping DB '$DB_NAME' → $DUMP_FILE"
MYSQL_PWD="$DB_PASS" mysqldump \
    --host="$DB_HOST" \
    --port="$DB_PORT" \
    --user="$DB_USER" \
    --single-transaction \
    --quick \
    --routines \
    --triggers \
    --default-character-set=utf8mb4 \
    --skip-lock-tables \
    "$DB_NAME" \
  | gzip -9 > "$DUMP_FILE"

DB_SIZE="$(du -h "$DUMP_FILE" | cut -f1)"
echo "[backup] DB dump OK ($DB_SIZE)"

# ── 2. Tar uploads (only if folder has files) ──────────────────
tar_if_present () {
    local label="$1"
    local src="$2"
    local outfile="$DEST/uploads-${label}.tar.gz"

    if [[ -d "$src" ]] && [[ -n "$(ls -A "$src" 2>/dev/null | grep -v '^\.htaccess$' || true)" ]]; then
        echo "[backup] archiving $label ← $src"
        tar -czf "$outfile" -C "$(dirname "$src")" "$(basename "$src")"
        local sz="$(du -h "$outfile" | cut -f1)"
        echo "[backup]   $label OK ($sz)"
    else
        echo "[backup] skip $label (empty or missing)"
    fi
}

tar_if_present "blog"          "$PROJECT_DIR/images/blog"
tar_if_present "businessportal" "$PROJECT_DIR/BusinessPortal/uploads"
tar_if_present "admin-avatars"  "$PROJECT_DIR/BusinessPortal/auth/uploads"
tar_if_present "legacy-auth"    "$PROJECT_DIR/auth/uploads"

# ── 3. Verify the dump is restorable ───────────────────────────
echo "[backup] verifying dump readability"
if ! gzip -t "$DUMP_FILE"; then
    echo "[backup] ERROR: dump is corrupt"
    exit 2
fi

# ── 4. Manifest ────────────────────────────────────────────────
{
    echo "Backup taken: $(date '+%F %T %Z')"
    echo "Host:         $(hostname)"
    echo "DB:           $DB_NAME @ $DB_HOST:$DB_PORT"
    echo
    ls -lh "$DEST"
} > "$DEST/MANIFEST.txt"

# ── 5. Retention — keep last 14 daily folders ──────────────────
RETENTION_DAYS="${RETENTION_DAYS:-14}"
echo "[backup] pruning backups older than $RETENTION_DAYS days"
find "$BACKUP_ROOT" -mindepth 1 -maxdepth 1 -type d -mtime +"$RETENTION_DAYS" -print -exec rm -rf {} \;

TOTAL_SIZE="$(du -sh "$DEST" | cut -f1)"
echo "[backup] DONE — $DEST ($TOTAL_SIZE)"
echo "[backup] === $(date '+%F %T') — end ==="
