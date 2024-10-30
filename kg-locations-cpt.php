<?php
// Register Locations Custom Post Type - Since v2.0
if ( ! function_exists('kg_register_locations_cpt') ) {
	function kg_register_locations_cpt() {

		$labels = array(
			'name'                => _x( 'kg Google Map Locations', 'Post Type General Name', 'kg-google-maps' ),
			'singular_name'       => _x( 'kg Google Map Location', 'Post Type Singular Name', 'kg-google-maps' ),
			'menu_name'           => __( 'Locations', 'kg-google-maps' ),
			//'name_admin_bar'      => __( 'kg Google Map', 'kg-google-maps' ),
			//'parent_item_colon'   => __( 'kg Google Maps', 'kg-google-maps' ),
			'all_items'           => __( 'Manage Locations', 'kg-google-maps' ),
			'add_new_item'        => __( 'Add New Location', 'kg-google-maps' ),
			'add_new'             => __( 'Add New Location', 'kg-google-maps' ),
			'new_item'            => __( 'New Location', 'kg-google-maps' ),
			'edit_item'           => __( 'Edit Location', 'kg-google-maps' ),
			'update_item'         => __( 'Update Location', 'kg-google-maps' ),
			'view_item'           => __( 'View Location', 'kg-google-maps' ),
			'search_items'        => __( 'Search Locations', 'kg-google-maps' ),
			'not_found'           => __( 'Not found', 'kg-google-maps' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'kg-google-maps' ),
		);
		$args = array(
			'label'               => __( 'kg Map Location', 'kg-google-maps' ),
			'description'         => __( 'Defines a location to place on map.', 'kg-google-maps' ),
			'labels'              => $labels,
			'supports'            => array( 'title'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=kg-maps',
			//'menu_position'       => 58,
			'menu_icon'           => 'dashicons-pressthis',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'kg-locations', $args );

	}
	add_action( 'init', 'kg_register_locations_cpt', 0 );

	// Register meta box for custom fields
	function kg_locations_meta_box_add() {
		add_meta_box( 'kg-locations-meta', 'Location Options', 'kg_locations_meta_box_cb', 'kg-locations', 'normal', 'high' );
	}
	add_action( 'add_meta_boxes', 'kg_locations_meta_box_add' );

	function kg_locations_meta_box_cb($post) {
		$kggm_params = get_post_custom($post->ID);
		$kggm_param_address = isset($kggm_params['kggm-param-address'])?esc_attr($kggm_params['kggm-param-address'][0]):'';
		$kggm_param_marker = isset($kggm_params['kggm-param-marker'])?esc_attr($kggm_params['kggm-param-marker'][0]):'';
		$kggm_param_marker_custom = isset($kggm_params['kggm-param-marker-custom'])?esc_attr($kggm_params['kggm-param-marker-custom'][0]):'';
		$kggm_param_infowindow = isset($kggm_params['kggm-param-infowindow'])?$kggm_params['kggm-param-infowindow'][0]:'';
		$kggm_param_map = isset($kggm_params['kggm-param-map'])?esc_attr($kggm_params['kggm-param-map'][0]):'';

		// We'll use this nonce field later on when saving.
		wp_nonce_field('kg_locations_meta_box_nonce', 'meta_box_nonce');
		?>
		<div class="kggm-controls-group" style="float: inherit">
			<div class="kggm-control" style="float: inherit">
				<label for="kggm-control-address" class="kggm-control-label"><?=__('Address', 'kg-google-maps')?> <span class="required kggm-right"><?=__('(required)', 'kg-google-maps')?></span></label>
				<input type="text" id="kggm-control-address" name="kggm-param-address" value="<?php echo $kggm_param_address; ?>" class="kggm-control-text kggm-param" data-param-name="address" data-required="1" placeholder="This field is required" />
			</div>

			<!--<div class="kggm-control" style="float: inherit">
				<div class="kggm-preview" style="float: right; width: 10%; text-align: right;">
					<img src="" style="display: none;" />
				</div>
				<label for="kggm-control-marker" class="kggm-control-label" style="float: left; width: 89%;"><?=__('Marker Icon', 'kg-google-maps')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<select id="kggm-control-marker" name="kggm-param-marker" class="kggm-control-select kggm-param kggm-opt-a" data-param-name="marker" style="float: inherit; width: 89%;">
					<option value="">Default</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/blue.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/blue.png"); ?>>Blue</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/green.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/green.png"); ?>>Green</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/litegreen.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/litegreen.png"); ?>>Lite Green</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/orange.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/orange.png"); ?>>Orange</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/pink.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/pink.png"); ?>>Pink</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/red.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/red.png"); ?>>Red</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/skyblue.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/skyblue.png"); ?>>Sky Blue</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/teal.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/teal.png"); ?>>Teal</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/teapink.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/teapink.png"); ?>>Tea Pink</option>
					<option value="<?php echo kg_GMAPS_IMGURL; ?>/markers/yellow.png" <?php selected($kggm_param_marker, kg_GMAPS_IMGURL."/markers/yellow.png"); ?>>Yellow</option>
					<option value="custom" <?php selected($kggm_param_marker, "custom"); ?>>Custom URL</option>
				</select>
				<input type="text" id="kggm-control-marker-custom" name="kggm-param-marker-custom" value="<?php echo $kggm_param_marker_custom; ?>" class="kggm-control-text kggm-param kggm-opt-b" data-param-name="marker" style="float: inherit; width: 89%; <?php if($kggm_param_marker!="custom") {?>display: none;<?php } ?>" />
				<span class="notes" style="float: left; width: 89%;"><?=__('URL to a custom .png/.jpg/.gif image to use as marker icon. Default is Google Map Pin Icon.', 'kg-google-maps')?></span>
			</div>

			<div class="kggm-control" style="float: inherit; clear: both; padding-top: 10px;">
				<label for="kggm-control-infowindow-wrap" class="kggm-control-label" style="float: inherit"><?=__('Content for Info Window', 'kg-google-map<!--s')?> <span class="optional kggm-right"><?=__('(optional)', 'kg-google-maps')?></span></label>
				<?php
					$settings = array(
						'media_buttons' => false,
						'teeny' => true,
						'textarea_rows' => '7',
						'textarea_name' => 'kggm-param-infowindow'
					);

					wp_editor( $kggm_param_infowindow, "kggm-control-infowindow", $settings );
				?>
				<span class="notes" style="float: left; width: 89%;"><?=__('Leave blank to display address in the info window.', 'kg-google-maps')?></span>
			</div>

			<div class="kggm-control" style="float: inherit; clear: both; padding-top: 10px;">
				<label for="kggm-control-map" class="kggm-control-label"><?=__('Use with Map', 'kg-google-maps')?> <span class="required kggm-right"><?=__('(required)', 'kg-google-maps')?></span></label>
				<select id="kggm-control-map" name="kggm-param-map" class="kggm-control-select kggm-param" data-param-name="map">
					<?php
						$args = array(
							'posts_per_page' => -1,
							'post_type' => 'kg-maps',
							'orderby' => 'title',
							'order' => 'ASC'
						);
						$maps = get_posts($args);

						foreach ($maps as $map):
					?>
							<option value="<?php echo $map->ID; ?>" <?php selected($kggm_param_map, $map->ID); ?>><?php echo get_the_title($map->ID); ?></option>
					<?php
						endforeach;
						wp_reset_postdata();
					?>
				</select>
			</div>-->
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function($){
				$("#kggm-control-marker").on("change", function(){
					var v = $(this).val();

					if(v !== "" && v !== "custom") {
						//var src = '<?php echo kg_GMAPS_IMGURL; ?>/markers/' + v;
						$(".kggm-preview img").attr("src", v);
						$(".kggm-preview img").show();
					} else {
						$(".kggm-preview img").attr("src", "").hide();
						$(".kggm-preview img").hide();
					}

					if(v == "custom") {
						$(".kggm-opt-b").show();
					} else {
						$(".kggm-opt-b").val("");
						$(".kggm-opt-b").hide();
					}

				});
			});
		</script>
	<?php
	}

	// Save Custom Fields
	function kg_locations_meta_box_save( $post_id ) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'kg_locations_meta_box_nonce' ) ) return;

		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;

		// Make sure data is set before trying to save it
		if( isset( $_POST['kggm-param-address'] ) )
			update_post_meta( $post_id, 'kggm-param-address', esc_attr( $_POST['kggm-param-address'] ) );

		if( isset( $_POST['kggm-param-infowindow'] ) )
			update_post_meta( $post_id, 'kggm-param-infowindow', $_POST['kggm-param-infowindow']);

		if( isset( $_POST['kggm-param-marker'] ) )
			update_post_meta( $post_id, 'kggm-param-marker', esc_attr( $_POST['kggm-param-marker'] ) );

		if( isset( $_POST['kggm-param-marker-custom'] ) )
			update_post_meta( $post_id, 'kggm-param-marker-custom', esc_attr( $_POST['kggm-param-marker-custom'] ) );

		// Post Relation - link it with Selected Map
		if( isset( $_POST['kggm-param-map'] ) )
			update_post_meta( $post_id, 'kggm-param-map', esc_attr( $_POST['kggm-param-map'] ) );
	}
	add_action( 'save_post', 'kg_locations_meta_box_save' );

	// Add short code column to maps posts list
	function kg_locations_columns_head($defaults) {
		unset($defaults['date']);

		$defaults['map'] = __('Map');
		$defaults['date'] = __('Date');

		return $defaults;
	}
	function kg_locations_columns_content($column_name, $post_ID) {
		if ($column_name == 'map') {
			$map_id = get_post_meta($post_ID, 'kggm-param-map', true);
			$map_title = get_the_title($map_id);

			echo "<a href='post.php?post={$map_id}&action=edit'>{$map_title}</a> ({$map_id})";
		}

		if($column_name == 'date') {
			echo get_the_date();
		}
	}
	add_filter('manage_kg-locations_posts_columns', 'kg_locations_columns_head', 10);
	add_action('manage_kg-locations_posts_custom_column', 'kg_locations_columns_content', 10, 2);
}