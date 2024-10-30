<div class="kggm-shortcode-wrapper">
	<h2 class="kggm-shortcode-title" data-shortcode="kg-gmap">[kg-gmap]</h2>
	<p class="kggm-shortcode-desc"><?=__('Configure and Insert short code to place a Google Map in your post or page.', 'kg-google-maps')?></p>
<a href="http://jimitupadhyay.byethost8.com/wp/index.php/google-direction/" style="float:right;">See Tutorial to add Widget</a>
	<?php
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'kg-maps',
			'orderby' => 'title',
			'order' => 'ASC'
		);
		$maps = get_posts($args);
		wp_reset_postdata();
	?>

	
	<div class="kggm-controls-group">
	
		<div class="kggm-control" data-mode="basic">
			<label for="kggm-control-address" class="kggm-control-label"><?=__('Address', 'kg-google-maps')?> <span class="required kggm-right"><?=__('(required)', 'kg-google-maps')?></span></label>
			<input type="text" id="kggm-control-address" name="kggm-param-address" class="kggm-control-text kggm-param" data-param-name="address" data-required="1" placeholder="This field is required" />
		</div>



		<div class="kggm-control separator">
			<button type="button" id="kggm-action-insert" class="button button-primary button-large kggm-control-button kggm-right"><?=__('Insert Short Code', 'kg-google-maps')?></button>
		</div>
	</div>
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

		$("#kggm-control-map").on("change", function(){
			var v = $(this).val();

			if(v != "") {
				$("[data-mode=basic]").hide();
				$("[data-required=1]").attr("data-required", "0");
			} else {
				$("[data-mode=basic]").show();
				$("[data-required=0]").attr("data-required", "1");
			}
		});

		kg_gmaps_insert();
	});
</script>
