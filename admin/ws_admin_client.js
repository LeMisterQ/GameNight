$(document).ready(function(){

	////////////////////////////
	////////////////////////////
	////////MODALE POPUP////////
	////////////////////////////
	////////////////////////////

	// Ref à la modale
	modal = $('#myModal');

	// span qui ouvre la modale
	span = $(".close_popup");

	// Click sur X de la modale : fermer la modale
	span.unbind().click(function() {
		modal.css("display", "none");
		$('button[name="submit"]').attr("disabled",false);
	});


	// Click en dehors de la modale : fermer la modale
	modal.unbind().click(function() {
		$(this).css("display", "none");
		$('button[name="submit"]').attr("disabled",false);
	});

	////////////////////////////
	////////////////////////////
	//////USEFUL FUNCTIONS//////
	////////////////////////////
	////////////////////////////

	var thismessage;

	function sendMessage(wsobject, msg){
		wsobject.send(JSON.stringify(msg));
	}

	function dispMessage(contents) {
		//Modifie le contenu de la modale classique
		$('.article_popup_other').text(contents);

		// Affiche la modale classique
		modal.css("display", "flex");
	}

	function refreshTeams(operation, team){

		if(operation == "add"){

			Ask4Teams["operation"] = operation;
			Ask4Teams["team2delete"] = "none";
			Ask4Teams["type"] = "newteam";
			sendMessage(wst, Ask4Teams);

		}
		else if(operation == "delete"){

			Ask4Teams["operation"] = operation;
			Ask4Teams["team2delete"] = team;
			Ask4Teams["type"] = "newteam";
			sendMessage(wst, Ask4Teams);
		}

	}

	function populateAnswers(){
		
		let letAsk4Img = {};
		let thisImgName = "all";

		letAsk4Img["type"] = "img";
		letAsk4Img["imgname"] = thisImgName;

		sendMessage(wsi, letAsk4Img);
		
		
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

				thismessage = message.teamname + " a pris la main";

				//On affiche la modale avec l'info
				dispMessage(thismessage);
				break;

			case "activate":
				if(message.status == "OK"){
					console.log("Buzzer reactivated for all");
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
		refreshTeams("add", "none");

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

							lower_table = '<div class="lowertable">';
							team_container = '<div class="team_container">' + message[i]["teamname"] + '</div>';
							points_container = '<div class="gamestate_container"><div class="points_container">' + message[i]["points"] + '</div><div class="points_container pts">pts</div></div>';
							points_modifier = '<div class="gamestate_container"><div class="points add">+1</div><div class="points sub">-1</div></div>';
							reset_the_buzzer = '<div class="reset-buzz">RB</div>';
							lower_table_end = '</div>';
							global_storage = lower_table + team_container + points_container + points_modifier + reset_the_buzzer + lower_table_end;

							$('.container_main').append(global_storage);
							activateIndividualReset();
							activatePointsModifier();
						}
					}
				}
				else if (message.operation == "delete"){

					let team2delete = message.team;

					//On regarde les équipes en place et on delete celle qui correspond
					$('div[class="lowertable"]').each(function(){

						if($(this).children(".team_container").text() == team2delete){
							$(this).remove();
						}

					});

				}

			break;

			case "newpoints":

				if(message.operation == "add" || message.operation == "sub"){

					let team2impact = message.team2impact;

					//On regarde les équipes en place et on modifie le score de l'équipe concernée
					$('div[class="lowertable"]').each(function(){

						if($(this).children(".team_container").text() == team2impact){

							$(this).find('div[class$="points_container"]').text(message.points);

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

	////////////////////////////
	///////PROTOCOL Img/////////
	////////////////////////////

	var wsi = new WebSocket("wss://homeq.fr/ws/", "imgInfo");

	wsi.onopen = function (event) {
		populateAnswers();
	};

	wsi.onmessage=function(event) {
		var message = JSON.parse(event.data);

		switch(message.type) {

			case "img":
			
				if(message.operation == "populate"){
				
					let answers_count = Number(Object.keys(message).length - 2);
					let answers_storage = "";

					for(i=0; i<answers_count; i++){
						answers_storage += '<option value="' + message[i] + '">';
					}
					console.dir(answers_storage);
					$("datalist").append(answers_storage);
				}
			
			break;
		}
	};

	////////////////////////////
	////////////////////////////
	///////INTERACTIONS/////////
	////////////////////////////
	////////////////////////////

	$('div[class="buzzer_object"]').unbind().click(function(){
		let thisload = {};
		thisload["type"] = "activate";
		sendMessage(ws, thisload);
	});

	$('div[class="buzzer_all"]').unbind().click(function(){
		let thisload = {};
		thisload["type"] = "reset";
		thisload["actor"] = "all";
		sendMessage(ws, thisload);
	});

	function activateIndividualReset(){
		$('div[class="reset-buzz"]').unbind().click(function(){

			let thisload = {};
			var this_teamname = $(this).parent().find(".team_container").text();

			thisload["type"] = "reset";
			thisload["actor"] = this_teamname;
			sendMessage(ws, thisload);
		});
	}
	///////////////////////////////////
	/////INTERACT REFRESH TEAMS////////
	///////////////////////////////////

	let Ask4Teams = {};

	///////////////////////////////////
	/////INTERACT POINTS MODIFIERS/////
	///////////////////////////////////

	function activatePointsModifier(){
		//Selon le div cliqué, on fait un add ou un sub
		$('div[class="points add"]').unbind().click(function(){

			let Ask4Points = {};
			let Ask4Sound = {};

			//On prend la ref du bouton cliqué
			var this_teamname = $(this).parents(".lowertable").find(".team_container").text();

			Ask4Points["team2impact"] = this_teamname;
			Ask4Points["operation"] = "add";
			Ask4Points["type"] = "newpoints";
			Ask4Sound["type"] = "sound";
			Ask4Sound["operation"] = "play";
			Ask4Sound["soundname"] = "ring+";

			sendMessage(wst, Ask4Points);
			sendMessage(wss, Ask4Sound);

		});

		$('div[class="points sub"]').unbind().click(function(){

			let Ask4Points = {};
			let Ask4Sound = {};

			//On prend la ref du bouton cliqué
			var this_teamname = $(this).parents(".lowertable").find(".team_container").text();

			Ask4Points["team2impact"] = this_teamname;
			Ask4Points["operation"] = "sub";
			Ask4Points["type"] = "newpoints";
			Ask4Sound["type"] = "sound";
			Ask4Sound["operation"] = "play";
			Ask4Sound["soundname"] = "ring-";

			sendMessage(wst, Ask4Points);
			sendMessage(wss, Ask4Sound);

		});
	}

	//////////////////////////
	/////INTERACT SOUNDS//////
	//////////////////////////

	$('.wrong').on("click", function(){

			let Ask4Sound = {};

			Ask4Sound["type"] = "sound";
			Ask4Sound["operation"] = "play";
			Ask4Sound["soundname"] = "wrong";

			sendMessage(wss, Ask4Sound);

	});
	$('.good').on("click", function(){

			let Ask4Sound = {};

			Ask4Sound["type"] = "sound";
			Ask4Sound["operation"] = "play";
			Ask4Sound["soundname"] = "good";

			sendMessage(wss, Ask4Sound);

	});

	$('.applause').on("click", function(){

			let Ask4Sound = {};

			Ask4Sound["type"] = "sound";
			Ask4Sound["operation"] = "play";
			Ask4Sound["soundname"] = "applause";

			sendMessage(wss, Ask4Sound);

	});

	//////////////////////////
	/////INTERACT IMAGES//////
	//////////////////////////

	$('.go_answer').on("click", function(){

		let letAsk4Img = {};
		let thisImgName = $('input[id="select_answer"]').val();

		letAsk4Img["type"] = "img";
		letAsk4Img["imgname"] = thisImgName;

		sendMessage(wsi, letAsk4Img);

	});
	
	$('#bonus').on("click", function(){

		let letAsk4Vid = {};

		letAsk4Vid["type"] = "video";
		letAsk4Vid["vidname"] = "bonus";

		sendMessage(wsi, letAsk4Vid);

	});	


});