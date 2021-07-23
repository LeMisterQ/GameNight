//Js gérant l'apparition du menu d'édition du member title
$(document).ready(function(){
	// Si clic sur le canvas on récup le pseudo qu'on fournit au serveur */	
	$('.members_spiderChart_each').on('click', function(){
	
	//Récupération du pseudo
	var this_pseudo = $(this).find('canvas').attr("id").split("_")[1];
	
	//Déclaration de la page PHP de traitement du binaire d'image et de sa source
	var url = 'includes/members_spiderCharts.php?spider_pseudo=' + this_pseudo;
	
		$.ajax({
			type: 'GET',
			url: url,
			dataType: 'html',
			cache: false,
			success: function(code_html, statut){
				$("body").prepend(code_html);
			}
		});
	});
});