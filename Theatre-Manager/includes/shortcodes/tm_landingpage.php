<?php
/**
 * Landing Page Shortcode
 * Displays detailed information about a specific show
 * 
 * Usage:
 * [tm_landingpage show_id="123"]
 * [tm_landingpage show_id="current"]
 * [tm_landingpage show_id="123" field_list="show_name,show_image,author,director,synopsis,cast,ticket_url"]
 * 
 * Available Fields:
 * - show_name: The show title
 * - show_image: The show's main image
 * - author: Show author
 * - sub_authors: Additional authors
 * - director: Director name
 * - associate_director: Associate director name
 * - producer: Producer name
 * - stage_manager: Stage manager name
 * - synopsis: Show synopsis/description
 * - show_dates: Show performance dates and times
 * - ticket_url: Link to purchase tickets
 * - cast: Cast member information (character name, actor name, bio)
 * - venue: Venue information
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get the current show (today's date falls within show dates)
 */
function tm_get_current_show() {
    $today_ts = strtotime(date('Y-m-d'));
    $shows = get_posts([
        'post_type' => 'show',
        'posts_per_page' => -1
    ]);

    foreach ($shows as $show) {
        $season_id = get_post_meta($show->ID, '_tm_show_season', true);
        if (!$season_id) continue;

        $season = get_post($season_id);
        if (!$season) continue;

        $start_raw = get_post_meta($season_id, '_tm_season_start_date', true);
        $end_raw = get_post_meta($season_id, '_tm_season_end_date', true);

        $start_ts = $start_raw ? strtotime($start_raw) : 0;
        $end_ts = $end_raw ? strtotime($end_raw) : 0;

        if (!empty($start_ts) && !empty($end_ts)) {
            if ($today_ts > $start_ts && $today_ts < $end_ts) {
                return $show->ID;
            }
        }
    }

    return null;
}

/**
 * Render field output based on field name
 */
function tm_render_landingpage_field($show_id, $field_name, $hard_breaks = true, $atts = array()) {
    $output = '';
    
    switch ($field_name) {
        case 'show_name':
            $title = get_the_title($show_id);
            if ($title) {
                $output .= esc_html($title);
            }
            break;

        case 'show_image':
            // Images always output as HTML regardless of hard_breaks setting
            $image_value = get_post_meta($show_id, '_tm_show_sm_image', true);
            if ($image_value) {
                $image_url = tm_get_image_url($image_value);
                if ($image_url) {
                    $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title($show_id)) . '" />';
                }
            }
            break;

        case 'author':
            $value = get_post_meta($show_id, '_tm_show_author', true);
            if ($value) {
                $output .= esc_html($value);
            }
            break;

        case 'sub_authors':
            $value = get_post_meta($show_id, '_tm_show_sub_authors', true);
            if ($value) {
                $output .= esc_html($value);
            }
            break;

        case 'director':
            $value = get_post_meta($show_id, '_tm_show_director', true);
            if ($value) {
                $output .= esc_html($value);
            }
            break;

        case 'associate_director':
            $value = get_post_meta($show_id, '_tm_show_associate_director', true);
            if ($value) {
                $output .= esc_html($value);
            }
            break;

        case 'producer':
            $value = get_post_meta($show_id, '_tm_show_producer', true);
            if ($value) {
                $output .= esc_html($value);
            }
            break;

        case 'stage_manager':
            $value = get_post_meta($show_id, '_tm_show_stage_manager', true);
            if ($value) {
                $output .= esc_html($value);
            }
            break;

        case 'synopsis':
            $value = get_post_meta($show_id, '_tm_show_synopsis', true);
            if ($value) {
                if ($hard_breaks) {
                    $output .= wp_kses_post($value);
                } else {
                    // Strip tags for plain text when hard_breaks is false
                    $output .= esc_html(wp_strip_all_tags($value));
                }
            }
            break;

        case 'show_dates':
            $value = get_post_meta($show_id, '_tm_show_show_dates', true);
            if ($value) {
                if ($hard_breaks) {
                    $output .= wp_kses_post($value);
                } else {
                    // Strip tags for plain text when hard_breaks is false
                    $output .= esc_html(wp_strip_all_tags($value));
                }
            }
            break;

        case 'ticket_url':
            $url = get_post_meta($show_id, '_tm_show_tickets_url', true);
            if ($url) {
                $use_button = strtolower($atts['urlbutton']) === 'true' || $atts['urlbutton'] === '1';
                if ($use_button && $hard_breaks) {
                    // Display as a button
                    $buttonformat = sanitize_key($atts['buttonformat']);
                    $button_class = 'tm-url-button tm-button-' . esc_attr($buttonformat);
                    $output .= '<a href="' . esc_url($url) . '" class="' . $button_class . '" target="_blank">Get Tickets</a>';
                } else {
                    // Display as regular link
                    if ($hard_breaks) {
                        $output .= '<a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a>';
                    } else {
                        // Just the URL text when hard_breaks is false
                        $output .= esc_html($url);
                    }
                }
            }
            break;

        case 'cast':
            // Get all cast members for this show
            $cast_args = [
                'post_type' => 'cast',
                'posts_per_page' => -1,
                'meta_query' => [
                    [
                        'key' => '_tm_cast_show',
                        'value' => $show_id,
                        'compare' => '='
                    ]
                ]
            ];
            $cast_members = get_posts($cast_args);

            if ($cast_members) {
                if ($hard_breaks) {
                    // Formatted HTML output with list
                    $output .= '<ul class="tm-landingpage-cast-list">';
                    foreach ($cast_members as $cast_member) {
                        $output .= '<li class="tm-landingpage-cast-item">';
                        
                        $character = get_post_meta($cast_member->ID, '_tm_cast_character_name', true);
                        $actor = get_post_meta($cast_member->ID, '_tm_cast_actor_name', true);
                        $bio = get_the_excerpt($cast_member->ID);
                        
                        if ($character) {
                            $output .= '<strong>' . esc_html($character) . '</strong>';
                            if ($actor) {
                                $output .= ' — ' . esc_html($actor);
                            }
                        } elseif ($actor) {
                            $output .= esc_html($actor);
                        }
                        
                        if ($bio) {
                            $output .= '<br>' . wp_kses_post($bio);
                        }
                        
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                } else {
                    // Plain text output when hard_breaks is false
                    $cast_texts = [];
                    foreach ($cast_members as $cast_member) {
                        $character = get_post_meta($cast_member->ID, '_tm_cast_character_name', true);
                        $actor = get_post_meta($cast_member->ID, '_tm_cast_actor_name', true);
                        
                        if ($character && $actor) {
                            $cast_texts[] = esc_html($character) . ' - ' . esc_html($actor);
                        } elseif ($character) {
                            $cast_texts[] = esc_html($character);
                        } elseif ($actor) {
                            $cast_texts[] = esc_html($actor);
                        }
                    }
                    $output .= implode(', ', $cast_texts);
                }
            }
            break;

        case 'castwithbio':
            // Get all cast members for this show with bio in responsive columns
            $cast_args = [
                'post_type' => 'cast',
                'posts_per_page' => -1,
                'meta_query' => [
                    [
                        'key' => '_tm_cast_show',
                        'value' => $show_id,
                        'compare' => '='
                    ]
                ]
            ];
            $cast_members = get_posts($cast_args);

            if ($cast_members) {
                $castcols = intval($atts['castcols']);
                if ($castcols < 1) $castcols = 3;
                if ($castcols > 6) $castcols = 6;
                $output .= '<div class="tm-landingpage-castwithbio" style="--cast-cols: ' . intval($castcols) . '">';
                
                foreach ($cast_members as $cast_member) {
                    $character = get_post_meta($cast_member->ID, '_tm_cast_character_name', true);
                    $actor = get_post_meta($cast_member->ID, '_tm_cast_actor_name', true);
                    $picture = get_post_meta($cast_member->ID, '_tm_cast_picture', true);
                    $bio = get_post_meta($cast_member->ID, '_tm_cast_bio', true);

                    $output .= '<div class="tm-cast-column">';
                    
                    // Image
                    if ($picture) {
                        $picture_url = tm_get_image_url($picture);
                        if ($picture_url) {
                            $output .= '<img src="' . esc_url($picture_url) . '" alt="' . esc_attr($actor ?: $character) . '" class="tm-cast-image">';
                        }
                    }
                    
                    // Character Name
                    if ($character) {
                        $output .= '<h4 class="tm-cast-character">' . esc_html($character) . '</h4>';
                    }
                    
                    // Actor Name
                    if ($actor) {
                        $output .= '<p class="tm-cast-actor">Played by ' . esc_html($actor) . '</p>';
                    }
                    
                    // Bio
                    if ($bio) {
                        $output .= '<p class="tm-cast-bio">' . wp_kses_post($bio) . '</p>';
                    }
                    
                    $output .= '</div>';
                }
                
                $output .= '</div>';
            }
            break;

        case 'venue':
            $venue_id = get_post_meta($show_id, '_tm_show_venue', true);
            if ($venue_id) {
                $venue = get_post($venue_id);
                if ($venue) {
                    if ($hard_breaks) {
                        // Formatted HTML output with div
                        $output .= '<div class="tm-landingpage-venue">';
                        $output .= '<strong>' . esc_html($venue->post_title) . '</strong>';
                        
                        $address = get_post_meta($venue_id, '_tm_venue_address', true);
                        if ($address) {
                            $output .= '<br>' . wp_kses_post($address);
                        }
                        
                        $phone = get_post_meta($venue_id, '_tm_venue_phone', true);
                        if ($phone) {
                            $output .= '<br>' . esc_html($phone);
                        }
                        
                        $website = get_post_meta($venue_id, '_tm_venue_website', true);
                        if ($website) {
                            $output .= '<br><a href="' . esc_url($website) . '" target="_blank">' . esc_html($website) . '</a>';
                        }
                        
                        $output .= '</div>';
                    } else {
                        // Plain text output when hard_breaks is false
                        $venue_parts = [esc_html($venue->post_title)];
                        
                        $address = get_post_meta($venue_id, '_tm_venue_address', true);
                        if ($address) {
                            $venue_parts[] = wp_strip_all_tags($address);
                        }
                        
                        $phone = get_post_meta($venue_id, '_tm_venue_phone', true);
                        if ($phone) {
                            $venue_parts[] = esc_html($phone);
                        }
                        
                        $website = get_post_meta($venue_id, '_tm_venue_website', true);
                        if ($website) {
                            $venue_parts[] = esc_html($website);
                        }
                        
                        $output .= implode(' ', $venue_parts);
                    }
                }
            }
            break;
    }

    return $output;
}

/**
 * Shortcode handler
 */
function tm_shortcode_landingpage($atts) {
    $atts = shortcode_atts([
        'show_id' => 'current',
        'field_list' => 'show_name,show_image,author,director,producer,stage_manager,synopsis,show_dates,ticket_url,cast,venue',
        'hard_breaks' => 'true',
        'castcols' => '3',
        'urlbutton' => 'false',
        'buttonformat' => 'default'
    ], $atts, 'tm_landingpage');

    // Determine which show to display
    $show_id = null;
    if (strtolower($atts['show_id']) === 'current') {
        $show_id = tm_get_current_show();
        if (!$show_id) {
            return '<!-- No current show available -->';
        }
    } else {
        $show_id = intval($atts['show_id']);
        if ($show_id <= 0) {
            return '<!-- Invalid show ID -->';
        }
        // Verify the show exists
        $show = get_post($show_id);
        if (!$show || $show->post_type !== 'show') {
            return '<!-- Show not found -->';
        }
    }

    // Parse field list
    $field_list = array_map('trim', explode(',', $atts['field_list']));
    $field_list = array_filter($field_list);

    if (empty($field_list)) {
        return '<!-- No fields specified -->';
    }

    // Parse hard_breaks parameter
    $hard_breaks = strtolower($atts['hard_breaks']) === 'true' || $atts['hard_breaks'] === '1';

    // Render the landing page
    if ($hard_breaks) {
        // With hard breaks: wrap in divs for styling control
        $output = '<div class="tm-landingpage-wrapper tm-landingpage">';

        foreach ($field_list as $field) {
            $field = sanitize_key($field);
            $field_output = tm_render_landingpage_field($show_id, $field, $hard_breaks, $atts);

            if ($field_output) {
                // Images don't need field div wrapper - they're self-contained
                if ($field === 'show_image') {
                    $output .= $field_output;
                } else {
                    $output .= '<div class="tm-landingpage-field tm-landingpage-field-' . esc_attr($field) . '">' . $field_output . '<br></div>';
                }
            }
        }

        $output .= '</div>';
    } else {
        // Without hard breaks: output raw content
        $output = '';
        $has_image = false;
        $image_output = '';
        
        foreach ($field_list as $field) {
            $field = sanitize_key($field);
            $field_output = tm_render_landingpage_field($show_id, $field, $hard_breaks, $atts);

            if ($field_output) {
                // Track image output separately
                if ($field === 'show_image') {
                    $has_image = true;
                    $image_output = $field_output;
                } else {
                    $output .= $field_output;
                }
            }
        }
        
        // Handle image placement based on content
        if ($has_image) {
            if (count($field_list) == 1) {
                // Image only: wrap in inline-block span to stay in text flow
                $output = '<span class="tm-landingpage-image-inline">' . $image_output . '</span>';
            } else {
                // Mixed content: image + text - wrap everything in centered div
                $output = '<div class="tm-landingpage-mixed" style="text-align: center;">' . $image_output . $output . '</div>';
            }
        }
    }

    return $output;
}

// Register the shortcode
add_shortcode('tm_landingpage', 'tm_shortcode_landingpage');