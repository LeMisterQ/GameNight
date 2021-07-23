
//////////////////
//Constantes
//////////////////
// Date de la LAN
var countDownDate = new Date("October 25, 2019 18:00:00").getTime();
var endDate = new Date("October 27, 2019 14:00:00").getTime();
var myggAPI_key = "AIzaSyCBBQtxvCzG7oIW7PzqPipyuvo9Tlxq-q4";
var mapsrc ='https://www.google.com/maps/embed/v1/place?key='+myggAPI_key+'&q=33+Avenur+Louis+Braille,+59700+Marcq';

//Plugin Regex//
jQuery.expr[':'].regex = function(elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ? 
                        matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels,'')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
    return regex.test(jQuery(elem)[attr.method](attr.property));
}


//////////////////
//Get background Image sizeToContent
//////////////////

//On cache le loadingGif a l'ouverture de la page
$('.loadingGif').hide();
$('.steam-img').hide();
$('.all_container').hide();

//////////////////
//Fonctions
//////////////////
function close_content(){

	$('.modal_lan').css({"opacity": "1"}).animate({"opacity": "0"}, 100);
	$('.modal_lan').css({"touch-action":"",'visibility':'hidden'});	
	$('.boite_principale').css({
	'opacity':'1'
	});
	
}

function open_content(){

	$('.modal_lan').css({"opacity": "0"}).animate({"opacity": "1",'visibility':'visible'}, 300);
	$('.modal_lan').css({"touch-action":"none",'visibility':'visible'});
}

function text_popin(){
$('.activities_desc').css({
	'font-size':'initial'
	});
	$('.activities_desc').animate({
        'font-size': '+=2vw'
    });
}

function text_popout(){
	$('.activities_desc').animate({
        'font-size': '-=2vw',
    });

}

//////////////////
//Initial Loading
////////////////

//Loading du GGMap app
$("#LANggmap").attr("src", mapsrc);

//Affichage des sides activities
var alcohol = 'Des rafraîchissements type Vodka RedBull';
var book ='Histoire de Siggy';
var burger = 'Hamburgers sauce maroilles (Homemade)';
var gamepad ='Raspberry Retro-Gaming';
var dust_curling= 'Curling Dust (possible Burning-Dust, sur demande)';
 
$( '.alcohol' ).on("click", function() {
	//text_popout();
	$('.activities_desc').text(alcohol);
	text_popin();
});

$( '.book' ).on("click", function() {
	//text_popout();
	$('.activities_desc').text(book);
	text_popin();
});

$( '.burger' ).on("click", function() {
	//text_popout();
	$('.activities_desc').text(burger);
	text_popin();
});

$( '.burger' ).on("mouseover", function() {
	setTimeout(function(){
	$('.burger').attr("src", "img/burger_2_3.png");},300);
	setTimeout(function(){
	$('.burger').attr("src", "img/burger_1_3.png");},600);
	setTimeout(function(){
	$('.burger').attr("src", "img/burger_0_3.png");},900);
	setTimeout(function(){
	$('.burger').attr("src", "img/burger.png");},1200);
});

$( '.gamepad' ).on("click", function() {
	//text_popout();
	$('.activities_desc').text(gamepad);
	text_popin();
});

$( '.dust_curling' ).on("click", function() {
	//text_popout();
	$('.activities_desc').text(dust_curling);
	text_popin();
});

//Open de la modale pour chaque game
//ALIEN SWARM
$("#630").on("click", function(){

	window.open("https://www.youtube.com/embed/603izhzohos?rel=0&autoplay=1", "_blank");

});

//ARTEMIS
$("#apex_legends").on("click", function(){

	window.open("https://www.youtube.com/embed/UMJb_mkqynU?rel=0&autoplay=1", "_blank");

});

//BF1942
$("#battlefield1942").on("click", function(){

	window.open("https://www.youtube.com/embed/1-5jz3VP25s?rel=0&autoplay=1", "_blank");

});

//Battlefront II - Star Wars
$("#sw_bf2").on("click", function(){

	window.open("https://www.youtube.com/watch?v=_q51LZ2HpbE?rel=0&autoplay=1", "_blank");

});

//Brawlhalla
$("#291550").on("click", function(){

	window.open("https://www.youtube.com/watch?v=baixpNzE9es?rel=0&autoplay=1", "_blank");

});

//Burnout Paradise
$("#burnout_paradise").on("click", function(){

	window.open("https://www.youtube.com/watch?v=f4JqCmt9hzE?rel=0&autoplay=1", "_blank");

});

//CHIVALRY
$("#219640").on("click", function(){

	window.open("https://www.youtube.com/embed/li0uqpbdZgE?rel=0&autoplay=1", "_blank");

});

//CROSSOUT
$("#386180").on("click", function(){

	window.open("https://www.youtube.com/embed/wMS_mdMg6xo?rel=0&autoplay=1", "_blank");

});

//EPIC LOON
$("#762190").on("click", function(){

	window.open("https://www.youtube.com/embed/RQfVrIgpVfU?rel=0&autoplay=1", "_blank");

});

//BLAZERUSH
$("#302710").on("click", function(){

	window.open("https://www.youtube.com/embed/oC_RQdAQyDs?rel=0&autoplay=1", "_blank");

});

//For HONOR
$("#304390").on("click", function(){

	window.open("https://www.youtube.com/embed/BchIGo-S5fs?rel=0&autoplay=1", "_blank");

});

//Flatout
$("#2990").on("click", function(){

	window.open("https://www.youtube.com/embed/zGpVuknnN78?rel=0&autoplay=1", "_blank");

});

//GE Source
$("#goldeneyesource").on("click", function(){

	window.open("https://www.youtube.com/embed/-E4XtdEnWx4?rel=0&autoplay=1", "_blank");

});

//HF2
$("#220").on("click", function(){

	window.open("https://www.youtube.com/embed/UKA7JkV51Jw?rel=0&autoplay=1", "_blank");

});

//HFDeathMatch
$("#70").on("click", function(){

	window.open("https://www.youtube.com/embed/XHuKV9iMYeg?rel=0&autoplay=1", "_blank");

});

//Hand Simulator
$("#657200").on("click", function(){

	window.open("https://www.youtube.com/embed/lkVdNvQkAMQ?rel=0&autoplay=1", "_blank");

});

//Left 4 Dead 2
$("#550").on("click", function(){

	window.open("https://www.youtube.com/embed/Iqid90JR6BY?rel=0&autoplay=1", "_blank");

});

//McDoBigtasty
$("#mcdobigtasty").on("click", function(){

	window.open("https://www.mcdonalds.fr/produits/burgers/big-tasty", "_blank");

});

//OverCooked
$("#448510").on("click", function(){

	window.open("https://www.youtube.com/embed/0ZK7veYPEJQ?rel=0&autoplay=1", "_blank");

});

//PUBG
$("#578080").on("click", function(){

	window.open("https://www.youtube.com/embed/XCaWIkZKC6Q?rel=0&autoplay=1", "_blank");

});

//ROCKET LEAGUE
$("#252950").on("click", function(){

	window.open("https://www.youtube.com/embed/weV-WToHdhY?rel=0&autoplay=1", "_blank");

});

//REVOLT
$("#revolt").on("click", function(){

	window.open("https://www.revoltrace.net/downloads.php", "_blank");

});

//UT 3
$("#13210").on("click", function(){

	window.open("https://www.youtube.com/embed/Mr-zUO_l3eA?rel=0&autoplay=1", "_blank");

});

//WA
$("#217200").on("click", function(){

	window.open("https://www.youtube.com/embed/Xl2Oox2a58k?rel=0&autoplay=1", "_blank");

});

//////////////////
//VR
//////////////////

//Elite Dangerous
$("#359320").on("click", function(){

	window.open("https://www.youtube.com/embed/UTJ59Qcs_L8?rel=0&autoplay=1", "_blank");

});

//Eve Valkyrie
$("#688480").on("click", function(){

	window.open("https://www.youtube.com/embed/4BmlrbtAt7k?rel=0&autoplay=1", "_blank");

});

//Beat Saber
$("#620980").on("click", function(){

	window.open("https://www.youtube.com/embed/gV1sw4lfwFw?rel=0&autoplay=1", "_blank");

});

//Robot Recall
$("#robot_recall").on("click", function(){

	window.open("https://www.youtube.com/embed/MIK4D0kVlIs?rel=0&autoplay=1", "_blank");

});

//Close de la modale
$(".close").on("click", function(){
	close_content();
});

//////////////////
//Recup des infos via la Steam API lors du hover
//////////////////
//////////////////
/*Liste des SteamsID
var alienswarm_id=630;
var artemis_id=247350;
var battlefield1942_id="";
var blur_id="";
var chivalry_id=219640;
var crossout_id=386180;
var epicloon_id=762190;
var factorio_id=427520;
var forhonor_id=304390;
var goldeneyesource_id="";
var halflife2_id=220;
var halflifedeathmatch_id=70;
var handsimulator_id=657200;
var left4dead2_id=550;
var overcooked_id=448510;
var pubg_id=578080;
var rl_id=252950;
var shellshocklive_id=326460;
var UT3_id=13210;
var wormsarmageddon_id=217200;
*/
//////////////////

var gameUrl_base = "https://store.steampowered.com/app/";

$( document ).ready(function() {
	$(".gamesImg").on("mouseover",function(){		
		
		var this_object = $(this);
		var getID = $(this).attr("id");
		var re = new RegExp("(^[0-9]{2,})");
		
		if(re.test(getID) === true){
			if(this_object.find(".steam-img").attr("src") === undefined){
				//Icone de chargement = ON
				this_object.find('.loadingGif').show();
				var requestURL = "scripts/steam_infos.php?steamid="+getID;
				$.ajax({
					url: requestURL,
					dataType: 'json',
					type: "GET",
					error: function () {
						alert('error');
					},
					success: function (data) {
						this_object.find(".steam-img").attr("src", "img/steam.png");
					
						var thistring = JSON.stringify(data);					
						var obj = JSON.parse(thistring);
						
						//Demarrage des Check des infos disponibles selon le jeu
						var is_released = obj[getID].data.release_date.coming_soon;
						var release_date = obj[getID].data.release_date.date;
						if(is_released !== true){
							var is_free = obj[getID].data.is_free;
							if(is_free !== true){
								var this_curr = obj[getID].data.price_overview.currency;
								var this_initial_price = String(Number(obj[getID].data.price_overview.initial)/100)+this_curr;
								var this_discounted_price = String(Number(obj[getID].data.price_overview.final)/100)+this_curr;
								var this_discount = '-'+obj[getID].data.price_overview.discount_percent+'%';
								//console.log(this_initial_price + ', ' + this_discounted_price + ', ' + this_discount + '%');
								
								if(this_discount === '-0%'){
								this_object.find(".all_container").css({'background-color': 'unset','border-radius': 'unset','border': 'unset'});
								this_object.find(".discount").text(this_initial_price);
								this_object.find(".btn_green").attr("href", gameUrl_base+getID);
								}
								else{
								//Inscription dynamique des infos								
								this_object.find(".initial_p").html('<strike>'+this_initial_price+'</strike>');
								this_object.find(".discounted_p").text(this_discounted_price);
								this_object.find(".discount").text(this_discount);	
								this_object.find(".btn_green").attr("href", gameUrl_base+getID);
								}								
							}
							else{
							this_object.find(".all_container").css({'background-color': 'unset','border': 'unset'});
							this_object.find(".discount").text("GRATUIT");
							this_object.find(".btn_green").attr("href", gameUrl_base+getID);
							}
						//end of secondary ifs
						}
						else{
						this_object.find(".all_container").css({'background-color': 'unset','border': 'unset'});
						this_object.find(".discount").text(release_date);
						this_object.find(".btn_green").attr("href", gameUrl_base+getID);
						}
					//end of success
					},
					complete: function(data){
						this_object.find('.loadingGif').hide();
						this_object.find('.steam-img').show();
						this_object.find('.all_container').show();
					}					
				//end ajax call
				});	
			//end of if checking 1st request or not
			}
		//end of main if
		}
		else{
		this_object.children().remove();
		}
	});
	
	//Desactive le click pour afficher la video si on click sur le AddCart uniquement
	$(':regex(id,^[0-9]) a').on("click",function(e){
		console.log($(this).parents(':regex(id,^[0-9])').attr("id") + " Not Clicked!");
		console.log($(this).attr("class") + " Clicked!");
		e.stopPropagation();
	});
	
	$(".gamesImg").on("taphold",function(e){
	e.preventDefault();
	});
});
	
//////////////////
//Timer
//////////////////


function lan_ongoing(){	
	$('.modal_lan').css({
	'visibility':'visible',
	'background-image':'url("./img/denise.jpg")',
	'background-repeat': 'no-repeat',
	'background-position': 'center',
	'background-size': 'contain',
	});	
	$('.boite_principale').css({
	'opacity':'0.2'
	});
	open_content();
}

function lan_over(){
	$('.modal_lan').css({
	'visibility':'visible',
	'background-image':'url("./img/gameover.png")',
	'background-repeat': 'no-repeat',
	'background-position': 'center',
	'background-size': 'contain',
	});
	$('.modal_lan2').css({
	'background-image':'url("./img/link2.gif")',
	'background-repeat': 'no-repeat',
	'background-position': 'center bottom',
	'background-size': 'auto',
	'transform':'rotateY(180deg)',
	});	
	$('.modal_lan3').css({
	'background-image':'url("./img/cloud.gif")',
	'background-repeat': 'no-repeat',
	'background-position': 'center bottom',
	'background-size': 'auto',	
	});		
	$('.boite_principale').css({
	'opacity':'0.2'
	});
	open_content();
}

// Update count down every 1 s
var x = setInterval(function() {

  // todays date / time
  var now = new Date().getTime();

  // Find the distance between now an the count down date
  var distance = countDownDate - now;
  //console.log("now="+now + "endDate="+endDate);
  var distance_end = endDate - now;
  //console.log("distance_end="+distance_end);
  //console.log("distance="+distance);
  
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  document.getElementById("demo").innerHTML = days + "j " + hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "C'est MAINTENANT!";
	lan_ongoing();
  }
  if (distance_end < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "C'est FINI!";
	lan_over();
  }
  
}, 1000);