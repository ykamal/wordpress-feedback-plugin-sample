<?php

namespace WPFB\DB;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

use WPFB\Constants;

/**
 * Database tables manager
 */
class Manager {

    // !! THESE ARE STATIC FOR SAFETY PURPOSES

    /**
     * Create the tables
     */
    public static function create_tables()
    {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . Constants::DB_NAME_FB_TRACKER;

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            ip VARCHAR(45) NOT NULL,
            content_id BIGINT(20) UNSIGNED NOT NULL,
            is_helpful TINYINT(1) DEFAULT 1 NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            CONSTRAINT fkey_content_id FOREIGN KEY (content_id) REFERENCES {$wpdb->prefix}posts(ID) ON DELETE CASCADE
        ) {$charset}";

        dbDelta($sql);
    }

    /**
     * Delete the tables
     */
    public static function delete_tables()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . Constants::DB_NAME_FB_TRACKER;
        $sql = "DROP TABLE IF EXISTS {$table_name}";
        $wpdb->query($sql);
    }
}
