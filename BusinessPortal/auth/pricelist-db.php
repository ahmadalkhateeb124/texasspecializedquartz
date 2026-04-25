<?php
/**
 * Backward-compat shim. The actual class moved to src/Repositories/PriceListManager.php
 * and is now loaded by the src/bootstrap.php autoloader.
 */
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/Repositories/PriceListManager.php';
