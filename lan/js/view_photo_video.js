// JS gérant l'affiche des photos et videos dans un viewer custom

$(document).ready(function(){

	//Déclaration de la page PHP de traitement d'image formData et de sa source
	var url = 'scripts/gallery_viewer.php';

	var get_lan_content_path;
	var get_background;
	var trim_bacground_url;
	var is_menu_rolled;
	var menu_div;
	var original_img_dir = "img/uploads/previous_lan/";
	var thumb_img_dir = "img/uploads/previous_lan/thumbnails/";
	var thumb_extension = "mini_";
	
	//Suite à l'ajout d'une image pour tentative d'upload, on va chercher le content de la pop-up modale en repérant de quelle LAN on vient
	$('div[class^="gamesImg add"]').on("click", function(){
	
		//On récupére l'image cliquée et on modifie son path pour avoir celui de l'original qui est envoyé au serveur
		get_background = $(this).css('background-image');
		trim_bacground_url = get_background.replace('url(','').replace(')','').replace(/\"/gi, "").replace('https://homeq.fr/', '');
		get_lan_content_path = trim_bacground_url.replace(thumb_img_dir, original_img_dir).replace(thumb_extension, '');		
		console.log(get_lan_content_path);
		
		$.ajax({
			type: 'POST',
			url: url,
			dataType: 'text',
			data: { 
				"clicked_content_path": get_lan_content_path
			},
			cache: false,
			success: function(code_html, statut){
				$("body").prepend(code_html);
				
				//On enroule le menu s'il est déroulé pour plus de confort
				menu_div = $( "div[class^='global_avatar_container']" ).attr("class").match(/rol/gi);
				if(menu_div == null){
					
					$( ".up_down" ).trigger( "click" );
					
				}
			}
		});
	});
});