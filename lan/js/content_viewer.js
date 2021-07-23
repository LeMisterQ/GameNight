// JS gérant l'envoi de l'image gallery à ajouter au serveur

$(document).ready(function(){
	
	//Si click sur bouton de cancel, on remove le viewer (pour ne pas empiler les divs sinon en cas de clic successifs sur des images)
	$(".close_viewer").on('click', function(){
		$("#myModal").remove();
	});
	
	// Si clic n'importe où dans la fenêtre, on remove le div
	$("#myModal").on('click', function(){
		$(this).remove();
	});
});