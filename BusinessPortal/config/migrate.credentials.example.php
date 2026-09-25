<?php
/**
 * Copy to `migrate.credentials.php` (gitignored) on the LIVE server only.
 * run.php on live then opens with: https://texasspecializedquartz.com/run.php?key=THIS_KEY
 * Use a long random value (at least 16 characters), e.g. from: php -r "echo bin2hex(random_bytes(24));"
 */
return [
    'key' => '6dffe54a2b56d3068f8820c03ed60c90b7efd6a8',
];
