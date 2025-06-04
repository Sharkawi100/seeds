## 5. Create `_project_docs/future_improvements.md`

````markdown
# Future Improvements Roadmap

Last Updated: June 2025

## Phase 1: Complete Security Tables (Priority: Medium)

### 1. Implement Full Device Tracking

```sql
CREATE TABLE `user_logins` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int unsigned NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `user_agent` varchar(255) DEFAULT NULL,
    `device_type` varchar(50) DEFAULT NULL,
    `browser` varchar(50) DEFAULT NULL,
    `platform` varchar(50) DEFAULT NULL,
    `location` varchar(100) DEFAULT NULL,
    `latitude` decimal(10,8) DEFAULT NULL,
    `longitude` decimal(11,8) DEFAULT NULL,
    `is_trusted` tinyint(1) NOT NULL DEFAULT 0,
    `logged_in_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `logged_out_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `user_logins_user_id_logged_in_at_index` (`user_id`,`logged_in_at`),
    CONSTRAINT `user_logins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
````
