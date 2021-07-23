// JS gérant l'envoi de l'image gallery à ajouter au serveur

//Si click sur bouton de cancel, on ferme la pop-up
$("#cancel_add").on('click', function(){
	$("#myModal").css("display", "none");
});

//Déclaration de la page PHP de traitement d'image formData et de sa source
var url = '../scripts/formData_img_reception.php';

//Si click sur bouton d'upload
$("#confirm_add").on('click', function(e){

	var fd = new FormData();
	//On s'assure de récupérer l'info de la LAN pour laquelle une image tente d'être uploadée
	var whichLan = $('#received_lan_num').text().trim();
	var files = $('#file-input'+whichLan+'')[0].files[0];
	fd.append('image',files);
	fd.append('whichLan',whichLan);

	$.ajax({
		url: url,
		type: 'post',
		data: fd,
		contentType: false,
		processData: false,
		success: function(msg){
			$('.statusMsg').html('');
			if(msg.trim() == 'Success'){
				$('.statusMsg').html('<span style="font-size:inherit;color:#34A853">Succès !</span>');
				$("button#confirm_add").attr("disabled");
				setTimeout(function(){
				location.reload(true);
				},500);
			}
			else{
				$('.statusMsg').html('<span style="font-size:inherit;color:#EA4335"><br/>'+msg+'<br/>Erreur...</span>');
			}								
		}
	});
});	
