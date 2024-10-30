<?php defined('ABSPATH') || exit;

$properties = (object) BPSTORY;
/**
 * Plugin activate hook
 */
register_activation_hook($properties->pluginFile, function() use ($properties) {

    global $wpdb;
    $settings = $properties->settingsName;
    $prefix = $wpdb->prefix . $properties->textDomain . '_';

    if (!function_exists('dbDelta')) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    }

    // Get $wpdb charset collate set charset
    if ($wpdb->has_cap('collation')) {
        $charset = $wpdb->get_charset_collate();
    }

    $createTable = function($tableName, $properties) use ($wpdb, $charset, $prefix) {
        $tableName = $prefix . $tableName;
        if (!$wpdb->get_var("SHOW TABLES LIKE '{$tableName}'") !== $tableName) {
            dbDelta("CREATE TABLE IF NOT EXISTS `{$tableName}` ($properties) {$charset};");
        }
    };
    
    /**
     * Admin story boxes
     * @since 3.0.0
     */
    $createTable('admin_story_boxes', '
        `id` BIGINT(20) AUTO_INCREMENT,
        `story_box` VARCHAR(20) NOT NULL,
        `story_box_image` TEXT NOT NULL,
        `status` BOOLEAN NOT NULL,
        `created_at` timestamp default CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ');

    /**
     * Admin stories table
     * @since 3.0.0
     */
    $createTable('admin_stories', '
        `story_id` BIGINT(20) AUTO_INCREMENT,
        `story_box_id` BIGINT(20) NOT NULL,
        `media_url` TEXT NOT NULL,
        `external_url` TEXT,
        `status` BOOLEAN NOT NULL,
        `show_until_date` DATE,
        `created_at` timestamp default CURRENT_TIMESTAMP,
        PRIMARY KEY (`story_id`)
    ');

    if (empty(get_option($settings))) {
        // Default settings
        add_option($settings, [
            'data_deletion_status' => '',
        ]);
    }
    
    update_option($settings.'_db_version', '3');     
});