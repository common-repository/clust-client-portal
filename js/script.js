;(function($) {
	$(document).ready(function($){
		$("#clust_client_portal_ib").on("click", function() {
			if( $("#clust_client_portal_url").val() == '' ){
				$("#url_error").show();
				return;
			}
			$("#url_error").hide();
			var id    = $("#clust_client_portal_select").val();
			var signature = $("#clust_client_portal_select option:selected").data("signature");
			var height = $("#clust_client_portal_height").val();
			var url = $("#clust_client_portal_url").val();
			var wh    = $("#clust_client_portal_wh").is(":checked") ? "1" : "0";
			var wl    = $("#clust_client_portal_wl").is(":checked") ? "1" : "0";
			wp.media.editor.insert('[clust_client_portal id="' + id + '" signature="' + signature  + '" height="' + height + '" url="' + url + '" with_header="' + wh + '" with_logo="' + wl + '"]');
			});
	});
})(jQuery);