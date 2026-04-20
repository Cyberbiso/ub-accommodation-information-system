-- Cleanup script for optional Laravel framework tables.
-- Run this only after deploying config that uses:
--   SESSION_DRIVER=file
--   CACHE_STORE=file
--   QUEUE_CONNECTION=sync
--
-- These drops do NOT remove application data tables such as users,
-- properties, payments, bookings, applications, or notifications.

START TRANSACTION;

DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `sessions`;

COMMIT;

-- Optional but NOT included above:
-- DROP TABLE IF EXISTS `password_reset_tokens`;
-- Only drop password_reset_tokens if you also disable the forgot/reset
-- password feature in the application.
