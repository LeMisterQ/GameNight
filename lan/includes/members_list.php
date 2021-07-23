<?php
//ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
if (isset($_SESSION['pseudo']) OR isset($_SESSION['email'])){
	
	//On inclue la bdd et on va chercher le chemin de l'avatar de l'utilisateur
	include_once('bdd_connect.php');
		
	//Fonction de récupération des images d'une LAN donnée
	function generate_members_list($bdd){

		//Tableau de stockage des images de la liste récupérée
		$this_lan_members_list = array();
		
		//Dossier des images gallery stockées
		$dossier_avatar = "../img/uploads/";
		$dossier_rank = "../img/rl/";

		$reqx = $bdd->prepare("
		SELECT a.uid, a.pseudo, a.title, b.img_type, b.img_name, c.rank, c.subrank, c.rank_img
		FROM members AS a
		INNER JOIN user_img AS b ON a.uid = b.uid
		INNER JOIN rl_img AS c ON b.uid = c.uid
		WHERE img_type = 'avatar';
		");
		$reqx->execute();
		
		//Inscription des données récupérées dans le tableau fetch
		//FETCHASSOC important car permet un accès plus malin aux données array
		$members_list = $reqx->fetchAll(PDO::FETCH_ASSOC);

		$members_list_lenght = sizeof($members_list);		

		//Je ferme la connexion bdd
		$reqx->closeCursor();

		for ($x = 0; $x < $members_list_lenght; $x++)
		{
						
			//Récupération des résultats de la requête
			$fetched_uid = $members_list[$x]['uid'];
						
			//Requête pour récupérer la liste des skills
			$reqy = $bdd->prepare("
					SELECT skill_value, name FROM member_skills
					INNER JOIN skills_name on member_skills.skill_id = skills_name.skill_id
					WHERE uid = :uid ORDER BY name;
					");
					$reqy->execute(array(
						'uid' => $fetched_uid));
						
			//Inscription des données récupérées dans le tableau fetch		
			$competences = $reqy->fetchAll(PDO::FETCH_ASSOC);
			
			//Fin de connexion BDD
			$reqy->closeCursor();
			
			//Taille du tableau de fetch pour ce user
			$competences_list_lenght = sizeof($competences);
			
			//Initialisation des variables de stockage
			$skills_array = "";
			$values_array = "";
			
			//On loop à travers toutes les compétences et leurs notes respectives, qu'on stock dans la chaine qui va former le script du canvas
			for ($y = 0; $y < $competences_list_lenght; $y++){
				
				//Test de récupération des données issues du tableau de fetch
				if( $y == $competences_list_lenght - 1){
					$skills_array .= '""';
					$values_array .= $competences[$y]['skill_value'];
				}
				else {
					$skills_array .= '"", ';
					$values_array .= $competences[$y]['skill_value'] . ', ';
				}
				
			}
			
			$this_spider_chart_core = '
			<canvas id="myRadarChart_' . $members_list[$x]['pseudo'] . '" width="400" height="400"></canvas>
			<script>
				var ctx = document.getElementById("myRadarChart_' . $members_list[$x]['pseudo'] . '").getContext("2d");
				var myRadarChart_' . $members_list[$x]['pseudo'] . ' = new Chart(ctx, {
					"type":"radar",
					"data":{
						"labels":[';
			$this_spider_chart_core .= $skills_array . '],
						"datasets":[					
							{
								"data":[';
			$this_spider_chart_core .= $values_array . '],								
								"fill":true,
								"lineTension":0.2,
								"backgroundColor":"#E9E9E9",
								"borderColor":"gray",
								"pointBackgroundColor":"gray",
								"pointBorderColor":"whitesmoke",
								"pointHoverBackgroundColor":"gray",
								"pointHoverBorderColor":"white",
								"pointBorderWidth":"0.1",
								"pointHitRadius":"0.01",
							}
						]},
					options:
						{
							scale:
							{
								ticks: {
									//Radar values start at 0
									beginAtZero: true,
									min: 0,
									max: 10,
									stepSize: 10,
									//Display scale of radar values 
									display: false
								},
								//Display scale of rader (only one scale)
								display: true,
								gridLines: {
									color: "#E9E9E9",
									lineWidth: "3"
								},
								angleLines: {
									color: "transparent"
								}
							},
							//Display the legend on top of radar chart
							legend: {
								display: false
							},
							layout: {
								padding: {
									left: 0,
									right: 0,
									top: 0,
									bottom: 0
								}
							}
						}
				});
			</script>';
			
			//////////////////////////////////////////////
		
			$this_container_start = "<div class=\"members_container\">";
			$this_row_member_start = "<div class=\"members_each\">";
			$this_member_avatar = "<div class=\"member_avatar\"><img class=\"img_member\" src=\"" . $dossier_avatar . $members_list[$x]['img_name'] . "\"></img></div>";
			$this_member_nickname = "<div class=\"member_nick\">" . $members_list[$x]['pseudo'] . "</div>";
			$this_member_title = "<div class=\"member_title\">" . $members_list[$x]['title'] . "</div>";
			$this_member_rank = "<div class=\"member_rank\">" . $members_list[$x]['rank'] . ' ' . $members_list[$x]['subrank'] . "</div>";
			$this_member_rank_img = "<div class=\"member_rank_img\"><img class=\"img_rl_member\" src=\"" . $dossier_rank . $members_list[$x]['rank_img'] . "\"></img></div>";
			$this_row_member_end = "</div>";
			$this_spider_chart_start = "<div class=\"members_spiderChart_each\"><div class=\"member_title\">SKILLS</div>";
			$this_spider_chart_end = "</div>";
			$this_container_end = "</div>";
			
			$this_created_member_div = $this_container_start . $this_row_member_start . $this_member_avatar . $this_member_nickname . $this_member_title . $this_member_rank . $this_member_rank_img . $this_row_member_end . $this_spider_chart_start . $this_spider_chart_core . $this_spider_chart_end . $this_spider_chart . $this_container_end;
			array_push($this_lan_members_list, $this_created_member_div);
		}
		
		//Taille du tableau de members généré
		$this_lan_members_list_lenght = sizeof($this_lan_members_list);
		
		//Je concatène toutes les valeurs récupérées pour générer tous mes divs de members les un après les autres
		for ($i = 0; $i < $members_list_lenght; $i++)
		{
			$members .= $this_lan_members_list[$i];
		}
		
		echo $members;
		
	}

	generate_members_list($bdd);
	
}

?>