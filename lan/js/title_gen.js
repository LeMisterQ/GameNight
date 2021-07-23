// JS gérant le générateur de titres

	// Si clic sur le boutton, on toggle entre montrer et afficher le contenu */	
	$('button[id^="but_title_"]').on('click', function(){
		$(this).next().toggleClass("show");
	});
	
	//Fonction de filtrage de ce qu'on écrit dans les input lors de la recherche
	$('input[id^="drop_input_title"]').keyup(function(){
		var input, filter, a, i;
		
		input = $(this);		
		filter = input.val().toUpperCase();		
		div = input.parent();
		a = div.find("a");
		
		for (i = 0; i < a.length; i++) {
		txtValue = a[i].textContent || a[i].innerText;
		if (txtValue.toUpperCase().indexOf(filter) > -1) {
		  a[i].style.display = "";
		} else {
		  a[i].style.display = "none";
		}
		}
	});	
	
	//Déclaration des tableaux
	var title_1 = ['Un','Le','L’'];
	var title_2 = ['Loser','Gars','Gamin','Partisan','Mineur','Prêtre','Hacker','Pirate','Black-block','Sauveur','Traître','Ami','Héros','Justicier','Pape','Père','Mère','Mec','Curé','Homme','Eventreur','Egorgeur','Unijambiste','Bourrin','Gros lard','Chauve','Boulet','Vengeur','Monsieur','Madame','Mademoiselle','Damoiseau','Emissaire','Empereur','Roi','Grand-duc','Archiduc','Comte','Margrave','Landgrave','Burgrave','Titres nobiliaires','Prince','Duc','Grave','Vicomte','Baron','Seigneur','Ecuyer','Joueur','Pro-Gamer','Maître','Soldat','Matelot','Caporal','Sergent','Adjudant','Major','Sous-lieutenant','Lieutenant','Capitaine','Commandant','Lieutenant-colonel','Colonel','Général','Attaquant','Archer','Bienfaiteur','Barbare','Barde','Défenseur','Druide','Ensorceleur','Guerrier','Magicien','Moine','Rôdeur','Roublard','Alchimiste','Chevalier','Conjurateur','Inquisiteur','Oracle','Sorcière','Paladin','Magus','Ninja','Pistolier','Samouraï','Justicier','Métamorphe','Chasseur de vampire','Arcaniste','Bretteur','Chaman','Chasseur','Enquêteur','Lutteur','Prêtre combattant','Sanguin','Scalde','Tueur'];
	var title_3 = ['raide','anorexique','relou','fumeur','drogué','alcoolique','récalcitrant','puant','malodorant','orthodoxe','juif','soupirant','vert','bleu','rose','blanc','noir','arabe','rebeu','jaune','russe','casseur','brûleur','mangeur','professionnel','pro','abimé','sans peur','bourré','barbu','sans poils','séduisant','suffocant','mystérieux','ténébreux','bonne poire','haineux','bandant','hétéro','homo','un peu gay','pas cher','fonctionnaire','catholique','croyant','syndicaliste','militant','nazi','moisi','zéro','mauvais','ultra nul','très bon','nul','froid','chaud','flippant','pédophile','fratricide','masqué','vénérable','honorable','bien bâti','bien monté','idiot','débile','excellent','cool','naze','respecté','pourri','fat','petit','grand','gros','maigre','abordable','accessible','accompli','accueillant','actif','admirable','adorable','adroit','affable','affectueux','affirmatif','agréable','aidant','aimable','aimant','ambitieux','amical','amusant','animé','apaisant','appliqué','ardent','artistique','assertif','assidu','astucieux','attachant','attentif','attentionné','attractif','audacieux','authentique','autonome','avenant','aventureux','beau','bienfaisant','bienséant','bienveillant','bon','brave','brillant','bûcheur','câlin','calme','capable','captivant','chaleureux','chanceux','charismatique','charitable','charmant','charmeur','chouette','civil','clément','cohérent','collaborateur','combatif','comique','communicatif','compatissant','compétent','compétitif','complaisant','complice','compréhensif','concentré','concerné','conciliant','confiant','consciencieux','conséquent','constant','content','convaincant','convenable','coopératif','courageux','courtois','créatif','critique','cultivé','curieux','débonnaire','débrouillard','décidé','décontracté','délicat','détendu','déterminé','dévoué','digne','digne de confiance','diligent','diplomate','direct','discipliné','discret','disponible','distingué','distrayant','divertissant','doué','doux','droit','drôle','dynamique','eblouissant','eclatant','econome','efficace','egayant','eloquent','emouvant','empathique','encourageant','endurant','energique','engagé','enjoué','enthousiaste','entreprenant','epanoui','galant','humble','humoristique','imaginatif','impliqué','indulgent','infatigable','influent','ingénieux','inoubliable','inspiré','intègre','intelligent','intéressé','intrépide','intuitif','inventif','jovial','joyeux','judicieux','juste','leader','libéré','libre','logique','loyal','lucide','magistral','maître de soi','malin','mature','méritant','méthodique','mignon','minutieux','modèle','modeste','moral','motivé','naturel','noble','novateur','nuancé','objectif','obligeant','observateur','opiniâtre','optimiste','ordonné','organisé','original','ouvert','ouvert d’esprit','pacificateur','pacifique','paisible','passionnant','passionné','patient','persévérant','perspicace','persuasif','pétillant','philosophe','plaisant','poli','polyvalent','ponctuel','pondéré','posé','positif','pragmatique','pratique','précis','présent','prévenant','prévoyant','productif','propre','protecteur','prudent','pugnace','pur','raffiné','raisonnable','rassurant','rationnel','réaliste','réceptif','réconfortant','reconnaissant','réfléchi','résistant','résolu','respectueux','responsable','rigoureux','romantique','rusé','sage','savant','séduisant','serein','sérieux','serviable','sincère','sociable','social','soigneux','solide','souriant','sportif','stable','stimulant','stratège','structuré','studieux','sûr de soi','sympathique','talentueux','tempéré','tenace','tendre','timide','tolérant','tranquille','travaillant','unique','vaillant','valeureux','vif','vigilant','vigoureux','vivace','volontaire','volubile','vrai','zen','abrupt','accro','accusateur','acerbe','agressif','aigri','amateur','amorphe','angoissé','anxieux','arbitraire','arriviste','arrogant','associable','asocial','assisté','autoritaire','avare','bagarreur','baratineur','bavard','blasé','blessant','borné','boudeur','brouillon','brute','bruyant','cachottier','calculateur','capricieux','caractériel','caricatural','carriériste','cassant','casse-cou','catastrophiste','caustique','censeur','coléreux','colérique','complexé','compliqué','confus','crédule','cruel','cynique','débordé','défaitiste','dépensier','désinvolte','désobéissant','désordonné','désorganisé','diabolique','distrait','docile','dominateur','dragueur','egocentrique','egoïste','emotif','enigmatique','entêté','envahissant','envieux','etourdi','excentrique','excessif','fainéant','familier','fantasque','fataliste','grossier','hautain','hésitant','humiliant','hypocrite','imbu de lui-même','immature','impatient','imprudent','impulsif','inaccessible','inattentif','incompétent','inconstant','inculte','indécis','indiscret','indomptable','influençable','insatisfait','insignifiant','insouciant','instable','intolérant','intransigeant','introverti','ironique','irréaliste','irrespectueux','irresponsable','jaloux','joueur','laxiste','lent','lunatique','macho','magnanime','mal à l’aise','mal élevé','maladroit','malhonnête','maniaque','maniéré','manipulateur','méchant','médiocre','médisant','méfiant','mégalomane','menteur','méprisant','mesquin','misogyne','moqueur','mou','muet','mystérieux','mythomane','naïf','narcissique','négatif','négligeant','nerveux','nonchalant','obstiné','obtus','odieux','opiniâtre','orgueilleux','paresseux','passif','pédant','persécuteur','pervers','pessimiste','peureux','plaintif','possessif','présomptueux','prétentieux','procrastinateur','profiteur','provocateur','puéril','raciste','radin','râleur','rancunier','rebelle','renfermé','réservé','résigné','rétrograde','revanchard','revêche','révolté','rigide','ringard','routinier','sans gêne','sarcastique','secret','sensible','solitaire','sombre','soupçonneux','sournois','stressé','strict','stupide','suffisant','superficiel','susceptible','tatillon','tempétueux','têtu','triste','vaniteux','versatile','vulgaire'];
	var title_4 = ['à','un','une','le','l’','la','les','au','d\'','du','de la','de','à l\'','de l’','en','des'];
	var title_5 = ['Problèmes','Détresse','Béton armé','Titane','Diamant','Adamantium','Mousse','Carton','Nourissons','Enfants','Tête','Caboche','Flamme','Abime','Improvsite','Mauvais moment','Bon moment','Princesse','Dragon','Bête','Nature','Lune','tous les Jeux','Rocket League','Bande','Famille','Lille','Printemps','Eté','Automne','Hiver','Néant','Tonerre','Foudre','Eclair','Braise','Magma','Plasma','Cosmos','Feu','Glace','Eau','Vent','Ténèbres','Lumière','Terres','Monde','Planète','Univers','Galaxie','Voie Lactée','Royaume','Entreprise','Lan','Village','Eglise','Ecole','Pays','Temps anciens','Temps modernes','PLS','Abysse','Âge glaciaire','Alpin','Altitude','Amont','Antarctique','Antipodes','Arc volcanique','Archipel','Arctique','Atmosphère','Badlands','Baie','Banc de sable','Banquise','Bassin','Bayou','Biosphère','Bois','Brousse','Canyon','Cap','Cendre','Cercle polaire','Fées','Chute d\'eau','Cirque','Colline','Continent','Corail','Cratère','Cryosphère','Delta','Dépression','Désert','Dune','Equateur','Equinoxe','Etang','Falaise','Fleuve','Flux','Forêt','Fosse','Glacier','Globe','Golfe','Gorge','Gouffre','Grotte','Gué','Iceberg','Île','Jungle','Lac','Lac de lave','Lagon','Lave','Mamelon','Mer','Montagne','Oasis','Océan','Oued','Pampa','Plage','Plaine','Point culminant','Pôle','Prairie','Presqu\'île','Ravin','Raz-de-marée','Rivière','Savane','Séisme','Sommet','Source','Stalactite','Stalagmite','Steppe','Terre','Tremblement de terre','Tsunami','Vague','Vallée','Volcan','Zone humide'];
	
	//Classement par ordre alhpabetique
	title_1 = title_1.sort();
	title_2 = title_2.sort();
	title_3 = title_3.sort();
	title_4 = title_4.sort();
	title_5 = title_5.sort();
	
	//On calcule les longueurs d'array
	var title_1_L = title_1.length;
	var title_2_L = title_2.length;
	var title_3_L = title_3.length;
	var title_4_L = title_4.length;
	var title_5_L = title_5.length;
	
	var div2add;
	
	//On loop sur l'array pour compléter le dropdown 1
	for(i=0;i<title_1_L;i++){
		div2add = '<a id="title_1_'+i+'" href="#">'+title_1[i]+'</a>';
		$('#drop_title_1').append(div2add);
	}
	
		//On loop sur l'array pour compléter le dropdown 2
	for(i=0;i<title_2_L;i++){
		div2add = '<a id="title_2_'+i+'" href="#">'+title_2[i]+'</a>';
		$('#drop_title_2').append(div2add);
	}
	
		//On loop sur l'array pour compléter le dropdown 3
	for(i=0;i<title_3_L;i++){
		div2add = '<a id="title_3_'+i+'" href="#">'+title_3[i]+'</a>';
		$('#drop_title_3').append(div2add);
	}
	
		//On loop sur l'array pour compléter le dropdown 4
	for(i=0;i<title_4_L;i++){
		div2add = '<a id="title_4_'+i+'" href="#">'+title_4[i]+'</a>';
		$('#drop_title_4').append(div2add);
	}
	
		//On loop sur l'array pour compléter le dropdown 5
	for(i=0;i<title_5_L;i++){
		div2add = '<a id="title_5_'+i+'" href="#">'+title_5[i]+'</a>';
		$('#drop_title_5').append(div2add);
	}
	
	//Si clic sur un choix du menu, on le valide dans son button
	//Si on a tous les morceaux, on peux débloquer le bouton de validation
	$('a[id^="title_"]').on('click', function(e){
		e.preventDefault();
		var get_a_text = $(this).text();
		var getButton = $(this).parent().parent().find("button");
		getButton.text(get_a_text);
		getButton.next().toggleClass("show");
		
		var pieces = $('button[id^="but_title_1"], button[id^="but_title_2"], button[id^="but_title_3"], button[id^="but_title_4"], button[id^="but_title_5"]').text();
		if(pieces.match(/Partie/g) == null && pieces.trim() != ""){
		
		var full_title = $('button[id^="but_title_1"]').text() + ' ' + $('button[id^="but_title_2"]').text() + ' ' + $('button[id^="but_title_3"]').text() + ' ' + $('button[id^="but_title_4"]').text() + ' ' +$('button[id^="but_title_5"]').text();
		$('#title_full').text(full_title);
		$('button#validate_title').removeAttr("disabled");
		
		}
	});
