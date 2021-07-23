<?php
ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
if (isset($_SESSION['pseudo']) OR isset($_SESSION['email'])){

	if (isset($_GET['spider_pseudo'])){

		//On inclue la bdd et on va chercher le chemin de l'avatar de l'utilisateur
		include_once('bdd_connect.php');

		//Requête pour récupérer l'uid
		$reqa = $bdd->prepare('SELECT uid, pseudo FROM members WHERE pseudo = :pseudo');
		$reqa->execute(array(
			'pseudo' => $_GET['spider_pseudo']));
			
		//Inscription des données récupérées dans le tableau fetch		
		$donnees = $reqa->fetch();
					
		//Récupération des résultats de la requête
		$fetched_uid = $donnees['uid'];
		$fetched_pseudo = $donnees['pseudo'];
					
		//Requête pour récupérer la liste des skills
		$reqx = $bdd->prepare("
				SELECT skill_value, name FROM member_skills INNER JOIN skills_name on member_skills.skill_id = skills_name.skill_id WHERE uid = :uid ORDER BY name;
				");
				$reqx->execute(array(
					'uid' => $fetched_uid));
					
		//Inscription des données récupérées dans le tableau fetch		
		$competences = $reqx->fetchAll(PDO::FETCH_ASSOC);

		//Fin de connexion BDD
		$reqa->closeCursor();
		$reqx->closeCursor();
		
		//Taille du tableau de fetch pour ce user
		$competences_list_lenght = sizeof($competences);
		
		//Initialisation des variables de stockage
		$skills_array;
		$values_array;
		
		//On loop à travers toutes les compétences et leurs notes respectives, qu'on stock dans la chaine qui va former le script du canvas
		for ($x = 0; $x < $competences_list_lenght; $x++){
			
			//Test de récupération des données issues du tableau de fetch
			if( $x == $competences_list_lenght - 1){
				$skills_array .= '"' . $competences[$x]['name'] . '"';
				$values_array .= $competences[$x]['skill_value'];
			}
			else {
				$skills_array .= '"' . $competences[$x]['name'] . '", ';
				$values_array .= $competences[$x]['skill_value'] . ', ';
			}
		}
	}
}
?>		
<div id="myModal" class="modal_canvas" style="display: block; width: 100%; height: 100%">

  <!-- Modal content -->
  <div class="modal-content_canvas">
	<div class="title_canvas">
		<?php echo($fetched_pseudo); ?>
		<div class="close_canvas">Fermer</div>
	</div>
	
	<canvas id="myRadarChart" style="max-width: 1920px; max-height: 1080px">
	
	</canvas>
	<script>
		var ctx = document.getElementById("myRadarChart").getContext("2d");
		var myRadarChart = new Chart(ctx, {
			"type":"radar",
			"data":{
				"labels":[<?php echo($skills_array); ?>],
				"datasets":[					
					{
						"data":[<?php echo($values_array); ?>],								
						"fill":true,
						"lineTension":0.2,
						"backgroundColor":"rgb(187,218,255,0.4)",
						"borderColor":"darkblue",
						"pointBackgroundColor":"darkblue",
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
							stepSize: 1,
							//Display scale of radar values 
							display: true,
							fontSize: 15
						},
						//Display scale of rader (only one scale)
						display: true,
						pointLabels: {
							fontSize: 15
						},
						gridLines: {
							color: "#23374E",
						},
						angleLines: {
							color: 'whitesmoke',
							fontSize: 15
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
					},
					responsive: true
			}
		});
	</script>
  </div>
</div>
<script src="js/canvas_pop.js?<?php echo filemtime('../js/pop_ups.js') ?>"></script>