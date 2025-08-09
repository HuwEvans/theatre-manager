<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Register Show Custom Post Type
 */
function tm_register_show_cpt() {
    $labels = array(
        'name' => 'Shows',
        'singular_name' => 'Show',
        'menu_name' => 'Shows',
        'name_admin_bar' => 'Show',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Show',
        'new_item' => 'New Show',
        'edit_item' => 'Edit Show',
        'view_item' => 'View Show',
        'all_items' => 'All Shows',
        'search_items' => 'Search Shows',
        'not_found' => 'No shows found.',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-tickets-alt',
        'supports' => array(''),
        'has_archive' => true,
        'show_in_menu' => 'theatre-manager',
    );

    register_post_type('show', $args);
}
add_action('init', 'tm_register_show_cpt');

/**
 * Add Meta Boxes for Show
 */
function tm_add_show_meta_boxes() {
    add_meta_box('tm_show_details', 'Show Details', 'tm_render_show_meta_box', 'show', 'normal', 'default');
}
add_action('add_meta_boxes', 'tm_add_show_meta_boxes');

/**
 * Render Show Meta Box
 */
function tm_render_show_meta_box($post) {
    wp_nonce_field('tm_save_show_meta', 'tm_show_meta_nonce');

    $fields = [
        'name' => '',
        'author' => '',
        'sub_authors' => '',
        'synopsis' => '',
        'genre' => '',
        'director' => '',
        'associate_director' => '',
        'time_slot' => '',
        'show_dates' => '',
        'season' => '',
        'sm_image' => ''
    ];

    foreach ($fields as $key => $default) {
        $fields[$key] = get_post_meta($post->ID, '_tm_show_' . $key, true);
    }

    $genres = ['Comedy', 'Farce', 'Mystery', 'Drama', 'Musical'];
    $slots = ['Fall', 'Winter', 'Spring'];
    $seasons = get_posts(['post_type' => 'season', 'numberposts' => -1]);

    echo '<p><label>Name:<br><input type="text" name="tm_show_name" value="' . esc_attr($fields['name']) . '" class="widefat" /></label></p>';
    echo '<p><label>Author:<br><input type="text" name="tm_show_author" value="' . esc_attr($fields['author']) . '" class="widefat" /></label></p>';
    echo '<p><label>Sub-authors:<br><input type="text" name="tm_show_sub_authors" value="' . esc_attr($fields['sub_authors']) . '" class="widefat" /></label></p>';
    echo '<p><label>Synopsis:<br><textarea name="tm_show_synopsis" class="widefat">' . esc_textarea($fields['synopsis']) . '</textarea></label></p>';

    echo '<p><label>Genre:<br><select name="tm_show_genre">';
    foreach ($genres as $genre) {
        echo '<option value="' . esc_attr($genre) . '" ' . selected($fields['genre'], $genre, false) . '>' . esc_html($genre) . '</option>';
    }
    echo '</select></label></p>';

    echo '<p><label>Director:<br><input type="text" name="tm_show_director" value="' . esc_attr($fields['director']) . '" class="widefat" /></label></p>';
    echo '<p><label>Associate Director:<br><input type="text" name="tm_show_associate_director" value="' . esc_attr($fields['associate_director']) . '" class="widefat" /></label></p>';

    echo '<p><label>Time Slot:<br><select name="tm_show_time_slot">';
    foreach ($slots as $slot) {
        echo '<option value="' . esc_attr($slot) . '" ' . selected($fields['time_slot'], $slot, false) . '>' . esc_html($slot) . '</option>';
    }
    echo '</select></label></p>';

    echo '<p><label>Show Dates:<br><textarea name="tm_show_show_dates" class="widefat">' . esc_textarea($fields['show_dates']) . '</textarea></label></p>';

    echo '<p><label>Season:<br><select name="tm_show_season">';
    foreach ($seasons as $season) {
        echo '<option value="' . esc_attr($season->ID) . '" ' . selected($fields['season'], $season->ID, false) . '>' . esc_html($season->post_title) . '</option>';
    }
    echo '</select></label></p>';

	echo '<p><label>SM Image:<br>';
	echo '<input type="text" name="tm_show_sm_image" id="tm_show_sm_image" value="' . esc_attr($fields['sm_image']) . '" class="widefat" />';
	echo '<button type="button" class="button tm-media-button" data-target="tm_show_sm_image" data-preview="tm_show_sm_image_preview">Select Image</button>';
	echo '</label></p>';
	echo '<div><img id="tm_show_sm_image_preview" src="' . esc_url($fields['sm_image']) . '" style="max-width:150px;' . ($fields['sm_image'] ? '' : ' display:none;') . '" /></div>';
}
/**
 * Save Show Meta
 */
function tm_save_show_meta($post_id) {
    if (!isset($_POST['tm_show_meta_nonce']) || !wp_verify_nonce($_POST['tm_show_meta_nonce'], 'tm_save_show_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ('show' !== $_POST['post_type'] || !current_user_can('edit_post', $post_id)) return;

    $fields = [
        'name', 'author', 'sub_authors', 'synopsis',
        'genre', 'director', 'associate_director',
        'time_slot', 'show_dates', 'season', 'sm_image'
    ];

    foreach ($fields as $field) {
        if (isset($_POST['tm_show_' . $field])) {
            update_post_meta($post_id, '_tm_show_' . $field, sanitize_text_field($_POST['tm_show_' . $field]));
        }
    }

    // Auto-fill post title from Name field
    remove_action('save_post', 'tm_save_show_meta');
    wp_update_post([
        'ID' => $post_id,
        'post_title' => sanitize_text_field($_POST['tm_show_name'])
    ]);
    add_action('save_post', 'tm_save_show_meta');
}
add_action('save_post', 'tm_save_show_meta');
/**
 * Customize Show List Columns
 */
function tm_show_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Show Name',
        'genre' => 'Genre',
        'time_slot' => 'Time Slot',
        'season' => 'Season',
        'sm_image' => 'Image',
		'post_id' => 'ID'
    );
}
add_filter('manage_show_posts_columns', 'tm_show_columns');

/**
 * Render Custom Columns
 */
function tm_show_custom_column($column, $post_id) {
    switch ($column) {
        case 'genre':
            echo esc_html(get_post_meta($post_id, '_tm_show_genre', true));
            break;
        case 'time_slot':
            echo esc_html(get_post_meta($post_id, '_tm_show_time_slot', true));
            break;
        case 'season':
            $season_id = get_post_meta($post_id, '_tm_show_season', true);
            echo $season_id ? get_the_title($season_id) : '';
            break;
        case 'sm_image':
            $img = get_post_meta($post_id, '_tm_show_sm_image', true);
            if ($img) echo '<img src="' . esc_url($img) . '" style="max-width:50px;" />';
            break;
		case 'post_id':
			echo $post_id;
			break;
    }
}
add_action('manage_show_posts_custom_column', 'tm_show_custom_column', 10, 2);
