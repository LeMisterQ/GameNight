// JS gérant l'envoi du titre du membre au serveur

$(document).ready(function(){

//Déclaration de la page PHP de traitement du binaire d'image et de sa source
var url = 'scripts/title_reception.php';

	//Si click sur bouton d'upload
	$("#validate_title").on('click', function(e){
		var data2send = $('#title_full').text();

		//Requête AJAX d'upload
		$.ajax({
			type: 'POST',
			url: url,
			dataType: 'text',
			cache: false,
			data: {
				member_title : data2send,
			},
			beforeSend: function(){
				$('#validate_title').attr("disabled","disabled");
				$('#title_full').css("opacity",".5");
			},
			success: function(msg){
				$('.statusMsg').html('');
				if(msg.trim() == 'Success'){
					$('.statusMsg').html('<span style="font-size:inherit;color:#34A853">Succès !</span>');
					setTimeout(function(){
					location.reload(true);
					},500);
				}
				else{
					$('.statusMsg').html('<span style="font-size:inherit;color:#EA4335">Erreur...</span>');
				}
				$('#title_full').css("opacity","");
				$("#validate_title").removeAttr("disabled");
				
			}
		});
	});
});