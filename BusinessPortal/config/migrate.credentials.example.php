<?php
/**
 * Copy to `migrate.credentials.php` (gitignored) on the LIVE server only.
 * run.php on live then opens with: https://texasspecializedquartz.com/run.php?key=THIS_KEY
 * Use a long random value (at least 16 characters), e.g. from: php -r "echo bin2hex(random_bytes(24));"
 */
return [
    'key' => 'CHANGE_ME_TO_A_LONG_RANDOM_STRING',
];
