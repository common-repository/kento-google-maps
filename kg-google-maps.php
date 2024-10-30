<?php
/*
Plugin Name: Google Direction Map
Plugin URI: https://www.paypal.com/in/cgi-bin/webscr?cmd=_flow&SESSION=GxDRAlScMgqrm5VDpl59KUqgiYtWecMGrq2qmc0nUfJAM-7cpipC_mYBj5e&dispatch=50a222a57771920b6a3d7b606239e4d529b525e0b7e69bf0224adecfb0124e9b61f737ba21b08198d1a93361f052308ac20c1249d8113f4c
Description: KENTO Google Direction adds Google Maps feature to your website. The simple yet intuitive interface allows to insert a map anywhere on your post or page, in less than a minute.

Version: 1.0
Author: Kento Infotech
Author URI: https://www.paypal.com/in/cgi-bin/webscr?cmd=_flow&SESSION=GxDRAlScMgqrm5VDpl59KUqgiYtWecMGrq2qmc0nUfJAM-7cpipC_mYBj5e&dispatch=50a222a57771920b6a3d7b606239e4d529b525e0b7e69bf0224adecfb0124e9b61f737ba21b08198d1a93361f052308ac20c1249d8113f4c
Text Domain: Kento-Google-Direction
License: GPLv2
*/

if(defined('KENTO_GMAPS_VERSION')) return;	// Looks like another instance is active.

define('KENTO_GMAPS_VERSION', '1.0');
define('KENTO_GMAPS_FULLNAME', 'Google Direction Map');
define('KENTO_GMAPS_SHORTNAME', 'kggmaps');
define('KENTO_GMAPS_INITIALS', 'kggm_');
define('KENTO_GMAPS_TEXTDOMAIN', 'kg-google-maps');
define('KENTO_GMAPS_DESCRIPTION', 'Google Direction adds Google Maps feature to your website. The simple yet intuitive interface allows to insert a map anywhere on your post or page, in less than a minute.
');

// Paths
define('KENTO_GMAPS_PATH', dirname(__FILE__));
define('KENTO_GMAPS_FOLDER', basename(KENTO_GMAPS_PATH));

// URLs
define('KENTO_GMAPS_URL', plugin_dir_url( __FILE__ ));
define('KENTO_GMAPS_IMGURL', KENTO_GMAPS_URL."images");

// Activate
function KENTO_GMAPS_activate() {}
register_activation_hook( __FILE__, 'KENTO_GMAPS_activate' );

// Deactivate
function KENTO_GMAPS_deactivate() {}
register_deactivation_hook( __FILE__, 'KENTO_GMAPS_deactivate' );

function KENTO_GMAPS_enqueue_scripts() {
	wp_enqueue_style(KENTO_GMAPS_SHORTNAME.'-colorbox', KENTO_GMAPS_URL.'colorbox.css');
	wp_enqueue_style(KENTO_GMAPS_SHORTNAME.'-jqueryui', KENTO_GMAPS_URL.'/jquery-ui.min.css');
	wp_enqueue_style(KENTO_GMAPS_SHORTNAME.'-core', KENTO_GMAPS_URL.'kg-google-maps.css');

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');

	wp_enqueue_script(KENTO_GMAPS_SHORTNAME.'-colorbox', KENTO_GMAPS_URL . 'jquery.colorbox-min.js', array('jquery'), null, true);
	wp_enqueue_script(KENTO_GMAPS_SHORTNAME.'-core', KENTO_GMAPS_URL . 'kg-google-maps.js', array('jquery'), '1.0.0', true);

	$img = KENTO_GMAPS_URL . 'kg-google-maps.png';

	wp_localize_script(
		KENTO_GMAPS_SHORTNAME.'-core',
		KENTO_GMAPS_INITIALS.'ajax',
		array(
			'url' => admin_url('admin-ajax.php' ),
			'tag' => KENTO_GMAPS_FULLNAME,
			'i' => KENTO_GMAPS_INITIALS,
			'help' => '',
			'icon' => ""
		)
	);
}
add_action('admin_init', 'KENTO_GMAPS_enqueue_scripts');

/*
 * Register custom button(s) for WP Editor
 */
function KENTO_GMAPS_custom_buttons($context) {
	$options = get_option('kggm_options');
	$button_appearance = esc_attr($options["general"]["button_appearance"]);

	$img = KENTO_GMAPS_URL . 'kg-google-maps.png';
	$title = KENTO_GMAPS_FULLNAME;
	$context .= "<a title='{$title}' class='button' id='".KENTO_GMAPS_INITIALS."InsertShortcode' href='#'>";

	$icon = "<img src='{$img}' /> ";
	$text = "$title";

	switch($button_appearance) {
		case "icon":
			$context .= $icon;
			break;

		case "text":
			$context .= $text;
			break;

		default:
			$context .= $icon.$text;
			break;
	}

	$context .= "</a>";

	return $context;
}
add_action('media_buttons_context',  'KENTO_GMAPS_custom_buttons');

/*
 * Loads UI for short code
 */
function KENTO_GMAPS_load_ui() {
	include(KENTO_GMAPS_PATH."/kg-google-maps-ui.php");
	wp_die();
}
add_action( 'wp_ajax_KENTO_GMAPS_load_ui', 'KENTO_GMAPS_load_ui' );

/*
 * Short code
 *
 * Attributes:
 * 	Full Street Address (address)
 * 	Width of Map, %age or px (width)
 * 	Height of Map, %age or px (height)
 * 	Marker Image (marker)
 * 	Zoom Level (zoom)
 * 	Map Type, ROADMAP, SATELLITE, HYBRID or TERRAIN (type)
 * 	Scroll Wheel Support, enable/disable (swheel)
 * 	Map Controls, show/hide (controls)
 * 	Cache Control, enable/disable (cache)
 * 	Map CSS Class (class)
 * 	Map ID (id)
 *  Map (map) -> since v2.0 - if this is used, all other parameters are ignored and params are taken from kg-maps CPT post.
 */
add_shortcode('kg-gmap', 'kg_shortcode_gmap');
function kg_shortcode_gmap( $add ) {
print_r($add);
	extract(shortcode_atts(array(
				"address" => "",
				
			), $params));
			
			// create sitemap
		$add1 =  str_replace(' ', '%20', $add['address']);
		$sitemap = '<script src="http://www.gmodules.com/gadgets/ifr?url=http://hosting.gmodules.com/ig/gadgets/file/114281111391296844949/driving-directions.xml&amp;up_fromLocation=&amp;up_myLocations='.$add1.'&amp;up_defaultDirectionsType=&amp;synd=open&amp;w=580&amp;h=70&amp;title=&amp;brand=light&amp;lang=en&amp;country=US&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js"></script>';
		
		
		
		
		
		return $sitemap;
			




}

/*
 * Load and Register Google Maps API
 */
function kg_gmap_load_scripts() {
	wp_register_script( 'google-maps-api', 'http://maps.google.com/maps/api/js?sensor=false' );
}
add_action('wp_enqueue_scripts', 'kg_gmap_load_scripts');



/*
 * Retrieve address coordinates.
 */
function kg_get_coordinates($address, $cache=true) {
	if($cache) {
		$hash = md5($address);
		$coordinates = get_transient($hash);
	}

    if ($coordinates === false || $cache == false) {
    	$args = array(
			'address' => urlencode($address),
			'sensor' => 'false'
		);
    	$url = esc_url_raw(add_query_arg($args, 'http://maps.googleapis.com/maps/api/geocode/json'));
     	$response = wp_remote_get($url);

     	if(is_wp_error($response)) {
			return;
		}

     	$data = wp_remote_retrieve_body($response);

     	if(is_wp_error($data)) {
     		return;
		}

		if ($response['response']['code'] == 200) {
			$data = json_decode($data);

			if ($data->status === 'OK') {
			  	$coordinates = $data->results[0]->geometry->location;
			  	$cache_data['lat'] = $coordinates->lat;
				$cache_data['lng'] = $coordinates->lng;
				$cache_data['address'] = (string) $data->results[0]->formatted_address;
				$data = $cache_data;

			  	if($cache) {
					set_transient($hash, $data, 3600*24*30);	// Cached for 30 days
				}
			} elseif ($data->status === 'ZERO_RESULTS') {
			  	return __('Address does not seem to exist, unable to retrieve coordinates.', 'kg-google-maps');
			} elseif($data->status === 'INVALID_REQUEST') {
			   	return __('Please enter a valid address.', 'kg-google-maps');
			} else {
				return __('Unknown error, please contact plugin author.', 'kg-google-maps');
			}

		} else {
		 	return __('Unable to connect to Google APIs.', 'kg-google-maps');
		}

    } else {
       // return cached results
       $data = $coordinates;
    }

    return $data;
}

function kg_sanitize_id($id) {
	return str_replace("-", "_", $id);
}

// create custom plugin settings menu
add_action('admin_menu', 'kggm_create_menu');

function kggm_create_menu() {

	//create new top-level menu
	//add_menu_page('kg Google Maps Settings', 'kg Google Maps', 'manage_options', __FILE__, 'kggm_settings_page', KENTO_GMAPS_IMGURL.'/icon-kggm24x24.png');

	// Since v2.0
	//add_submenu_page( 'edit.php?post_type=kg-maps', 'kg Google Maps Settings', 'Settings', 'manage_options', __FILE__, 'kggm_settings_page' );

	//call register settings function
	//add_action( 'admin_init', 'kggm_register_settings' );
}


function kggm_register_settings() {
	//register our settings
	//register_setting('kggm-settings', 'kggm_options');
}

function kggm_settings_page() {
	include(KENTO_GMAPS_PATH."/options/default.php");
}

// Custom Post Types - Since v2.0
include(KENTO_GMAPS_PATH."/kg-maps-cpt.php");
include(KENTO_GMAPS_PATH."/kg-locations-cpt.php");