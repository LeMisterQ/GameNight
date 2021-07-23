// JS gérant l'affichage de la pop-up de confirmation d'ajout d'image

$(document).ready(function(){

	var get_lan_pic_add;
	
	//Suite à l'ajout d'une image pour tentative d'upload, on va chercher le content de la pop-up modale en repérant de quelle LAN on vient
	$('input[id^="file-input"]').on("change", function(){
	
	var this_input = $(this);
	
	//On récupére l'integer du numéro de la LAN
	get_lan_pic_add = Number(this_input.parent().attr("id").match(/\d/));	
	
		$.ajax({
			type: 'GET',
			url: 'includes/gallery_img_add_confirm.php',
			dataType: 'html',
			data: { 
				"lan_num": get_lan_pic_add
			},
			cache: false,
			success: function(code_html, statut){
				$("body").prepend(code_html);
			}
		});
	});
});	