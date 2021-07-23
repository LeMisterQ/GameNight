// JS gérant l'envoi de l'image cropée au serveur

$(document).ready(function(){

//Déclaration de la page PHP de traitement du binaire d'image et de sa source
var url = 'scripts/binary_img_reception.php';

	//Si click sur bouton d'upload
	$("#fileSubmit").on('click', function(e){
		var data2send = $('#img_init').find('a').eq(0).attr('href');

		//Requête AJAX d'upload
		$.ajax({
			type: 'POST',
			url: url,
			dataType: 'text',
			cache: false,
			data: {
				base64data : data2send,
			},
			beforeSend: function(){
				$('#fileSubmit').attr("disabled","disabled");
				$('#img_init').css("opacity",".5");
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
				$('#img_init').css("opacity","");
				$("#fileSubmit").removeAttr("disabled");
				
			}
		});
	});
});