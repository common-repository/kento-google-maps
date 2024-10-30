<?php
// Register Maps Custom Post Type - Since v2.0
if ( ! function_exists('kg_register_maps_cpt') ) {
	function kg_register_maps_cpt() {

		$labels = array(
			'name'                => _x( 'kg Google Maps', 'Post Type General Name', 'kg-google-maps' ),
			'singular_name'       => _x( 'kg Google Map', 'Post Type Singular Name', 'kg-google-maps' ),
			'menu_name'           => __( 'kg Google Maps', 'kg-google-maps' ),
			//'name_admin_bar'      => __( 'kg Google Map', 'kg-google-maps' ),
			//'parent_item_colon'   => __( 'kg Google Maps', 'kg-google-maps' ),
			'all_items'           => __( 'All Maps', 'kg-google-maps' ),
			'add_new_item'        => __( 'Add New Map', 'kg-google-maps' ),
			'add_new'             => __( 'Add New Map', 'kg-google-maps' ),
			'new_item'            => __( 'New Map', 'kg-google-maps' ),
			'edit_item'           => __( 'Edit Map', 'kg-google-maps' ),
			'update_item'         => __( 'Update Map', 'kg-google-maps' ),
			'view_item'           => __( 'View Map', 'kg-google-maps' ),
			'search_items'        => __( 'Search Maps', 'kg-google-maps' ),
			'not_found'           => __( 'Not found', 'kg-google-maps' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'kg-google-maps' ),
		);
		$args = array(
			'label'               => __( 'kg Map', 'kg-google-maps' ),
			'description'         => __( 'Defines a map entry.', 'kg-google-maps' ),
			'labels'              => $labels,
			'supports'            => array( 'title', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
		//	'show_in_menu'        => true,
			'menu_position'       => 99,
			'menu_icon'           => kg_GMAPS_IMGURL.'/icon-kggm24x24.png', //'dashicons-location-alt',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'kg-maps', $args );

	}
	//add_action( 'init', 'kg_register_maps_cpt', 0 );

	// Register meta box for custom fields
	function kg_maps_meta_box_add() {
		add_meta_box( 'kg-maps-meta', 'Map Options', 'kg_maps_meta_box_cb', 'kg-maps', 'normal', 'high' );
	}
	add_action( 'add_meta_boxes', 'kg_maps_meta_box_add' );

	function kg_maps_meta_box_cb($post) {
		$kggm_params = get_post_custom($post->ID);
		$kggm_param_width = isset($kggm_params['kggm-param-width'])?esc_attr($kggm_params['kggm-param-width'][0]):'';
		$kggm_param_height = isset($kggm_params['kggm-param-height'])?esc_attr($kggm_params['kggm-param-height'][0]):'';
		$kggm_param_zoom = isset($kggm_params['kggm-param-zoom'])?esc_attr($kggm_params['kggm-param-zoom'][0]):'';
		$kggm_param_type = isset($kggm_params['kggm-param-type'])?esc_attr($kggm_params['kggm-param-type'][0]):'';
		$kggm_param_swheel = isset($kggm_params['kggm-param-swheel'])?esc_attr($kggm_params['kggm-param-swheel'][0]):'';
		$kggm_param_controls = isset($kggm_params['kggm-param-controls'])?esc_attr($kggm_params['kggm-param-controls'][0]):'';
		$kggm_param_cache = isset($kggm_params['kggm-param-cache'])?esc_attr($kggm_params['kggm-param-cache'][0]):'';
		$kggm_param_class = isset($kggm_params['kggm-param-class'])?esc_attr($kggm_params['kggm-param-class'][0]):'';
		$kggm_param_id = isset($kggm_params['kggm-param-id'])?esc_attr($kggm_params['kggm-param-id'][0]):'';

		//print_r($kggm_params);

		// We'll use this nonce field later on when saving.
		wp_nonce_field('kg_maps_meta_box_nonce', 'meta_box_nonce');
		?>
		<div class="kggm-controls-group" style="float: inherit">
			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-width" class="kggm-control-label"><?=__('Map Width', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<input type="text" id="kggm-control-width" name="kggm-param-width" value="<?php echo $kggm_param_width; ?>" class="kggm-control-text kggm-param" data-param-name="width" />
				<span class="notes"><?=__('i.e. 50% or 400px - default is 100%', 'kg-google-maps')?></span>
			</div>

			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-height" class="kggm-control-label"><?=__('Map Height', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<input type="text" id="kggm-control-height" name="kggm-param-height" value="<?php echo $kggm_param_height; ?>" class="kggm-control-text kggm-param" data-param-name="height" />
				<span class="notes"><?=__('i.e. 50% or 400px - default is 400px', 'kg-google-maps')?></span>
			</div>

			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-zoom" class="kggm-control-label"><?=__('Initial Zoom Level', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<input type="text" id="kggm-control-zoom" name="kggm-param-zoom" value="<?php echo $kggm_param_zoom; ?>" class="kggm-control-text kggm-param" data-param-name="zoom" />
				<span class="notes"><?=__('Default is 15 - lower value zoom out while higher value zoom in the map.', 'kg-google-maps')?></span>
			</div>

			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-type" class="kggm-control-label"><?=__('Map Type', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<select id="kggm-control-type" name="kggm-param-type" class="kggm-control-select kggm-param" data-param-name="type">
					<option value="">-- Select --</option>
					<option value="HYBRID" <?php selected($kggm_param_type, 'HYBRID'); ?>>HYBRID</option>
					<option value="ROADMAP" <?php selected($kggm_param_type, 'ROADMAP'); ?>>ROADMAP (default)</option>
					<option value="SATELLITE" <?php selected($kggm_param_type, 'SATELLITE'); ?>>SATELLITE</option>
					<option value="TERRAIN" <?php selected($kggm_param_type, 'TERRAIN'); ?>>TERRAIN</option>
				</select>
			</div>

			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-swheel" class="kggm-control-label"><?=__('Mouse Scroll Wheel', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<select id="kggm-control-swheel" name="kggm-param-swheel" class="kggm-control-select kggm-param" data-param-name="swheel">
					<option value="">-- Select --</option>
					<option value="disable" <?php selected($kggm_param_swheel, 'disable'); ?>>Disable (default)</option>
					<option value="enable" <?php selected($kggm_param_swheel, 'enable'); ?>>Enable</option>
				</select>
			</div>

			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-controls" class="kggm-control-label"><?=__('Map Controls', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<select id="kggm-control-controls" name="kggm-param-controls" class="kggm-control-select kggm-param" data-param-name="controls">
					<option value="">-- Select --</option>
					<option value="hide" <?php selected($kggm_param_controls, 'hide'); ?>>Hide</option>
					<option value="show" <?php selected($kggm_param_controls, 'show'); ?>>Show (default)</option>
				</select>
			</div>

			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-cache" class="kggm-control-label"><?=__('Cache', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<select id="kggm-control-cache" name="kggm-param-cache" class="kggm-control-select kggm-param" data-param-name="cache">
					<option value="">-- Select --</option>
					<option value="disable" <?php selected($kggm_param_cache, 'disable'); ?>>Disable</option>
					<option value="enable" <?php selected($kggm_param_cache, 'enable'); ?>>Enable (default)</option>
				</select>
				<span class="notes"><?=__('If enabled, stores results in cache for 30 days - which improves speed. If you want to get fresh results every time, do not enable cache.', 'kg-google-maps')?></span>
			</div>

			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-class" class="kggm-control-label"><?=__('CSS: Map DIV Class(es)', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<input type="text" id="kggm-control-class" name="kggm-param-class" value="<?php echo $kggm_param_class; ?>" class="kggm-control-text kggm-param" data-param-name="class" />
				<span class="notes"><?=__('Default is kg-gmap', 'kg-google-maps')?></span>
			</div>

			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-id" class="kggm-control-label"><?=__('CSS: Map DIV ID', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<input type="text" id="kggm-control-id" name="kggm-param-id" value="<?php echo $kggm_param_id; ?>" class="kggm-control-text kggm-param" data-param-name="id" />
				<span class="notes"><?=__('Default is kg-gmap - do not include # (hash) sign. This ID is also used as map object ID.', 'kg-google-maps')?></span>
			</div>
		</div>
	<?php
	}

	// Save Custom Fields
	function kg_maps_meta_box_save( $post_id ) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'kg_maps_meta_box_nonce' ) ) return;

		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;

		// Make sure data is set before trying to save it
		if( isset( $_POST['kggm-param-width'] ) )
			update_post_meta( $post_id, 'kggm-param-width', esc_attr( $_POST['kggm-param-width'] ) );

		if( isset( $_POST['kggm-param-height'] ) )
			update_post_meta( $post_id, 'kggm-param-height', esc_attr( $_POST['kggm-param-height'] ) );

		if( isset( $_POST['kggm-param-zoom'] ) )
			update_post_meta( $post_id, 'kggm-param-zoom', esc_attr( $_POST['kggm-param-zoom'] ) );

		$param_type = (isset($_POST['kggm-param-type']) && !empty($_POST['kggm-param-type']))?$_POST['kggm-param-type']:"ROADMAP";
		update_post_meta($post_id, 'kggm-param-type', esc_attr($param_type));

		$param_swheel = (isset($_POST['kggm-param-swheel']) && !empty($_POST['kggm-param-swheel']))?$_POST['kggm-param-swheel']:'disable';
		update_post_meta($post_id, 'kggm-param-swheel', $param_swheel);

		$param_controls = (isset($_POST['kggm-param-controls']) && !empty($_POST['kggm-param-controls']))?$_POST['kggm-param-controls']:'show';
		update_post_meta($post_id, 'kggm-param-controls', $param_controls);

		$param_cache = (isset($_POST['kggm-param-cache']) && !empty($_POST['kggm-param-cache']))?$_POST['kggm-param-cache']:'enable';
		update_post_meta($post_id, 'kggm-param-cache', $param_cache);

		if( isset( $_POST['kggm-param-class'] ) )
			update_post_meta( $post_id, 'kggm-param-class', esc_attr( $_POST['kggm-param-class'] ) );

		if( isset( $_POST['kggm-param-id'] ) )
			update_post_meta( $post_id, 'kggm-param-id', esc_attr( $_POST['kggm-param-id'] ) );

	}
	add_action( 'save_post', 'kg_maps_meta_box_save' );

	// Register meta box for attached locations
	function kg_maps_meta_box_locations() {
		add_meta_box( 'kg-maps-locations', 'Locations on this map', 'kg_maps_meta_box_locations_cb', 'kg-maps', 'side', 'default' );
	}
	add_action( 'add_meta_boxes', 'kg_maps_meta_box_locations' );

	function kg_maps_meta_box_locations_cb($post) {
		$post_status = $post->post_status;

		if($post_status != "auto-draft" && $post_status != "draft") {
			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'kg-locations',
				'orderby' => 'title',
				'order' => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'kggm-param-map',
						'value' => $post->ID
					)
				)
			);
			$locations = get_posts($args);

			echo "<table width='100%' cellpadding='5' cellspacing='0'>";
print_r($locations);
			foreach($locations as $location) {
				echo "<tr>";
				echo "<td style='width: 80%; border-bottom: 1px solid lightgray;'>".$location->post_title."</td>";
				echo "<td style='width: 20%; text-align: right; border-bottom: 1px solid lightgray;'><a href='post.php?post={$location->ID}&action=edit'>Edit</a></td>";
				echo "</tr>";
			}

			echo "<tr>";
			echo "<td colspan='2' style='padding-top: 20px; text-align: center;'><a href='post-new.php?post_type=kg-locations' class='page-title-action'>Add New Location</a></td>";
			echo "</tr>";

			echo "</table>";

			wp_reset_postdata();
		}
	}

	// Register meta box for map's short code
	function kg_maps_meta_box_sc() {
		add_meta_box( 'kg-maps-sc', 'Short Code', 'kg_maps_meta_box_sc_cb', 'kg-maps', 'side', 'high' );
	}
	add_action( 'add_meta_boxes', 'kg_maps_meta_box_sc' );

	function kg_maps_meta_box_sc_cb($post) {
		$post_status = $post->post_status;

		if($post_status != "auto-draft" && $post_status != "draft") {
			echo "<div style='text-align: center;'>";
			echo "<code style='display: block;'>[kg-gmap map={$post->ID}]</code>";
			echo "</div>";
		}
	}

	// Add short code column to maps posts list
	function kg_maps_columns_head($defaults) {
		unset($defaults['date']);

		$defaults['short_code'] = __('Short Code');
		$defaults['date'] = __('Date');

		return $defaults;
	}
	function kg_maps_columns_content($column_name, $post_ID) {
	echo $column_name. ',' . $post_ID;
		if ($column_name == 'short_code') {
			echo "<code>[kg-gmap map={$post_ID}]</code>";
		}

		if($column_name == 'date') {
			echo get_the_date();
		}
	}
	add_filter('manage_kg-maps_posts_columns', 'kg_maps_columns_head', 10);
	add_action('manage_kg-maps_posts_custom_column', 'kg_maps_columns_content', 10, 2);
}