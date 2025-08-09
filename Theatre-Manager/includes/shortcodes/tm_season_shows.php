<?php
function tm_season_shows_shortcode($atts) {

    $atts = shortcode_atts(['season_id' => 0], $atts);
	if ($atts['season_id']) {
	    $seasons = get_posts([
        'post_type' => 'season',
        'numberposts' => -1,
        'p' => $atts['season_id']
    	]);


    //	$seasons = get_posts(['post_type' => 'season', 'numberposts' => -1]);		
	} else {
    	$seasons = get_posts(['post_type' => 'season', 'numberposts' => -1]);
	}
//    $bg_color = get_option('tm_show_bg_color', '#ffffff');
    $text_color = get_option('tm_show_text_color', '#000000');
	$border_color = get_option('tm_show_border_color', '#000000');
    $border_width = get_option('tm_show_border_width', '0');
    $rounded = get_option('tm_show_rounded') ? 'true' : 'false';
    $border_radius = get_option('tm_show_radius', '20');
    $shadow = get_option('tm_show_shadow') ? 'true' : 'false';
	
	$style = 'color:' . $text_color . '; ; border-width: ' . $border_width . ';border-color: ' . $border_color . '; border-radius: ' . $border_radius . '; border-style: solid;';
	if ($shadow === true) {
		$style = $style . ' box-shadow: 5px 10px;';
	}
	$style = $style .  '}';

    $output = '<div style="' . $style . '" class="tm-season-shows">';

    $slot_order = ['Fall', 'Winter', 'Spring'];

    foreach ($seasons as $season) {
        $output .= '<h2>' . esc_html($season->post_title) . '</h2>';

        $shows = get_posts([
            'post_type' => 'show',
            'numberposts' => -1,
            'meta_query' => [
                [
                    'key' => '_tm_show_season',
                    'value' => $season->ID,
                    'compare' => '='
                ]
            ]
        ]);

        $grouped = [];
        foreach ($shows as $show) {
            $slot = get_post_meta($show->ID, '_tm_show_time_slot', true);
            if (!isset($grouped[$slot])) {
                $grouped[$slot] = [];
            }
            $grouped[$slot][] = $show;
        }
            $output .= '<div class="tm-show-grid">'; // Start grid container
        foreach ($slot_order as $slot) {
            if (!isset($grouped[$slot])) continue;


            foreach ($grouped[$slot] as $show) {
                $img = get_post_meta($show->ID, '_tm_show_sm_image', true);
                $author = get_post_meta($show->ID, '_tm_show_author', true);
                $sub_authors = get_post_meta($show->ID, '_tm_show_sub_authors', true);
                $director = get_post_meta($show->ID, '_tm_show_director', true);
                $associate_director = get_post_meta($show->ID, '_tm_show_associate_director', true);

                $output .= '<div class="tm-show-card">';
            $output .= '<h3 style="color:' . esc_html($text_color) .';">' . esc_html($slot) . ' Show</h3>';
                if ($img) {
                    $output .= '<img src="' . esc_url($img) . '" alt="' . esc_attr($show->post_title) . '" class="tm-show-image">';
                }
                $output .= '<h4 style="color:' . esc_html($text_color) .';">' . esc_html($show->post_title) . '</h4>';

                if ($author) {
                    $output .= '<p><strong>Author:</strong> ' . esc_html($author) . '</p>';
                }
                if ($sub_authors) {
                    $output .= '<p><strong>Sub-author(s):</strong> ' . esc_html($sub_authors) . '</p>';
                }
                if ($director) {
                    $output .= '<p><strong>Director:</strong> ' . esc_html($director) . '</p>';
                }
                if ($associate_director) {
                    $output .= '<p><strong>Associate Director:</strong> ' . esc_html($associate_director) . '</p>';
                }

                $output .= '</div>'; // End show card
            }

        }
            $output .= '</div>'; // End grid container

    }

    $output .= '</div>';
    return $output;
}
add_shortcode('tm_season_shows', 'tm_season_shows_shortcode');

?>