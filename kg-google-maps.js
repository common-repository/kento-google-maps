jQuery(document).ready(function($){
	$("#kggm_InsertShortcode").on("click", function(e){
		e.preventDefault();

		var data = {action: 'KENTO_GMAPS_load_ui'};

		KENTO_GMAPS_load_ui(data);
	});
});

function KENTO_GMAPS_load_ui(data) {
	jQuery.get(kggm_ajax.url, data, function(resp){
		jQuery.colorbox({
			title: kggm_ajax.icon+kggm_ajax.tag+kggm_ajax.help,
			className: kggm_ajax.i+"cbox",
			html: resp,
			open: true,
			width: 600,
			height: 500,
			opacity: '0.7',
			fixed: true
		});
	});
}

function kg_gmaps_insert() {
	jQuery("#kggm-action-insert").on("click", function(e) {
		e.preventDefault();

		var shortcode = jQuery(".kggm-shortcode-title").attr("data-shortcode");
		var params = {};
		var isOk = true;

		jQuery(".kggm-param").each(function(i){
			var p_name = jQuery(this).attr("data-param-name");
			var p_val = jQuery(this).val();
			var p_required = jQuery(this).attr("data-required");

			// If map attribute is used, then ignore other attributes
			if(p_name == "map" && p_val != "") {
				params[p_name] = p_val;
				return false;
			}

			if(p_val != "") {
				params[p_name] = p_val;
			} else if(p_val == "" && p_required == "1") { //Required parameter, must not be empty
				jQuery(this).addClass("highlight-error");
				jQuery(this).focus();
				isOk = false;
			}
		});

		if(isOk) {
			KENTO_GMAPS_insert_shortcode(shortcode, params);
		}
	});
}

function KENTO_GMAPS_insert_shortcode(shortcode, params) {
	params = (typeof params === 'undefined') ? null : params;

	var attrs = [];
	var strAttrs = "";
	var out = shortcode;

	if(params != null) {
		jQuery.each(params, function(key, val){
			attrs.push(key+"=\""+val+"\"");
		});

		attrs.sort();
		strAttrs = attrs.join(" ");
	}

	if(strAttrs != "") {
		out += " "+strAttrs;
	}

	window.parent.send_to_editor('[' + out + ']');
	jQuery.colorbox.close();
}
