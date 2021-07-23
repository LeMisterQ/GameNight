////////////////////////////
///////Useful info//////////
////////////////////////////

/*
https://blog.engineering.publicissapient.fr/2017/11/14/asyncawait-une-meilleure-facon-de-faire-de-lasynchronisme-en-javascript/
https://www.npmjs.com/package/mysql2#first-query
https://github.com/mysqljs/mysql#getting-the-number-of-affected-rows
https://github.com/sidorares/node-mysql2#using-promise-wrapper
https://codeburst.io/node-js-mysql-and-async-await-6fb25b01b628
*/

////////////////////////////
///////Ecriture LOGS////////
////////////////////////////

function getLogTime(){
	const myDate = new Date().toISOString().replace(/T/, ' ').replace(/\..+/, '');
	return myDate;
}

////////////////////////////
///////Connexion BDD////////
////////////////////////////



const gamecode = "ROBBY";

const mysql = require('mysql2/promise');
const util = require('util');


const pool = mysql.createPool({
	host: "localhost",
	database: "gamenight",
	user: "adminweb",
	password: "VjoqoV",
	waitForConnections: true,
	connectionLimit: 10,
	queueLimit: 0
});


////////////////////////////
/////Websocket Server///////
////////////////////////////

const WebSocketServer = require("ws").Server;
const ws = new WebSocketServer( { port: 8080 } );
console.log("Server started...");

/////Websocket Broadcast function///////
function broadcast(bcast) {
   console.log(getLogTime() + " : Broadcasting to all : " + bcast);
   ws.clients.forEach(function each(client) {
       client.send(bcast);
    });
}

//////////////////////////////////
///////////GAME ID GET////////////
//////////////////////////////////

	async function getGameId(gc) {

		try{

			const select = await pool.query('SELECT gameid AS gameid FROM games WHERE game_code = ?', [gc])
			if (select[0].length < 1) {
				throw new Error('No result was not found');
			}
			else{
				return select[0][0].gameid;
			}

		}
		catch (e){
			console.log('Error', e);
		}

	}

//////////////////////////////////
/////ASYNC BUZZER MANAGEMENT//////
//////////////////////////////////

	async function checkBuzzingTeam(gi){

		try{

			const select = await pool.query('SELECT buzzing_team AS buzzing_team FROM games WHERE gameid = ?', [gi]);
			if (select[0].length < 1) {
				throw new Error('No result was not found');
			}
			else{
				return select[0][0].buzzing_team;
			}

		}
		catch (e){
			console.log('Error', e);
		}
	}

	async function recordBuzz(requesting_team){

		try{

			const gameid = await getGameId(gamecode);
			const response = {};

			const buzzing_team = await checkBuzzingTeam(gameid);
			if(buzzing_team == "none"){

				//On inscrit la buzzing_team en bdd, on ferme le buzzer et on desactive le buzzer pour l'équipe qui vient de buzzer
				await pool.query("UPDATE games SET buzzing_team = ? WHERE gameid = ?", [requesting_team, gameid]);
				await pool.query("UPDATE games SET buzzer_state = 'closed' WHERE gameid = ?", [gameid]);
				await pool.query("UPDATE teams SET can_buzz = 0 WHERE team_name = ?", [requesting_team]);

				response["type"] = "buzzerresult";
				response["teamname"] = requesting_team;
			}
			else{

				response["type"] = "buzzerresult";
				response["teamname"] = buzzing_team;
			}

			//On envoie la confirmation du buzz à tous
			broadcast(JSON.stringify(response));

		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function getCanBuzz(gi){

		try{
			const select = await pool.query('SELECT team_name AS team_name, can_buzz AS can_buzz FROM teams WHERE team_game = ?', [gi]);
			if (select[0].length < 1) {
				throw new Error(getLogTime() + ' : No team was found for buzzer activation');
			}
			else{
				return select[0];
			}
		}
		catch (e){
			console.log('Error', e);
		}
	}

	async function activateBuzz(){

		try{

			const gameid = await getGameId(gamecode);

			//On inscrit la buzzing_team en bdd, on ferme le buzzer et on desactive le buzzer pour l'équipe qui vient de buzzer
			await pool.query("UPDATE games SET buzzing_team = 'none' WHERE gameid = ?", [gameid]);
			await pool.query("UPDATE games SET buzzer_state = 'open' WHERE gameid = ?", [gameid]);

			const can_get_buzz = await getCanBuzz(gameid);
			const response = {};

			for(i=0; i<can_get_buzz.length; i++){

				var temp_team_array = {};
				temp_team_array["teamname"] = can_get_buzz[i]["team_name"];
				temp_team_array["canbuzz"] = can_get_buzz[i]["can_buzz"];
				response[i] = temp_team_array;

			}

			response["type"] = "activate";
			response["status"] = "OK";

			//On envoie la confirmation du buzz à tous
			broadcast(JSON.stringify(response));

		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function resetBuzz(actor2reset){

		try{

			const response = {};

			if(actor2reset == "all"){
				//On met à jour les infos de la capacité à buzzer en bdd
				await pool.query("UPDATE teams SET can_buzz = 1");

			}
			else{
				await pool.query("UPDATE teams SET can_buzz = 1 WHERE team_name = ?", [actor2reset]);

			}

			response["type"] = "reset";
			response["actor"] = actor2reset;

			//On envoie qui a été reset à tous
			broadcast(JSON.stringify(response));

		}
		catch (e){
			console.log('Error', e);
		}

	}

//////////////////////////////////
////////ASYNC TEAM REFRESH////////
//////////////////////////////////

	async function getTeamId(gid, teamname){

		try{

			const select = await pool.query("SELECT teamid AS teamid FROM teams WHERE team_game = ? AND team_name = ?", [gid, teamname]);
			if (select[0].length < 1) {				
				throw new Error('No result was not found');
			}
			else{
				
				return select[0][0].teamid;

			}

		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function getTeamPlayers(tid){

		try{

			const select = await pool.query('SELECT player_name AS player_name FROM players WHERE player_team = ?', [tid]);
			if (select[0].length < 1) {
				throw new Error('No result was not found');
			}
			else{
				return select[0];

			}

		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function getTeamsContents(gid){

		try{

			let teamArray = {};
			let playersArray = {};
			const teamsContents = {};

			const select = await pool.query("SELECT teamid AS teamid, team_name AS team_name, team_points AS team_points FROM teams WHERE team_game = ?", [gid]);
			if (select[0].length < 1) {
				throw new Error('No result was not found');
			}
			else{

				for(i=0; i < select[0].length; i++){
					teamArray["teamname"] = select[0][i].team_name;
					teamArray["points"] = select[0][i].team_points;
					const currentPlayers = await getTeamPlayers(select[0][i].teamid);
					
					//On loop dans les players pour les ajouter à leur team respective
					for(j=0; j<currentPlayers.length; j++){						
						playersArray[j] = currentPlayers[j].player_name;						
					}
					
					teamArray["players"] = playersArray;
					
					//On remplit l'array principal
					teamsContents[i] = teamArray;
					
					//On nettoie les arrays temporaires avant la prochaine loop
					playersArray = {};
					teamArray = {};
				}

				return teamsContents;

			}

		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function updateTeamsInGame(gid){

		try{
			
			const select = await pool.query('UPDATE games SET game_teams_number = game_teams_number - 1 WHERE gameid = ?', [gid]);
			if (select[0].length < 1) {
				throw new Error('No result was not found');
			}

		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function deleteTeam(team, gid){

		try{
			
			const select = await pool.query('DELETE FROM teams WHERE team_name = ? AND team_game = ?', [team, gid]);
			if (select[0].length < 1) {
				throw new Error('No result was not found');
			}
			
			await updateTeamsInGame(gid);

		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function getTeamsNumber(gid){

		try{
			
			const select = await pool.query('SELECT count(*) AS teams_number FROM teams WHERE team_game = ?', [gid]);
			if (select[0].length < 1) {
				return 0;
			}
			else{
				return select[0][0].teams_number;
			}
			

		}
		catch (e){
			console.log('Error', e);
		}

	}	

	async function refreshTeams(operation, teamImpacted){

		try{
			
			const gameid = await getGameId(gamecode);

				if(teamImpacted != "none" && operation == "delete"){
					
					const deletedTeam = await deleteTeam(teamImpacted, gameid);
					console.log("Deleted Team : " + teamImpacted);				
					
				}
				
				const teams_number = await getTeamsNumber(gameid);
				
				//Si le nombre de team n'est pas nul, on continue, sinon inutile
				if(teams_number > 0){
					
					const response = await getTeamsContents(gameid);
					response["type"] = "newteam";
					response["operation"] = operation;

					if(teamImpacted != "none" && operation == "delete"){
						response["team"] = teamImpacted;
					}

					//On envoie l'info
					broadcast(JSON.stringify(response));
					
					//Rafraichit le selecteur d'équipe dans la page de register
					getTeamsList();	
					
				}
				else if(teamImpacted != "none" && operation == "delete"){
					
					//Utile pour la page admin quand la dernière team s'en va
					const response = {};
					response["type"] = "newteam";
					response["operation"] = operation;
					response["team"] = teamImpacted;
					
					//On envoie l'info
					broadcast(JSON.stringify(response));
					
					//Rafraichit le selecteur d'équipe dans la page de register
					getTeamsList();	
					
					//Renvoie le resultat (utile pour d'autres raisons)
					return response;
				}

		}
		catch (e){
			console.log('Error', e);
		}

	}
	
	async function refreshPlayers(operation, teamImpacted, playerImpacted){
		
		try{
			
			if(operation == "add"){
				
				const response = await addPlayer(teamImpacted, playerImpacted);
				response["type"] = "newplayer";
				response["operation"] = operation;
				response["team"] = teamImpacted;
				
				//On envoie l'info
				broadcast(JSON.stringify(response));
			
			}
			else if(operation == "delete"){
				
				const response = {};
				await deletePlayer(teamImpacted, playerImpacted);
				response["type"] = "newplayer";
				response["operation"] = operation;
				response["team"] = teamImpacted;
				response["player"] = playerImpacted;
				
				//On envoie l'info
				broadcast(JSON.stringify(response));
				
			}
			
		}
		catch (e){
			console.log('Error', e);
		}
		
	}

	async function addPlayer(team, player){
		
		try{
			
			const gameid = await getGameId(gamecode);
			
			const teamId = await getTeamId(gameid, team);
			
			const playerTable = {};
			
			const select = await pool.query('SELECT player_name AS player_name FROM players WHERE player_team = ? AND player_name = ?', [teamId, player]);
			if (select[0].length < 1) {
				throw new Error('No result was inserted');
			}
			else{
				
				playerTable["player"] = select[0][0].player_name;
				return playerTable;
				
			}
			
		}
		catch (e){
			console.log('Error', e);
		}
		
	}

	async function deletePlayer(team, player){
		
		try{
			
			const gameid = await getGameId(gamecode);
			
			const teamId = await getTeamId(gameid, team);
			
			const playerTable = {};
			
			const select = await pool.query('DELETE FROM players WHERE player_team = ? AND player_name = ?', [teamId, player]);
			if (select[0].length < 1) {
				throw new Error('No result was inserted');
			}
			
		}
		catch (e){
			console.log('Error', e);
		}
		
	}
	
	async function getTeamsOnly(gid){

		try{
			
			const answer_table = {};

			const select = await pool.query("SELECT team_name AS team_name FROM teams WHERE team_game = ?", [gid]);
			if (select[0].length < 1) {
				
				answer_table[0] = "none";
				return answer_table;
			}
			else{

				for(i=0; i < select[0].length; i++){
					answer_table[i] = select[0][i].team_name;
				}
				
				return answer_table;

			}

		}
		catch (e){
			console.log('Error', e);
		}

	}
	
	async function getTeamsList(){

		try{
			
			const gameid = await getGameId(gamecode);
			
			const response = await getTeamsOnly(gameid);
			response["type"] = "request";
			response["operation"] = "teamlist";
	
			//On envoie l'info
			broadcast(JSON.stringify(response));

		}
		catch (e){
			console.log('Error', e);
		}

	}	
	
//////////////////////////////////
////////ASYNC POINT REFRESH////////
//////////////////////////////////

	async function addPoints(team){

		try{
			
			const select = await pool.query('UPDATE teams SET team_points = team_points + 1 WHERE team_name = ?', [team]);
			if (select[0].length < 1) {
				throw new Error('No result was not updated');
			}
			
		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function subPoints(team){

		try{
			
			const select = await pool.query('UPDATE teams SET team_points = team_points - 1 WHERE team_name = ?', [team]);
			if (select[0].length < 1) {
				throw new Error('No result was not updated');
			}
			
		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function getTeamPoints(team){

		try{

			const select = await pool.query("SELECT team_points AS team_points FROM teams WHERE team_name = ?", [team]);
			if (select[0].length < 1) {
				throw new Error('No result was not found');
			}
			else{

				return select[0][0].team_points;

			}

		}
		catch (e){
			console.log('Error', e);
		}

	}
	
	async function refreshPoints(operation, team){

		try{
			
			const gameid = await getGameId(gamecode);
			
			if(operation == "add"){
				
				await addPoints(team);
				const response = {};
				response["points"] = await getTeamPoints(team);
				response["type"] = "newpoints";
				response["operation"] = operation;
				response["team2impact"] = team;
				
				//On envoie l'info
				broadcast(JSON.stringify(response));
			
			}
			else if(operation == "sub"){
				
				await subPoints(team);
				const response = {};
				response["points"] = await getTeamPoints(team);
				response["type"] = "newpoints";
				response["operation"] = operation;
				response["team2impact"] = team;
				
				//On envoie l'info
				broadcast(JSON.stringify(response));
				
			}

		}
		catch (e){
			console.log('Error', e);
		}

	}
	
///////////////////////////////
////////ASYNC SOUND FWD////////
///////////////////////////////

	
	async function fwdSoundReq(sound, operation){

		try{			
			
			const response = {};
			response["type"] = "sound";
			response["soundname"] = sound;
			response["operation"] = operation;
		
			//On envoie l'info
			broadcast(JSON.stringify(response));
			
		}
		catch (e){
			console.log('Error', e);
		}

	}

///////////////////////////////
////////ASYNC SOUND FWD////////
///////////////////////////////

	async function getAnswersList(){

		try{
			
			const gameid = await getGameId(gamecode);
			let answer_table = {};

			const select = await pool.query("SELECT answer_img FROM answers WHERE answers_game = ?", [gameid]);
			if (select[0].length < 1) {
				throw new Error('No result was not found');
			}
			else{

				for(i=0; i < select[0].length; i++){
					answer_table[i] = select[0][i].answer_img;
				}
				
				return answer_table;

			}

		}
		catch (e){
			console.log('Error', e);
		}

	}
	
	async function fwdImgReq(img){

		try{
			
			if(img == "all"){
				
				const response = await getAnswersList();
				response["type"] = "img";
				response["operation"] = "populate";
			
				//On envoie l'info
				broadcast(JSON.stringify(response));
			}
			else{
				
				const response = {};
				response["type"] = "img";
				response["imgname"] = img;
				response["operation"] = "display";
			
				//On envoie l'info
				broadcast(JSON.stringify(response));				
				
			}
			
			
		}
		catch (e){
			console.log('Error', e);
		}

	}

	async function fwdVidReq(vid){

		try{
			
				const response = {};
				response["type"] = "video";
				response["vidname"] = vid;
				response["operation"] = "play";
			
				//On envoie l'info
				broadcast(JSON.stringify(response));
				
		}
		catch (e){
			console.log('Error', e);
		}

	}
	
//////////////////////////////////
///////Websocket On connect///////
//////////////////////////////////	

//Lorsqu'un socket est ouvert pour un client
ws.on('connection', function (ws) {

	//Si le protocole est buzzerInfo
	if(ws.protocol == "buzzerInfo"){

		console.log(getLogTime() + " : Browser required protocol : " + ws.protocol);

		ws.on("message", function (str) {
			//Message que le server a reçu en JSON
			var ob = JSON.parse(str);

				//On check la validité du type de message reçu
				switch(ob.type) {

					case "buzzerresult":

						recordBuzz(ob.teamname);

					break;

					case "activate":

						activateBuzz();

					break;

					case "reset":

						resetBuzz(ob.actor);

					break;
				}

		});
	}
	//Si le protocole est teamsInfo
	else if(ws.protocol == "teamsInfo"){

		console.log(getLogTime() + " : Browser required protocol : " + ws.protocol);

		ws.on("message", function (str) {

			//Message que le server a reçu en JSON
			var ob = JSON.parse(str);

			//On check la validité du type de message reçu
			switch(ob.type) {
				case "newteam":
					
					//Rafraichit la liste des équipes dans la page Game pour ceux qui y sont déjà
					refreshTeams(ob.operation, ob.team2impact);		

				break;
				
				case "newplayer":
					
					//Rafraichit la liste des joueurs de l'équipe demandée
					refreshPlayers(ob.operation, ob.team2impact, ob.player2impact);		

				break;				
				
				case "newpoints" :
				
					refreshPoints(ob.operation, ob.team2impact);
				
				break;

				case "request" :
				
					getTeamsList();
					
				
				break;
			}
		});
	}
	//Si le protocole est soundInfo
	else if(ws.protocol == "soundInfo"){
		
		console.log(getLogTime() + " : Browser required protocol : " + ws.protocol);

		ws.on("message", function (str) {

			//Message que le server a reçu en JSON
			var ob = JSON.parse(str);

			//On check la validité du type de message reçu
			switch(ob.type) {
				
				case "sound":
					
					fwdSoundReq(ob.soundname, ob.operation);

				break;

			}
		});		

	}
	//Si le protocole est imgInfo
	else if(ws.protocol == "imgInfo"){
		
		console.log(getLogTime() + " : Browser required protocol : " + ws.protocol);
		
		ws.on("message", function (str) {

			//Message que le server a reçu en JSON
			var ob = JSON.parse(str);

			//On check la validité du type de message reçu
			switch(ob.type) {
				
				case "img":
					
					fwdImgReq(ob.imgname);

				break;
				
				case "video":
					
					fwdVidReq(ob.vidname);

				break;				

			}
		});	
		
	}

	//Gestion de la cloture de connexion
    ws.on("close", function() {
        console.log(getLogTime() + " : Browser gone.")
    })
});
