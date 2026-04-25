# scripts/

## backup.sh

Daily backup of the database and uploaded media.

### What it backs up
- MySQL dump (gzipped)
- `images/blog/` — admin-uploaded blog post images
- `BusinessPortal/uploads/` — videos and other admin uploads
- `BusinessPortal/auth/uploads/` — admin avatars
- `auth/uploads/` — legacy uploads

### Where backups go
Default: `$HOME/backups/texasspecializedquartz/YYYY-MM-DD/`

Override with `BACKUP_ROOT=/some/other/dir bash scripts/backup.sh`.

### Retention
Keeps the last 14 daily folders, prunes anything older.
Override: `RETENTION_DAYS=30 bash scripts/backup.sh`

### Manual run
```bash
bash scripts/backup.sh
```

### Cron setup (recommended)

Run daily at 3 AM, log to `/path/to/logs/backup.log`:

```cron
0 3 * * * /bin/bash /full/path/to/texasspecializedquartz/scripts/backup.sh >> /full/path/to/texasspecializedquartz/logs/backup.log 2>&1
```

On shared hosting (cPanel → Cron Jobs):
- **Command:** `/bin/bash /home/USER/public_html/scripts/backup.sh >> /home/USER/public_html/logs/backup.log 2>&1`
- **Schedule:** `0 3 * * *` (daily at 03:00)

### Restore from a backup

```bash
# Find a backup folder
ls $HOME/backups/texasspecializedquartz/

# Restore the database
gunzip < /path/to/backup/db-DBNAME.sql.gz | mysql -u USER -p DBNAME

# Restore an uploads folder
tar -xzf /path/to/backup/uploads-blog.tar.gz -C /path/to/site/images/
```

### Off-site copies (recommended)

The script writes to local disk. For real safety, sync the folder off-site:

```bash
# Append to your cron entry
0 3 * * * bash /path/scripts/backup.sh && rclone sync $HOME/backups/texasspecializedquartz/ remote:tsq-backups/
```

(Or use `rsync`, AWS S3 CLI, BackBlaze B2, etc.)
