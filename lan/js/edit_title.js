//Js gérant l'apparition du menu d'édition du member title
$(document).ready(function(){
	//Déclaration de la page PHP de traitement du binaire d'image et de sa source
	var url = 'includes/title_dropdowns.php';

	// Si clic sur le boutton, on toggle entre montrer et afficher le contenu */	
	$('button#edit_title').on('click', function(){
	
		$.ajax({
			type: 'GET',
			url: url,
			dataType: 'html',
			cache: false,
			success: function(code_html, statut){
				$(code_html).insertAfter('div#my_title');
				$('#button#edit_title').attr("disabled");	
			}
		});
	});
});