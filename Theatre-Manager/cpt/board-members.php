<?php
// Register Board Members CPT
function tm_register_board_members_cpt() {
    $labels = array(
        'name' => 'Board Members',
        'singular_name' => 'Board Member',
        'menu_name' => 'Board Members',
        'name_admin_bar' => 'Board Member',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-groups',
        'supports' => array(''), // No editor or custom fields
        'show_in_rest' => false,
        'show_in_menu' => 'theatre-manager', // Ensures CPT appears under Theatre Manager menu
    );

    register_post_type('board_member', $args);
}
add_action('init', 'tm_register_board_members_cpt');

// Add Meta Boxes
function tm_add_board_member_meta_boxes() {
    add_meta_box('tm_board_member_details', 'Board Member Details', 'tm_board_member_meta_box_callback', 'board_member', 'normal', 'default');
}
add_action('add_meta_boxes', 'tm_add_board_member_meta_boxes');

// Meta Box Callback
// Meta Box Callback
function tm_board_member_meta_box_callback($post) {
    wp_nonce_field('tm_save_board_member_meta', 'tm_board_member_nonce');

    $position = get_post_meta($post->ID, '_tm_position', true);
    $name = get_post_meta($post->ID, '_tm_name', true);
    $media_urls = get_post_meta($post->ID, '_tm_media_urls', true);
    $media_urls_array = !empty($media_urls) ? explode(',', $media_urls) : array();

    echo '<div class="tm-board-member">';
    echo '<label>Position:</label><br>';
    echo '<input type="text" name="tm_position" value="' . esc_attr($position) . '" /><br><br>';

    echo '<label>Name:</label><br>';
    echo '<input type="text" name="tm_name" value="' . esc_attr($name) . '" /><br><br>';

    echo '<label>Media (Images/Videos):</label><br>';
    echo '<input type="hidden" name="tm_media_urls" id="tm_media_urls" value="' . esc_attr($media_urls) . '" />';
    echo '<div id="tm_media_preview">';
    foreach ($media_urls_array as $url) {
        $url = trim($url);
        if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $url)) {
            echo '<img src="' . esc_url($url) . '" style="max-width:150px; margin:5px;" />';
        } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $url)) {
            echo '<video src="' . esc_url($url) . '" controls style="max-width:150px; margin:5px;"></video>';
        }
    }
    echo '</div>';
    echo '<button type="button" class="button" id="tm_media_button">Select Media</button>';
    echo '</div>';
}


// Save Meta Data
function tm_save_board_member_meta($post_id) {
    if (!isset($_POST['tm_board_member_nonce']) || !wp_verify_nonce($_POST['tm_board_member_nonce'], 'tm_save_board_member_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $position = sanitize_text_field($_POST['tm_position'] ?? '');
    $name = sanitize_text_field($_POST['tm_name'] ?? '');
    $media_urls_raw = $_POST['tm_media_urls'] ?? '';
    $media_urls_clean = implode(',', array_map('esc_url_raw', array_filter(array_map('trim', explode(',', $media_urls_raw)))));

    update_post_meta($post_id, '_tm_position', $position);
    update_post_meta($post_id, '_tm_name', $name);
    update_post_meta($post_id, '_tm_media_urls', $media_urls_clean);

    // Auto-set post title from Name
    remove_action('save_post', 'tm_save_board_member_meta');
    wp_update_post(array('ID' => $post_id, 'post_title' => $name));
    add_action('save_post', 'tm_save_board_member_meta');
}
add_action('save_post', 'tm_save_board_member_meta');

// Customize admin columns for Board Members
function tm_board_member_columns($columns) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'picture' => 'Media',
        'name' => 'Name',
        'position' => 'Position',
        'date' => 'Date'
    );
    return $columns;
}
add_filter('manage_board_member_posts_columns', 'tm_board_member_columns');

// Populate custom columns
function tm_board_member_custom_column($column, $post_id) {
    if ($column === 'position') {
        echo esc_html(get_post_meta($post_id, '_tm_position', true));
    } elseif ($column === 'name') {
        echo esc_html(get_post_meta($post_id, '_tm_name', true));
    } elseif ($column === 'picture') {
        $media_urls = get_post_meta($post_id, '_tm_media_urls', true);
        $urls = array_filter(array_map('trim', explode(',', $media_urls)));
        foreach ($urls as $url) {
            if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $url)) {
                echo '<img src="' . esc_url($url) . '" style="max-width:50px; height:auto; margin-right:5px;" />';
            } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $url)) {
                echo '<span class="dashicons dashicons-format-video" title="Video file" style="font-size:20px; margin-right:5px;"></span>';
            }
        }
    }
}
add_action('manage_board_member_posts_custom_column', 'tm_board_member_custom_column', 10, 2);

// Make columns sortable if needed
function tm_board_member_sortable_columns($columns) {
    $columns['name'] = 'title';
    return $columns;
}
add_filter('manage_edit-board_member_sortable_columns', 'tm_board_member_sortable_columns');
