$(document).ready(function(){

	//Helpful content on WebSockets
	//https://www.xul.fr/html5/websocket.php
	//https://github.com/websockets/ws#sending-and-receiving-text-data
	//https://github.com/websockets/ws/blob/HEAD/doc/ws.md#event-listening

	/////////////////////////
	//Gestion du Disconnect//
	/////////////////////////

	$('h1[class="alternative"]').click(function(){
		
		let countMyPlayers = 0;
		
		//Si lors de la demande de Quit il n'y a plus que 1 player, alors on tue la team
		$('div[class^="team_container"]').each(function(){
			
			if($(this).text() == getMyTeam()){
				
				countMyPlayers = $(this).next().find('.playername').length;
				
				
			}						
		});

		if(countMyPlayers == 1){

			//On demande le delete de sa team et le refresh pour les autres encore en jeu
			refreshTeams("delete", getMyTeam());
		
		}		

		//On notifie tout le monde de son départ
		refreshPlayers("delete", getMyTeam(), getMePlayer());

		window.location.href = "/disconnect.php";

	});

	////////////////////////////
	////////////////////////////
	////////MODALE POPUP////////
	////////////////////////////
	////////////////////////////

	function closeModal(el){
		el.css("display", "none");
		$('section').css("filter", "");
	}

	// Ref aux modale
	modal = $('#myModal');
	modalimg = $('#myModalImg');
	modalvid = $('#myModalVid');

	// span qui ouvre la modale
	span = $(".close_popup");

	// Click sur X de la modale : fermer la modale
	span.unbind().click(function() {
		closeModal(modal);
		closeModal(modalimg);
	});
	
	// Click sur button de la modale : fermer la modale
	$('.closebtn').unbind().click(function() {
		closeModal(modal);
	});

	// Click en dehors de la modale : fermer la modale
	modalimg.unbind().click(function() {
		closeModal(modalimg);		
	});
	
	// Click en dehors de la modale vidéo : fermer la modale
	modalvid.unbind().click(function() {
		closeModal(modalvid);		
	});	
	
	////////////////////////////
	////////////////////////////
	/////USEFUL FUNCTIONS///////
	////////////////////////////
	////////////////////////////

	//Check for Mobile
	var isMobile = false; //initiate as false
	// device detection
	if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
		|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
		isMobile = true;
	}

	//Réduction des tailles de polices si on a plus de joueurs
	//Pour les noms de joueur
	if(isMobile == false){
		$('div[class^="player_container"]').each(function(){
			var players_number = $(this).find(".playername").length;

			if(players_number == 4){
				$(this).find(".playername").css("font-size", "1.25vw");
				$(this).find(".playername").css("width", "40%");
			}
			else if(players_number == 3){
				$(this).find(".playername").css("font-size", "1.25vw");
			}
		});
	}
	
	//Nettoye les classes liées au clignotement de l'équipe qui avait précédement buzzé
	function cleanBuzzingTeam(){

		setTimeout(function(){
			$('div[class*="buzzingteam"]').each(function(){
				$(this).toggleClass("buzzingteam", false );
			});
			$('div[class*="buzzingborder"]').each(function(){
				$(this).toggleClass("buzzingborder", false );
			});
		}, 10000);
	}	

	var thismessage;

	function getMePlayer(){
		var meplayer = $('div[id="me"]').text();
		return meplayer;
	}

	function getMyTeam(){
		var myteam = $('div[class^="team_container myteam"]').text();
		return myteam;
	}

	function sendMessage(wsobject, msg){
		wsobject.send(JSON.stringify(msg));
	}

	function dispMessage(contents) {
		//Modifie le contenu de la modale classique
		$('.article_popup_other').text(contents);

		// Affiche la modale classique
		modal.css("display", "flex");
	}

	function deactivateBuzzer(){
		$('div[class="buzzer_object"]').toggleClass("clicked", true ).toggleClass("buzzer_object", false );
		$('div[class="clicked"]').off( "click" );
	}

	function activateBuzzer(){
		$('div[class="clicked"]').toggleClass("clicked", false ).toggleClass("buzzer_object", true );
		$('div[class="buzzer_object"]').unbind().on( "click", function(){
			buzzInfo["type"] = "buzzerresult";
			buzzInfo["teamname"] = getMyTeam();
			sendMessage(ws, buzzInfo);
			playSound("buzzer");
		});
		
		//On scroll auto sur le buzzer
		$('html, body, main').animate({
			scrollTop: $('div[class="toptitle"]').offset().top
		}, 500);

	}

	function refreshTeams(operation, team){

		if(operation == "add"){
			
			let Ask4Teams = {};

			Ask4Teams["operation"] = operation;
			Ask4Teams["team2impact"] = "none";
			Ask4Teams["type"] = "newteam";
			sendMessage(wst, Ask4Teams);

		}
		else if(operation == "delete"){
			
			let Ask4Teams = {};

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
	
	//Constructeur pour le son
	function sound(src) {
		
		this.sound = document.createElement("audio");
		this.sound.src = src;
		this.sound.setAttribute("preload", "auto");
		this.sound.setAttribute("controls", "none");
		this.sound.setAttribute("muted", true);
		this.sound.style.display = "none";
		document.body.appendChild(this.sound);
		this.play = function(){
		this.sound.play();
		}
		this.stop = function(){
		this.sound.pause();
		}
		
	}
	
	//On simule un premier click dans la page
	//pour faire croire au browser que l'user a interagit avec la page
	//Cela permet d'activer le play de son sans interaction user
	//$('body').trigger( "click" );
	
	
	function playSound(type){

		let this_sound_table;
		
		//Nettoyage des anciens sons générés avant de continuer
		$('audio[name="generated"]').each(function(){
			$(this).remove();
		});
		
		if(type == "applause"){
			this_sound_table = ["/snd/applause.mp3"];
		}
		else if(type == "good"){
			this_sound_table = ["/snd/good.mp3"];
		}
		else if(type == "wrong"){
			this_sound_table = ["/snd/wrong.mp3"];
		}
		else if(type == "ring-"){
			this_sound_table = ["../snd/rings.mp3"];
		}
		else if(type == "ring+"){
			this_sound_table = ["../snd/ringB.mp3"];
		}
		else if(type == "buzzer"){
		
			var random_sound = 0;
			this_sound_table = ["../snd/buzzA.mp3", "../snd/buzzB.mp3", "../snd/buzzC.mp3", "../snd/buzzD.mp3", "../snd/buzzE.mp3", "../snd/buzzF.mp3"];
			random_sound = Math.floor(Math.random()*(this_sound_table.length -1 ));
		
		}
		
		if(type == "buzzer"){
			this_sound = new sound(this_sound_table[random_sound]);
		}
		else{
			this_sound = new sound(this_sound_table[0]);
		}

		this_sound.sound.setAttribute("muted", false);
		this_sound.sound.setAttribute("name", "generated");
		this_sound.play();

	}
	
	function stopSound(type){
		
		$('audio[name="generated"]').each(function(){
			$(this).sound.stop();
		});
	}
	
	function displayImg(answerImg){
				
		var answer_img = ["/img/answers/sound_effects/" + answerImg + ".png"];
		
		//Modal expliquant que tu as la main
		$('.modal_img').attr("src", answer_img);

		// Affichage de la modale
		modalimg.css("display", "flex");
		$('section').css("filter", "blur(35px)");
			
		
	}
	
	function displayVid(vid){

		// Affichage de la modale
		modalvid.css("display", "flex");
		$('section').css("filter", "blur(35px)");
		
		document.getElementsByTagName("video")[0].play();
		
	}	

	////////////////////////////
	////////////////////////////
	/////WEBSOCKET CLIENT///////
	////////////////////////////
	////////////////////////////


	////////////////////////////
	/////PROTOCOL Buzzer////////
	////////////////////////////

	var ws = new WebSocket("wss://homeq.fr/ws/", "buzzerInfo");

	ws.onmessage=function(event) {
		var message = JSON.parse(event.data);
		switch(message.type) {
			case "buzzerresult":
				//Si la teamname renvoyée est la notre, on a la main
				if(message.teamname == getMyTeam()){
					thismessage = "Tu as la main !";
				}
				else{
					if(canIbuzz == 0){
						thismessage = message.teamname + " prend la main. Toi t'as déjà buzzé!";
					}
					else if (canIbuzz == 1){
						thismessage = message.teamname + " a été plus rapide que toi !";
					}
				}
				//On desactive le buzzer
				deactivateBuzzer();

				//On affiche la modale avec l'info
				dispMessage(thismessage);
				
				$('div[class^="team_container"]').each(function(){
					
					if($(this).text() == message.teamname){
				
						//On active les classes spéciales de flickering de la team qui a le buzz
						let div2Scrool2 = $(this);
						$(this).toggleClass("buzzingteam", true);
						$(this).next().toggleClass("buzzingborder", true);

						//AutoScroll vers la team sur Mobile
						setTimeout(function(){
							if(isMobile == true){
								$('html, body, main').animate({
									scrollTop: div2Scrool2.offset().top - 100
								}, 750);
							}
						}, 200);

						//Nettoyage des teams qui ont buzzées au bout d'un certain temps
						cleanBuzzingTeam();
					
					}

				});				
				
			break;

			case "activate":

				if(message.status == "OK"){
					for(i = 0; i < Number(Object.keys(message).length - 2); i++){

						if(message[i].teamname == getMyTeam()){
							canIbuzz = message[i].canbuzz;
							if(message[i].canbuzz == 1){
								activateBuzzer();								
							}
						}

					}
				}
			break;

			case "reset":
				if(message.teamname == getMyTeam()){
					activateBuzzer();
				}
			break;
		}
	};

	////////////////////////////
	/////PROTOCOL Teams/////////
	////////////////////////////

	var wst = new WebSocket("wss://homeq.fr/ws/", "teamsInfo");

	wst.onopen = function (event) {

		//Demande un refresh au chargement
		refreshTeams("add", getMyTeam());

	};

	wst.onmessage=function(event) {
		var message = JSON.parse(event.data);
		switch(message.type) {
			case "newteam":

				if(message.operation == "add"){

					let messageLength = Number(Object.keys(message).length - 2);

					var current_playerdiv;
					let player_container;
					let team_container;
					let points_container;
					let table_container;
					let lowertable_div;

					for(i=0; i < messageLength; i++){

						//Si une équipe identique existe déjà, on verouille la création de div
						if($( "div:contains('" + message[i]["teamname"] + "')" ).length > 0){
							creation_allowed = false;
						}
						else{
							creation_allowed = true;
						}

						//Si la team n'a pas été trouvée, on peut l'ajouter
						if(creation_allowed == true){

						current_playerdiv = "";
						var players_number = Object.keys(message[i]["players"]).length;

							for(j=0; j < players_number; j++){
								current_playerdiv += '<div class="playername">' + message[i]["players"][j] + '</div>';
							}

							team_container = '<div class="team_container">' + message[i]["teamname"] + '</div>';
							player_container = '<div class="player_container">' + current_playerdiv + '</div>';
							
							if(isMobile == false){
							points_container = '<div class="points_container">' + message[i]["points"] + '<div class="points_container pts">pts</div></div>';
							}
							else{
							points_container = '<div class="points_holder"><div class="points_container">' + message[i]["points"] + '</div><div class="points_container pts">pts</div></div>';
							}
							
							lowertable_div = '<div class="lowertable">' + player_container + points_container + '</div>';
							table_container = '<div class="table_container">' + team_container + lowertable_div + '</div>';


							if( $(".table_container").length < 3 ){
								$( ".tableaux" ).eq(0).append(table_container);
							}
							else if($(".table_container").length > 2){
								$( ".tableaux" ).eq(1).append(table_container);
							}
						}
					}
				}
				else if (message.operation == "delete"){

					let team2delete = message.team;

					//On regarde les équipes en place et on delete celle qui correspond
					$('div[class="team_container"]').each(function(){

						if($(this).text() == team2delete){
							$(this).parent().remove();
						}

					});

				}

			break;
			
			case "newpoints":
			
				if(message.operation == "add" || message.operation == "sub"){
				
					let team2impact = message.team2impact;
				
					//On regarde les équipes en place et on modifie le score de l'équipe concernée
					$('div[class^="team_container"]').each(function(){

						if($(this).text() == team2impact){
							
							if(isMobile == true){
								$(this).next(".lowertable").find('div[class$="points_container"]').html(message.points);
							}
							else{
								$(this).next(".lowertable").find('div[class$="points_container"]').html(message.points + '<div class="points_container pts">pts</div>');
							}
						}

					});
				
				
				}
				
			break;

			case "newplayer":	
				
				if(message.operation == "add"){			
					
					let impactedTeam = message.team;
					let impactedPlayer = '<div class="playername">' + message.player + '</div>';					
					
					$('div[class="team_container myteam"]').each(function(){
						
						if($(this).text() == impactedTeam){
							
							$(this).next().find('.player_container').append(impactedPlayer);
							
							//On affiche la modale avec l'info
							thismessage = message.player + ' a rejoint ton équipe ! #yay';
							dispMessage(thismessage);
							
						}						
					});					
				}
				else if(message.operation == "delete"){
					
					let impactedTeam = message.team;
					let impactedPlayer = message.player;
					
					$('div[class="team_container myteam"]').each(function(){
						
						if($(this).text() == impactedTeam){
							
							$(this).next().find('.playername').each(function(){
								
								if($(this).text() == impactedPlayer){
									
									if(message.player != getMePlayer()){
									
										//On affiche la modale avec l'info que si le joueur qui a quitté n'est pas soi même
										thismessage = message.player + ' a quitté l\'équipe ! #rip';
										dispMessage(thismessage);
									
									}
									
									$(this).remove();
									
								}
								
							});
							
						}						
					});
					
				}
				
			break;
		}
	};

	////////////////////////////
	/////PROTOCOL Sounds////////
	////////////////////////////

	var wss = new WebSocket("wss://homeq.fr/ws/", "soundInfo");

	wss.onopen = function (event) {

	};

	wss.onmessage=function(event) {
		var message = JSON.parse(event.data);
		
		switch(message.type) {
			
			case "sound":
			
				switch(message.operation) {
					
					case "play":
				
						playSound(message.soundname);
						
					break;
					
					case "stop":
				
						
					break;					
				}
				
			break;

			
		}
	};

	////////////////////////////
	///////PROTOCOL Img/////////
	////////////////////////////

	var wsi = new WebSocket("wss://homeq.fr/ws/", "imgInfo");

	wsi.onopen = function (event) {
		
	};

	wsi.onmessage=function(event) {
		var message = JSON.parse(event.data);

		switch(message.type) {

			case "img":
			
				if(message.operation == "display"){
					displayImg(message.imgname);
				}				
			
			break;
			
			case "video":
			
				if(message.operation == "play"){
					displayVid(message.vidname);
				}				
			
			break;			
		}
	};

	////////////////////////////
	/////INTERACT Buzzer////////
	////////////////////////////

	let buzzInfo = {};
	let canIbuzz;


});