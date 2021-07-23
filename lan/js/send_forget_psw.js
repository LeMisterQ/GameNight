// JS gérant l'envoi de l'image gallery à ajouter au serveur

$(document).ready(function(){

	//Déclaration de la page PHP de traitement d'image formData et de sa source
	var url = '../scripts/forgot_psw_mail.php';

	//Si click sur bouton d'upload
	$('button#button_forgot').on('click', function(e){
	
		
		//On s'assure de récupérer l'info de la LAN pour laquelle une image tente d'être uploadée
		var forget_email = $('input#forget_email').val();

        $.ajax({
            url: url,
            type: 'post',
            data: {
				forget_email: forget_email
			},
            datatype: "text",
            success: function(msg){
				$('.statusMsg').html('');
				if(msg.trim() == 'Success'){
					$('.statusMsg').html('<span style="font-size:inherit;color:#34A853">Succès ! Va vérifier tes emails.</span>');
					$("button#button_forgot").attr("disabled");
				}
				else{
					$('.statusMsg').html('<span style="font-size:inherit;color:#EA4335">Erreur...<br/>'+msg+'<br/></span>');
				}								
			}
        });
	});	
});