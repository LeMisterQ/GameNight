$(document).ready(function(){

	//Déclaration de la page PHP de traitement du binaire d'image et de sa source
	var url = '/scripts/team_constructor.php';
	var teamname;
	var playerdata = [];
	var i = 0;

	/////////////////////
	//////MODALE/////////
	/////////////////////

	function closeModal(el){
		el.css("display", "none");
		$('section').css("filter", "");
	}

	// Ref à la modale
	modal = $('#myModal');

	// span qui ouvre la modale
	span = $(".close_popup");

	// Click sur X de la modale : fermer la modale
	span.click(function() {
		closeModal(modal);

	});

	// Click en dehors de la modale : fermer la modale
	modal.click(function() {
		closeModal(modal);

	});
	
	////////////////////////////
	////////////////////////////
	/////USEFUL FUNCTIONS///////
	////////////////////////////
	////////////////////////////

	function sendMessage(wsobject, msg){
		wsobject.send(JSON.stringify(msg));
	}	

	function refreshTeams(operation, team){
		
		let Ask4Teams = {};

		if(operation == "add"){
		
			Ask4Teams["operation"] = operation;
			Ask4Teams["team2impact"] = "none";			
			Ask4Teams["type"] = "newteam";
			sendMessage(wst, Ask4Teams);
			
		}
		else if(operation == "delete"){
			
			Ask4Teams["operation"] = operation;
			Ask4Teams["team2impact"] = team;
			Ask4Teams["type"] = "newteam";
			sendMessage(wst, Ask4Teams);
		}
		
	}
	
	function refreshPlayers(operation, team, player){
	
		let Ask4Players = {};

		if(operation == "add"){
		
			Ask4Players["operation"] = operation;
			Ask4Players["team2impact"] = team;
			Ask4Players["player2impact"] = player;			
			Ask4Players["type"] = "newplayer";
			sendMessage(wst, Ask4Players);
			
		}
		else if(operation == "delete"){
			
			Ask4Players["operation"] = operation;
			Ask4Players["team2impact"] = team;
			Ask4Players["player2impact"] = player;
			Ask4Players["type"] = "newplayer";
			sendMessage(wst, Ask4Players);
		}
		
	}
	
	function getTeamsList(){

		let Ask4Teams = {};
	
		Ask4Teams["operation"] = "teamlist";			
		Ask4Teams["type"] = "request";
		sendMessage(wst, Ask4Teams);

		
	}	
	
	//////////////////////////
	////WEBSOCKET NOTIFIER////
	//////////////////////////

	var wst = new WebSocket("wss://homeq.fr/ws/", "teamsInfo");

	wst.onopen = function (event) {
		
		getTeamsList("all");
		
	};

	wst.onmessage=function(event) {
		
		var message = JSON.parse(event.data);
		
		switch(message.type) {
			
			case "request":
			
				switch(message.operation) {
					
					case "teamlist":
					
						let answers_storage;
					
						if(message[0] == "none"){
							
							$('select[id="select_team"]').html('<option class="select_items" value="" disabled selected>Aucune équipe en jeu</option>');
							
						}
						else{							
				
						//Treat Team List
						let answers_count = Number(Object.keys(message).length - 2);
						let answers_storage = "";

						for(i=0; i<answers_count; i++){
							answers_storage += '<option class = "select_items" value="' + message[i] + '">' + message[i] + '</option>';
						}

						$('select[id="select_team"]').html(answers_storage);
						}
						
					break;
				
				}
				
			break;
			
		}
		
	};


	/////////////////////
	////EVENT ONCLICK////
	/////////////////////
	
	let data2send = {};

	//Si click sur bouton d'upload
	$('button[id="confirmteam"]').on('click', function(e){
	
		//Récupération du nom de l'équipe
		if ($('input[name^="teamname"]').val().trim() != ""){
			teamname = $('input[name^="teamname"]').val().trim();
		}
		else{
			//TO DO : Pop-up champ obligatoire manquant
		}

		//We push team data to global data array
		data2send["teamname"] = teamname;

		//Récupération du nom du leader
		data2send["player"] = $('input[name="playerleader"]').val().trim();
		data2send["type"] = "creation";
		
		makeAJAXCall();

	});
	
	//Si click sur bouton d'upload
	$('button[id="confirmplayer"]').on('click', function(e){


		//Récupération du nom de l'équipe
		if ($('select[id="select_team"]').val().trim() != ""){
			teamname = $('select[id="select_team"]').val().trim();
		}
		else{
			//TO DO : Pop-up champ obligatoire manquant
		}

		//We push team data to global data array
		data2send["teamname"] = teamname;

		//Récupération du nom du leader
		data2send["player"] = $('input[name="playerme"]').val().trim();
		data2send["type"] = "addplayer";		
		
		makeAJAXCall()

	});		

function makeAJAXCall(){

		//Ajout du code de la partie récupéré sur la page
		var gamecode = $('p.session_code').text().trim();
		data2send["gamecode"] = gamecode;

		//DEBUG ONLY
		//console.dir(data2send);

		//Requête AJAX d'upload
		$.ajax({
			type: 'POST',
			dataType: "json",
			url: url,
			data: {teamcontent:data2send},
			beforeSend: function(){
				
				refreshTeams("add");				

			},
			success: function(json){

				//DEBUG ONLY
				//alert(json.status);

				if(json.status == "success" && json.creation == "OK"){
					
					refreshPlayers("add", data2send["teamname"], data2send["player"]);
					
					window.location.href = "/game.php";
				}
				else if(json.status == "success" && json.creation == "DENIED"){

					//Modal expliquant que tu as la main
					$('.article_popup_other').text("Cette équipe existe déjà, crées-en une nouvelle !");

					// Affichage de la modale
					modal.css("display", "flex");

				}
				else if(json.status == "success" && json.creation == "PLAYER_DENIED"){

					//Modal expliquant que tu as la main
					$('.article_popup_other').text("Ce nom de joueur est déjà pris, choisis-en un autre !");

					// Affichage de la modale
					modal.css("display", "flex");

				}
				else if(json.status == "success" && json.creation == "FULL_DENIED"){

					//Modal expliquant que tu as la main
					$('.article_popup_other').text("Cette équipe est déjà pleine !");

					// Affichage de la modale
					modal.css("display", "flex");

				}
				else if(json.status == "success" && json.creation == "TEAM_FULL_DENIED"){

					//Modal expliquant que tu as la main
					$('.article_popup_other').text("Partie déjà pleine, essaye plutôt de rejoindre une équipe");

					// Affichage de la modale
					modal.css("display", "flex");

				}				

			},
			error:function(x,e) {
				if (x.status==0) {
					alert('You are offline!!\n Please Check Your Network.');
				} else if(x.status==404) {
					alert('Requested URL not found.');
				} else if(x.status==500) {
					alert('Internel Server Error.');
				} else if(e=='parsererror') {
					alert('Error.\nParsing JSON Request failed.');
				} else if(e=='timeout'){
					alert('Request Time out.');
				} else {
					alert('Unknow Error.\n'+x.responseText);
				}
			}
		});

	}
});
