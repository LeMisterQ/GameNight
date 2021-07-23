<?php
	//ini_set('display_errors',1);

	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');

	//Header pour le JSON content type : pas utile car la requete AJAX comporte le dataType:json

	//Je récupère l'array JSON directement
	$register_teamcontent = $_POST['teamcontent'];

	//Si le JSON est bien un JSON, on continue le traitement de l'info reçue
	if (empty($register_teamcontent)) {
		//Si le contenu est vide, on retourne un échec
		$success_msg['status'] = 'error';
	}
	else {

		//DEBUG ONLY : on écrit le json dans un log
		/*
		$txtlog = print_r($_POST['teamcontent'], true);  // mettre le résultat de print_r() dans une variable avec le paramètre true
 		$f = fopen('../json_received.log', "w+");   // ouverture du fichier
		fwrite($f, $txtlog);                        // écriture dans le fichier
		fclose($f);
		*/
		
		//Je tente la connexion BDD
		include_once($root . '/scripts/bdd_connect.php');
		
		//Requêtes de vérfication : la partie (game) avec le code données n'est-elle pas pleine ?
		$req22 = $bdd->prepare('SELECT game_teams_number FROM games WHERE game_code = :game_code');
		$req22->execute(array(
			'game_code' => $gamecode));
			
		//Inscription des données récupérées dans le tableau fetch
		$donnees22 = $req22->fetch();
		$req22->closeCursor();
		//Récupération des résultats de la requête
		$fetched_game_teams_number = $donnees22['game_teams_number'];
		
		//Si il y a dejà 6 équipes, on autorise pas à continuer
		if ($fetched_game_teams_number < 6) {
		

			//Check si on a bien un teamname, si oui on continue
			if (array_key_exists('teamname', $register_teamcontent)) {
					
				//Récupération de teamname depuis le JSON et inscription dans la SESSION PHP			
				$teamname = trim($register_teamcontent["teamname"]);
				$meplayer =  trim($register_teamcontent['player']);
							
				//On check directement si la team existe déjà en BDD, 
				//Si oui, on refuse l'accès à la page

				$req = $bdd->prepare("
				SELECT teamid, team_name FROM teams WHERE team_name = :team_name;
				");
				$req->execute(array(
					'team_name' => $teamname));

				//Inscription des données récupérées dans le tableau fetch
				$donnees = $req->fetch();
				$req->closeCursor();
				
				//Récupération des résultats de la requête
				$fetched_teamname = $donnees['team_name'];
				$fetched_team_uid = $donnees['teamid'];
				
				if (!empty($fetched_teamname)){

					//Si on demande juste l'ajout d'un joueur à team existante et que c'est valide
					if ($register_teamcontent['type'] == "addplayer"){
						
						$req04 = $bdd->prepare("
						SELECT count(*) AS playerscount FROM players WHERE player_team = :player_team;
						");
						$req04->execute(array(
							'player_team' => $fetched_team_uid));

						//Inscription des données récupérées dans le tableau fetch
						$donnees = $req04->fetch();
						$req04->closeCursor();
						
						//Récupération des résultats de la requête
						$fetched_players_number = $donnees['playerscount'];
						
						if($fetched_players_number < 4){
							
							$req05 = $bdd->prepare("
							SELECT player_name FROM players WHERE player_name = :player_name;
							");
							$req05->execute(array(
								'player_name' => $meplayer));

							//Inscription des données récupérées dans le tableau fetch
							$donnees = $req05->fetch();
							$req05->closeCursor();
							
							//Récupération des résultats de la requête
							$fetched_meplayer = $donnees['player_name'];
							
							//Si ce joueur n'existe pas, alors on continue
							if(empty($fetched_meplayer)){
							
								//Insertion en BDD du player
								$req10 = $bdd->prepare("
								INSERT INTO players(player_team, player_name) VALUES (:player_team, :player_name);
								");
								$req10->execute(array(
									'player_team' => $fetched_team_uid,
									'player_name' => $meplayer));

								$req10->closeCursor();
								
								$_SESSION['teamname'] = $teamname;
								$_SESSION['player'] = $meplayer;
								
								$success_msg = ['status' => 'success', 'creation' => 'OK'];
							}
							else{
								//La joueur existe déjà
								$success_msg = ['status' => 'success', 'creation' => 'PLAYER_DENIED'];
							}
						}
						else{
							
							//La team est full
								$success_msg = ['status' => 'success', 'creation' => 'FULL_DENIED'];
							
						}
				
					}
					else{
						
						//La team existe déjà
						$success_msg = ['status' => 'success', 'creation' => 'DENIED'];
					}
				}
				//Si l'entrée n'existe pas, alors on continue, sinon accès DENIED
				else if (empty($fetched_teamname) || $fetched_teamname == ""){

					if ($register_teamcontent['type'] == "creation"){

						//On recupère le gameid avec le gamecode
						$req0 = $bdd->prepare("
						SELECT gameid FROM games WHERE game_code = :game_code;
						UPDATE games SET game_teams_number = game_teams_number + 1 WHERE game_code = :game_code;
						");
						$req0->execute(array(
							'game_code' => $gamecode));

						//Inscription des données récupérées dans le tableau fetch
						$donnees0 = $req0->fetch();
						$req0->closeCursor();

						//Récupération des résultats de la requête
						$fetched_gameid = $donnees0['gameid'];

						//Insertion en BDD de la team
						$req1 = $bdd->prepare("
						INSERT INTO teams(team_name, team_points, team_game) VALUES (:team_name, 0, :team_game);
						");
						$req1->execute(array(
							'team_name' => $teamname,
							'team_game' => $fetched_gameid));
						
						$req1->closeCursor();
						
						//On recupère l'id de la team créée
						$req2 = $bdd->prepare("
						SELECT teamid FROM teams WHERE team_name = :team_name;
						");
						$req2->execute(array(
							'team_name' => $teamname));

						//Inscription des données récupérées dans le tableau fetch
						$donnees2 = $req2->fetch();
						$req2->closeCursor();

						//Override de la variable déjà déclarée plus haut (car on est dans le cas d'une team créée)
						$fetched_teamid = $donnees2['teamid'];

						//Insertion en BDD du player
						$req3 = $bdd->prepare("
						INSERT INTO players(player_team, player_name) VALUES (:player_team, :player_name);
						");
						$req3->execute(array(
							'player_team' => $fetched_teamid,
							'player_name' => $meplayer));

						$req3->closeCursor();

						//On crée la session avec la team
						$_SESSION['teamname'] = $teamname;
						$_SESSION['player'] = $meplayer;
						
						$success_msg = ['status' => 'success', 'creation' => 'OK'];
						
					}
					
				}
				else{
					
					//La team existe déjà
					$success_msg = ['status' => 'success', 'creation' => 'DENIED'];
					
				}	
			}
			else{
			
			//Si le contenu est vide, on retourne un échec
			$success_msg['status'] = 'error';
			
			}
		}
		else{
			
			//Trop d'équipes
			$success_msg = ['status' => 'success', 'creation' => 'TEAM_FULL_DENIED'];
			
		}		
	}

	//DEBUG ONLY
	//$success_msg['status'] = 'You provided the value ' . $register_teamcontent[$current_player_name];

	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	//Si tout OK, en fin de code on retourne un succès
	echo json_encode($success_msg);
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
?>