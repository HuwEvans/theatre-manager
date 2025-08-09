<?php
/**
* Shortcode: Board Members Display
* Description: Outputs Board Member entries styled with Display Options.
*/

defined('ABSPATH') || exit;

function tm_board_member_shortcode($atts) {


    $args = array(
        'post_type' => 'board_member',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    $query = new WP_Query($args);

    $bg_color = get_option('tm_board_member_bg_color', '#ffffff');
    $text_color = get_option('tm_board_member_text_color', '#000000');
	$border_color = get_option('tm_board_member_border_color', '#000000');
    $border_width = get_option('tm_board_member_border_width', '0');
    $rounded = get_option('tm_board_member_rounded') ? 'true' : 'false';
    $border_radius = get_option('tm_board_member_radius', '20');
    $shadow = get_option('tm_board_member_shadow') ? 'true' : 'false';
	$grid_columns = get_option('tm_board_member_grid_columns', '1');
    $atts = shortcode_atts([
        'show_photos' => 'true',
        'columns' => $grid_columns,
    ], $atts, 'tm_board_members');
	
    $style = "background-color: {$bg_color}; color: {$text_color}; border: {$border_width}px solid {$border_color}; box-sizing: border-box; padding:15px;";
    if ($rounded === 'true') { $style .= " border-radius: {$border_radius}px;"; }
    if ($shadow === 'true') { $style .= " box-shadow: 0 2px 6px rgba(0,0,0,0.2);"; }

    $columns = max(1, intval($atts['columns']));
    $gap = 20;
    $card_width = "calc((100% - " . ($gap * ($columns - 1)) . "px) / {$columns})";

    echo '<style>
        .tm-board-members-grid {
			box-sizing: border-box;
			grid-template-columns: repeat(' . $columns .', 1fr);
            display: grid;
            flex-wrap: wrap;
            gap: 20px;
        }
        .tm-board-member-card {
            box-sizing: border-box;
            padding: 15px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            flex: 1 1 auto;
        }
		.tm-board-member-card p,
		.tm-board-member-card h4 {
			color: ' . esc_attr($text_color) . ';
			margin: 5px 0;
		}
        .tm-board-member-card img {
            width: 200px;
            height: 200px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .tm-position-bottom {
            margin-top: auto;
        }
        @media (max-width: 767px) {
            .tm-board-member-card {
                flex: 0 0 100% !important;
            }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .tm-board-member-card {
                flex: 0 0 50% !important;
            }
        }
    </style>';

    ob_start();
    if ($query->have_posts()) {
        echo '<div class="tm-board-members-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            $company = get_post_meta(get_the_ID(), '_tm_company', true);
            $position = get_post_meta(get_the_ID(), '_tm_position', true);
            $photo = get_post_meta(get_the_ID(), '_tm_media_urls', true);

            echo '<div class="tm-board-member-card" style="flex: 0 0 ' . $card_width . '; ' . esc_attr($style) . '">';
            
            if ($atts['show_photos'] === 'true' && $photo) {
                echo '<img src="' . esc_url($photo) . '" alt="Photo" />';
            }

            echo '<h4>' . esc_html(get_the_title()) . '</h4>';

            if ($position) {
                echo '<div class="tm-position-bottom"><p><strong></strong> ' . esc_html($position) . '</p></div>';
            }

            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>No board members found.</p>';
    }

    return ob_get_clean();
}
add_shortcode('tm_board_members', 'tm_board_member_shortcode');
?>
