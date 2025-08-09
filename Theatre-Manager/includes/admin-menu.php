<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Register Theatre Manager menu and Display Options submenu
 */
function tm_register_plugin_menu() {
    add_menu_page(
        'Theatre Manager',
        'Theatre Manager',
        'manage_options',
        'theatre-manager',
        'tm_display_options_page',
        'dashicons-admin-multisite',
        20
    );

    add_submenu_page(
        'theatre-manager',
        'Display Options',
        'Display Options',
        'manage_options',
        'tm-display-options',
        'tm_display_options_page'
    );
	
	add_submenu_page(
		'theatre-manager',
		'Instructions',
		'Instructions',
		'manage_options',
		'tm_instructions',
		'tm_instructions_page'
	);
}
add_action('admin_menu', 'tm_register_plugin_menu');

/**
 * Display Options Page with Tabs
 */
function tm_display_options_page() {
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'board_member';

    echo '<div class="wrap">';
    echo '<h1>Display Options</h1>';
    echo '<h2 class="nav-tab-wrapper">';
    $tabs = ['board_member', 'advertiser', 'sponsor', 'contributor', 'testimonials', 'season', 'show', 'cast'];
    foreach ($tabs as $tab) {
        $label = ucfirst(str_replace('_', ' ', $tab));
        $active = ($active_tab === $tab) ? 'nav-tab-active' : '';
        echo "<a href=\"?page=tm-display-options&tab={$tab}\" class=\"nav-tab {$active}\">{$label}</a>";
    }
    echo '</h2>';

    echo '<form method="post" action="options.php">';
    settings_fields('tm_display_options_' . $active_tab);
    do_settings_sections('tm-display-options-' . $active_tab);
    submit_button();
    echo '</form>';
    echo '</div>';
}

/**
 * Register settings for each tab
 */
function tm_register_display_settings() {
    $tabs = ['board_member', 'advertiser', 'sponsor', 'contributor', 'testimonials', 'season', 'show', 'cast'];
    foreach ($tabs as $tab) {
        $section_id = "tm_{$tab}_section";
        $page = "tm-display-options-{$tab}";
        $group = "tm_display_options_{$tab}";

        add_settings_section($section_id, ucfirst(str_replace('_', ' ', $tab)) . ' Display Settings', null, $page);

        add_settings_field("tm_{$tab}_bg_color", 'Background Color', 'tm_color_picker_callback', $page, $section_id, ['label_for' => "tm_{$tab}_bg_color"]);
        register_setting($group, "tm_{$tab}_bg_color");

        add_settings_field("tm_{$tab}_text_color", 'Text Color', 'tm_color_picker_callback', $page, $section_id, ['label_for' => "tm_{$tab}_text_color"]);
        register_setting($group, "tm_{$tab}_text_color");

        add_settings_field("tm_{$tab}_border_color", 'Border Color', 'tm_color_picker_callback', $page, $section_id, ['label_for' => "tm_{$tab}_border_color"]);
        register_setting($group, "tm_{$tab}_border_color");

        add_settings_field("tm_{$tab}_border_width", 'Border Width', 'tm_text_input_callback', $page, $section_id, ['label_for' => "tm_{$tab}_border_width"]);
        register_setting($group, "tm_{$tab}_border_width");

        add_settings_field("tm_{$tab}_rounded", 'Rounded Corners', 'tm_checkbox_callback', $page, $section_id, ['label_for' => "tm_{$tab}_rounded"]);
        register_setting($group, "tm_{$tab}_rounded");

        add_settings_field("tm_{$tab}_radius", 'Border Radius', 'tm_text_input_callback', $page, $section_id, ['label_for' => "tm_{$tab}_radius"]);
        register_setting($group, "tm_{$tab}_radius");

        add_settings_field("tm_{$tab}_shadow", 'Border Shadow', 'tm_checkbox_callback', $page, $section_id, ['label_for' => "tm_{$tab}_shadow"]);
        register_setting($group, "tm_{$tab}_shadow");

        if ($tab === 'testimonials') {
            add_settings_field("tm_{$tab}_rating_symbol", 'Rating Symbol', 'tm_rating_symbol_callback', $page, $section_id, ['label_for' => "tm_{$tab}_rating_symbol"]);
            register_setting($group, "tm_{$tab}_rating_symbol");
        }
		
		if ($tab === 'board_member' || $tab === 'advertiser') {
			add_settings_field("tm_{$tab}_grid_columns", 'Grid Columns', 'tm_grid_columns_callback', $page, $section_id, ['label_for' => "tm_{$tab}_grid_columns"]);
			register_setting($group, "tm_{$tab}_grid_columns");
		}
    }
}
add_action('admin_init', 'tm_register_display_settings');

/**
 * Callback functions
 */
function tm_color_picker_callback($args) {
    $option = get_option($args['label_for']);
    echo '<input type="text" class="tm-color-picker" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" value="' . esc_attr($option) . '" />';
}

function tm_text_input_callback($args) {
    $option = get_option($args['label_for']);
    echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" value="' . esc_attr($option) . '" />';
}

function tm_checkbox_callback($args) {
    $option = get_option($args['label_for']);
    echo '<input type="checkbox" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" value="1"' . checked(1, $option, false) . ' />';
}

function tm_rating_symbol_callback($args) {
    $option = get_option($args['label_for']);
    $symbols = ['Stars', 'Thumbs Up', 'Rockets', 'Hearts', 'Theatre Masks'];
    echo '<select id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '">';
    foreach ($symbols as $symbol) {
        echo '<option value="' . esc_attr($symbol) . '"' . selected($option, $symbol, false) . '>' . esc_html($symbol) . '</option>';
    }
    echo '</select>';
}
function tm_grid_columns_callback($args) {
	$option = get_option($args['label_for']);
	$numbers = ['1','2','3','4','5','6'];
	echo '<select id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '">';
	foreach ($numbers as $number) {
		echo '<option value="' . esc_attr($number) . '"' . selected($option, $number, false) . '>' . esc_html($number) . '</option>';
	}
	echo '</select>';
}

function tm_instructions_page() {
	echo "<h1>Theatre Manager Instructions</h1>";
	echo "# Theatre Manager Wordpress Plugin<br/>
This plugin is designed to work with Wordpress and wsa developed to help theatre groups manage important objects<br/>
on their websites.  It was designed after serveral years of maintaining a website for this purpose, and combines<br/>
simpliying the management of common changes that are required, and making it simple for non-technical people to <br/>
update the information.  Once it is up and running, it should be very sraight forward to make changes and have <br/>
these reflected on the appropriate pages with very little technical know how.<br/>
<br/>
The plugin has 2 main aspects.  The Custom Post Types (CPTs) and the shortcodes to go along with the CPTs.  This<br/>
combination of tools should allow someone to easily inplement these on a wordpress website, and provide a modcum<br/>
of control over how the elements are displayed.<br/>
<br/>
## The Custom Post Types<br/>
### Advertisers<br/>
	This CPT allows for entering advertisers that have paid the group to help prmot there business.  It will keep <br/>
	track of the name, logo, banner and website of each advertiser.  There is also a flag to designate an advertiser<br/>
	as a restaurant.  This was done to allow for the ability to create a 'Diner's Guide'.  Helping to promote<br/>
	local restaurants that patrons might what to frequent before or after a show.  It allows the advertisers to <br/>
	supply a link to a promotion that they can offer to people who get to them via advertising with the group.<br/>
### Board Members<br/>
	This CPT tracks information about the board members or creative team for the group.  It has the names and <br/>
	positions of each board member, and allows for a picture to be added for each board member.<br/>
### Contributors<br/>
	This CPT track infomation about donors to the group and provides a means of acknowlegding them on the website. <br/>
	It provides an easy way to manage the donors and see them in one place.<br/>
### Sponsors<br/>
	This CPT provides a means of tracking sponsors to the group.  This will allow for generating a of people and <br/>
	organizations that have taken part in the sponsorship program with the group.<br/>
### Testimonials<br/>
	This CPT allows for tracking of testimonials that have been provided by indivuals who have seen the shows and<br/>
	have gone out of there way to provide feedback.<br/>
### Seasons -> Shows -> Casts<br/>
	These CPTs are probable the most ussful of all the CPTs.  It provides a means to easily track the information <br/>
	about a season, including the shows and cast members for each show.  When maintaining this information for the<br/>
	group that I was a part of, it was the area that required the most updating, and had several components that <br/>
	were difficult to maintain.  Providing these CPTs, I wanted to provide an easy way to link all of this data <br/>
	together for easy display, and provide a simple way see and modify the data.<br/>
<br/>
## Display Options<br/>
This section of the plugin admin area will help with customizing the way that the shortcodes display the CPTs. <br/>
Primarily, you will be able to control the background color, the text color, the border color and width, whether<br/>
or not there are rounded corners, and then some other specifics to the individual CPT.  Please note that not all<br/>
settings apply to all shortcode display types.  For instance the slider types typically do not use any cusomizations<br/>
from the display options.<br/>
<br/>
## The Shortcodes<br/>
### Advertisers<br/>
#### [tm_advertisers]<br/>
This short code will help you display the advertisers CPTs in multiple ways.  As part of our advertising process<br/>
we included for the price of advertising inclusion in our diners guide for restaurants.  This is the one filter <br/>
that I have added to the advertiser shortcode to simply the creation of the diners guide. Images will be linked <br/>
with the website provided in the CPT.<br/>
<br/>
Options:<br/>
	view:  slider or grid (default is grid)<br/>
	category: restaurant (default is all)<br/>
	image_type: banner or logo (default is banner) - Only used for slider view<br/>
	columns:  1-6 (default is 3) - Only used for grid view<br/>
	<br/>
Example:<br/>
	[tm_advertisers view=\"slider\" category=\"restaurant\"]<br/>
	This will show only the restaurants in a slider using the banner image<br/>
	[tm_advertisers]<br/>
	This will use the defaults, and show a grid of all advertisers in a 3 column format.<br/>
<br/>
### Board Members<br/>
#### [tm_board_members]<br/>
This short code will display the CPT for board members on any page or post that it is placed on.  It will show the<br/>
members in a grid with the defined number of columns.  The display options will help with determining how the members<br/>
are displayed.<br/>
<br/>
Options:<br/>
	show_photos: true/false (default true)<br/>
	columns: 1-6 (default 3, configurable in the display options)<br/>
Example:<br/>
	[tm_board_members]<br/>
	Shows the board memebers in a 3 column grid with photos<br/>
	[tm_board_members show_photos=\"false\" columns=\"4\"]<br/>
	Shows the board members in a 4 column grid without photos.<br/>
	<br/>
### Contributors<br/>
#### [tm_contributors]<br/>
This shortcode will display the contributors CPT data in a tiered display.  There are no options available for this<br/>
shortcode, however some control over the display options is available in the admin panel as mentioned above.<br/>
<br/>
Options:  None<br/>
	No options are available, but some control over how the items are displayed is available in the Display Options.<br/>
Example:<br/>
	[tm_contributors]<br/>
	Show the contributors in a tiered list.  Platinum one column, gold 2 column, silver 3 column, bronze 4 column.<br/>
	<br/>
### Sponsors<br/>
#### [tm_sponsors]<br/>
This is one of two shortcodes for this CPT.  This will show the sponsors in a tiered grid similar to the contributors.<br/>
The options will allow you to control which peices of information are included in the display cards, and additional<br/>
control of the display options is available in the admin menu.<br/>
<br/>
Options:<br/>
        show_name: true/false (default true) toggle the display of individual names<br/>
        show_company: true/false (default true) toggle the display of company names<br/>
        show_logo: true/false (default true) if set to false will show banner images<br/>
        show_website: true/false (default true) show text of the website - image links will no be affected<br/>
				<br/>
Example:<br/>
	[tm_sponsors]<br/>
	This will use the defaults and show all fields as part of the display.  This will show the tiered grid version of<br/>
	the logos provided, linked to the website provided, and show the individuals name, the company and the website.  The<br/>
	tiered display will be Platinum one column, gold 2 column, silver 3 column, bronze 4 column.<br/>
	[tm_sponsors show_name=\"true\" show_company=\"false\" show_logo=\"true\" show_website=\"false\"]<br/>
	This will show the tiered display, but will only show the individuals name, and the logo (linked to the website).<br/>
<br/>
#### [tm_sponsor_slider]<br/>
This shortcode is designed to provide a slider of the banners supplied for all sponsors.  This was meant to provide a tool<br/>
to put a vertical banner<br/>
<br/>
Options:	None<br/>
	No Options are available and no display options will affect this shortcode.  This shortcode is designed to display<br/>
	banners in a slider.<br/>
	<br/>
Example:<br/>
	[tm_sponsor_slider]<br/>
	Will show the banner images in a slider.<br/>
	<br/>
### Testimonials<br/>
#### [tm_testimonials]<br/>
This shortcode will display a slider of testimonials.  The only options for this shortcode will be in the display options <br/>
in the admin panel.<br/>
<br/>
Options:	None<br/>
	No options are avialable to configure the shortcode itself, howver there are many display options in the admin panel <br/>
	that will allow for customizations of the testimonial slider.<br/>
<br/>
Example:<br/>
	[tm_testimonials]<br/>
	This will show the testimonial slider with the display options customizations<br/>
	<br/>
### Seasons -> Shows -> Casts<br/>
#### [tm_show_cast]<br/>
This shortcode will display the cast associated with a specific show.  It will show all fields and will not be contain a<br/>
lot of formatting.  It will include the pictures and all fields, but no show information.<br/>
<br/>
Options:<br/>
	show_id: <number> (required) id of show to display.<br/>
	<br/>
Example:<br/>
	[tm_show_cast show_id=\"177\"]<br/>
	This displays information pertining to show with post ID 177.<br/>
	<br/>
#### [tm_season_banner]<br/>
This shortcode will show the Social Media banner for the season specified.<br/>
<br/>
Options:<br/>
	season_id: <number>  (required)  ID of season to show information about.<br/>
<br/>
Example:<br/>
	[tm_season_banner season_id=\"177\"]<br/>
<br/>
#### [tm_season_cast]<br/>
This shortcode will show all of the shows for a season, along with the show details, and include the cast associated with<br/>
with the show.  There are options on how the data will be displayed that can be used to modify the shortcode behaviour.  The<br/>
Shows will be displayed sorted by time slot.  (Fall, Winter, Spring)<br/>
<br/>
Options:<br/>
        season_id: <number> (required)  Use the ID number of the season you want to display<br/>
        show_cast_images:  true/false  (default true) Use to toggle cast pictures<br/>
        cast_layout: grid/list (default grid) Use to determine the layout of the cast<br/>
<br/>
Example:<br/>
	[tm_season_cast seasonid=\"177\" show_cast_images=false cast_layout=\"grid\"]<br/>
	This example will display the shows for season with the ID of 177.  It will show the details of any field with data in<br/>
	it and will show the cast in a grid with no pictures.<br/>
<br/>
#### [tm_season_images]<br/>
This shortcode will display all three images for the given season.  It will put the Social Media banner on the top, and the<br/>
3-up (or flyer images) underneath next to each other.<br/>
<br/>
Options:<br/>
	season_id: <number> (required)  Use this to determine which season details will be shown<br/>
	<br/>
Example:<br/>
	[tm_season_images seasonid=\"177\"]<br/>
	This example will show the season images for season with the ID 177.<br/>
	<br/>
#### [tm_season_shows]<br/>
<br/>
Options:	<br/>
	season_id:  <number>   Use this to display a single season.  Default is to show all seasons.<br/>
<br/>
Example:<br/>
	[tm_season_shows]<br/>
	This example will display each season and the associated show summaries in a 3 column grid.<br/>
	[tm_season_shows season_id=\"177\"]<br/>
	This example will display the shows for the seaosn with ID 177 in a trhee olumn grid.<br/>
<br/>
#### [tm_cast]<br/>
This shortcode will display all cast posts.  Very little formatting is done, and there are currenly no filtering options for<br/>
this shortcode <br/>
<br/>
Options:<br/>
	exclude: 'actor_name', 'picture', 'show'<br/>
<br/>
Example:<br/>
	[tm_cast exclude=\"actor_name\"]<br/>
	By default, all fields are shown. Use 'exclude' to hide specific fields.  This sample will hide the actor_name field<br/>
<br/>
#### [tm_seasons]<br/>
This shortcode shows all season data unformatted.  The exclude options allows you to hide fields.<br/>
<br/>
Options:<br/>
	exclude: 'image_front', 'image_back', 'social_banner', 'start_date', 'end_date'<br/>
<br/>
Example:<br/>
	Usage: [tm_seasons exclude=\"start_date,end_date\"]<br/>
	By default, all fields are shown. Use 'exclude' to hide specific fields.  This sample will hide the start and end dates<br/>
<br/>
#### [tm_shows]<br/>
This shortcode will show the details of all shows.  Fields can be excluded by using the exclude option<br/>
<br/>
Options:<br/>
	exclude: 'author', 'sub_authors', 'synopsis', 'genre', 'director', 'associate_director', 'time_slot', 'show_dates'<br/>
<br/>
Example:<br/>
	[tm_shows exclude=\"genre,director\"]<br/>
	By default, all fields are shown. Use 'exclude' to hide specific fields.  This sample will hide the director and genre fields<br/>
";
}
?>
