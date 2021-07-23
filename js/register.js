$(document).ready(function(){
	
	//Check for Mobile
	var isMobile = false; //initiate as false
	// device detection
	if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
		|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
		isMobile = true;
	}	

	////////////////////////////////////////////////
	////////////////CHOOSE DECISION/////////////////
	////////////////////////////////////////////////

	//Affiche la page de création de team
	$('#addteam').on("click", function(){

		toggleChooseDiv("addteam");

	});

	//Affiche la page de join de team
	$('#jointeam').on("click", function(){
		
		toggleChooseDiv("jointeam");

	});

	//Retourne à l'écran de choix entre add team ou join team
	$('button[name="back2select_fromTeamform"]').unbind().click(function(){

		toggleCompleteFormDiv();

	});

	//Retourne à l'écran de choix entre add team ou join team
	$('button[name="back2select_fromPlayerform"]').unbind().click(function(){

		togglePlayerFormDiv();

	});
	
	////////////////////////////////////////////////
	////////////////TOGGLE FUNCTIONS////////////////
	////////////////////////////////////////////////
	
	function toggleChooseDiv(operation){
		
		if(operation == "addteam"){

			let el = $('#choose');

			if(el.width() != 0){

				//On veut le réduire
				el.animate({
					opacity: 0			
				},125,function(){

					//Reduce current div when animation complete
					el.animate({
						width:'0px'
					}, 0, function(){
						el.css("display", "none");
						toggleCompleteFormDiv();
					});

				});
			}
			else{

				el.css("display", "flex");
				
				let thatWidth;
				
				if(isMobile == true){	
					thatWidth = '100%';
				}
				else{
					thatWidth = '100%';
				}			

				//On veut l'afficher
				el.animate({
					
					width: thatWidth
					
				},0,function(){

					//Reduce current div when animation complete
					el.animate({
						opacity: 1
					}, 125, function(){
						
					});
				});
			}
		}
		else if(operation == "jointeam"){

			let el = $('#choose');

			if(el.width() != 0){

				//On veut le réduire
				el.animate({
					opacity: 0	
				},125,function(){

					//Reduce current div when animation complete
					el.animate({
						width:'0px'
					}, 0, function(){
						el.css("display", "none");
						togglePlayerFormDiv();
					});

				});
			}
			else{

				el.css("display", "flex");
				
				let thatWidth;
				
				if(isMobile == true){	
					thatWidth = '100%';
				}
				else{
					thatWidth = '100%';
				}			

				//On veut l'afficher
				el.animate({
					
					width: thatWidth
					
				},0,function(){

					//Reduce current div when animation complete
					el.animate({
						opacity: 1
					}, 125, function(){
						
					});
				});
			}
		}		
	}
	
	function toggleCompleteFormDiv(){
		


		let el = $('#completeform');

		if(el.width() != 0){

			//On veut le réduire
			el.animate({
				opacity: 0
			},125,function(){

				//Reduce current div when animation complete
				el.animate({
					width: "0px"
				}, 0, function(){
					el.css("display", "none");
					toggleChooseDiv("addteam");
				});

			});
		}
		else{

			el.css("display", "flex");
			
			let thatWidth;
			
			if(isMobile == true){	
				thatWidth = '100%';
			}
			else{
				thatWidth = '100%';
			}

			//On veut l'afficher
			el.animate({
				
			width: thatWidth
			
			},0,function(){

				//Reduce current div when animation complete
				el.animate({
					opacity: 1
				}, 125, function(){

				});
			});
		}

	}

	function togglePlayerFormDiv(){
		

		let el = $('#complete_player_form');

		if(el.width() != 0){

			//On veut le réduire
			el.animate({
				opacity: 0
			},125,function(){

				//Reduce current div when animation complete
				el.animate({
					width: "0px"
				}, 0, function(){
					el.css("display", "none");
					toggleChooseDiv("jointeam");
				});

			});
		}
		else{

			el.css("display", "flex");
			
			let thatWidth;
			
			if(isMobile == true){	
				thatWidth = '100%';
			}
			else{
				thatWidth = '100%';
			}

			//On veut l'afficher
			el.animate({
				
			width: thatWidth
			
			},0,function(){

				//Reduce current div when animation complete
				el.animate({
					opacity: 1
				}, 125, function(){

				});
			});
		}

	}
	
//////////////////////////////////////////////////////////
/////////////////////INPUT VALIDATION/////////////////////
//////////////////////////////////////////////////////////
	
	function checkInputValidation(){
		
		//Pour chaque article contenant des inputs eventuellement
		$('.article_sub').each(function(){
			
			thisEl = $(this);
			
			//Seulement si le div est affiché, alors on compte les input et on fait le check
			if(thisEl.css("display") != "none"){

				var activeInputs = thisEl.find($('input[class="init"]')).length;
				
				let filledDivCounter = 0;
				
				for(i=0; i < activeInputs; i++){
					
					if(thisEl.find($('input[class="init"]')).eq(i).val() != ""){
						filledDivCounter++;
					}
				}
				
				if(filledDivCounter == activeInputs){
					
					if(thisEl.attr("id") == "completeform"){
						
						$('button[id="confirmteam"]').attr('disabled', false);
					}
					else if(thisEl.attr("id") == "complete_player_form" && $('select[id="select_team"]').val() != null && $('select[id="select_team"]').text() != "Aucune équipe en jeu"){
					
						$('button[id="confirmplayer"]').attr('disabled', false);
					}
				}
				else{
					
					if(thisEl.attr("id") == "completeform"){
						
						$('button[id="confirmteam"]').attr('disabled', true);
					}
					else if(thisEl.attr("id") == "complete_player_form"){
					
						$('button[id="confirmplayer"]').attr('disabled', true);
					}
				}

			}
		});
	}
	
	//A chaque frappe, on check également
	$('input[class="init"]').unbind().on('keyup', function () {
		checkInputValidation();
	});	

//////////////////////////////////////////////////////////
/////////////////SCROLL ON FOCUS INPUT ///////////////////
//////////////////////////////////////////////////////////
	
	//Hauteur du viewport avant resize du clavier
	let VH = $( window ).height();

	$(window).on('resize', function(){
		
		if($(window).height() < VH){
		
			//Gère le resize du au clavier Android qui se déroule et laisse moins de place
			$('body').height(VH);
			$('#section').css({
				
				'justify-content': 'space-evenly'
				
			});
			
		   // If the current active element is a text input, we can assume the soft keyboard is visible.
		   if($(document.activeElement).attr('type') == 'text') {
	 
				var inputTop = $(this).offset().top;
				console.log("scroling to "+ inputTop);
				// scroll to the textarea
				$htmlOrBody.scrollTop(inputTop - scrollTopPadding);
				// Logic for while keyboard is shown
			}
			else {
			  // Logic for while keyboard is hidden
			}
		}
	   else{
		   
			//Gère le resize du au clavier Android qui s'enroule et laisse plus de place
			$('body').height(VH);
			$('#section').css({
			
				'justify-content': 'center'
			
			}); 
		   
	   }
	});

});