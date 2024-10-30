<?php defined('ABSPATH') || exit;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Plugin uninstall process
$options = get_option('bpstory_settings');
if (isset($options['data_deletion_status']) && 'on' === $options['data_deletion_status']) {
    delete_option('bpstory_settings');
    global $wpdb;
    $prefix = $wpdb->prefix . 'bpstory_';
    $adminStoryBoxes = $prefix.'admin_story_boxes';
    $adminSrories = $prefix.'admin_stories';
    $wpdb->query("DROP TABLE IF EXISTS $adminStoryBoxes");
    $wpdb->query("DROP TABLE IF EXISTS $adminSrories");
    delete_post_meta_by_key('bpstory_media_url');
    delete_post_meta_by_key('bpstory_story_visibility');
}