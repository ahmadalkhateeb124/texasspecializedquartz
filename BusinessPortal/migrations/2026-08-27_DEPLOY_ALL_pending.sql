-- ============================================================================
-- DEPLOY THIS FILE ONCE ON THE LIVE DATABASE.
--
-- This is the single, final, consolidated migration covering EVERYTHING not
-- yet on live: the employees/order-assignment feature AND the installation
-- sign-off feature. It replaces / supersedes all of the following files —
-- do NOT run any of them individually on live, this file already contains
-- their combined, final effect:
--   - 2026-08-26_add_employees.sql                       (superseded)
--   - 2026-08-26_single_employee_per_job_section.sql      (superseded)
--   - 2026-08-26_employee_per_order.sql                   (superseded)
--   - 2026-08-26_DEPLOY_employees_and_order_assignment.sql (included below, unchanged)
--   - 2026-08-26_installation_signoffs.sql                (included below, unchanged)
--
-- Safe to run on a fresh copy of the live DB (i.e. none of the files above
-- have been applied to it yet). Running it twice will error on the second
-- run (columns/tables already exist) — that's fine, it just means it already
-- ran successfully.
-- ============================================================================

-- ── 1. Employees + one-employee-per-order assignment ───────────────────────

ALTER TABLE `users`
  ADD COLUMN `role`   ENUM('admin','employee') NOT NULL DEFAULT 'admin'   AFTER `designation`,
  ADD COLUMN `status` ENUM('Active','Inactive') NOT NULL DEFAULT 'Active' AFTER `role`;

ALTER TABLE `fabrication_orders`
  ADD COLUMN `assigned_employee_id` int(11) DEFAULT NULL AFTER `account_id`,
  ADD KEY `fk_orders_employee` (`assigned_employee_id`),
  ADD CONSTRAINT `fk_orders_employee` FOREIGN KEY (`assigned_employee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- ── 2. Installation completion sign-off (one record per order) ─────────────

CREATE TABLE `installation_signoffs` (
  `id`                    int(11) NOT NULL AUTO_INCREMENT,
  `order_id`              int(11) NOT NULL,
  `signed_by_employee_id` int(11) DEFAULT NULL,
  `customer_name`         varchar(150) NOT NULL,
  `customer_address`      varchar(255) NOT NULL,
  `worked_area`           longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`worked_area`)),
  `checklist`             longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`checklist`)),
  `signature_text`        varchar(150) NOT NULL,
  `pdf_filename`          varchar(255) NOT NULL,
  `signed_at`             timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_signoff_order` (`order_id`),
  KEY `fk_signoff_employee` (`signed_by_employee_id`),
  CONSTRAINT `fk_signoff_order`    FOREIGN KEY (`order_id`)              REFERENCES `fabrication_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_signoff_employee` FOREIGN KEY (`signed_by_employee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
