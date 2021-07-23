$(document).ready(function(){

	var validate_psw = "";
	var validate_email = "";
	
	if($('input[name="email"]') != null){
		$('input[name="email"]').on('keyup', function () {
			var typed_email = this.value;

			var email = new RegExp('^[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}$');

			if (email.test(typed_email)) {
				$('input[name="email"]').css({
				'color': 'green',
				'background': '#d1f5d1',
				});
				validate_email = true;
			
			} else {
			$('button.registerbtn').attr('disabled', 'true');
			$('input[name="email"]').css({
			'color': 'red',
			'background': '#feeae5',
			});
			validate_email = false;
			}
		});	
	}	
	
	if($('#psw-repeat, #psw') != null){
		$('#psw-repeat, #psw').on('keyup', function () {
		  if ($('#psw').val() == $('#psw-repeat').val()) {
			$('#psw-repeat').css({
			'color': 'green',
			'background': '#d1f5d1',
			});
			validate_psw = true;
			
		  } else {
			$('button.registerbtn').attr('disabled', 'true');
			$('#psw-repeat').css({
			'color': 'red',
			'background': '#feeae5',
		  });
		  validate_psw = false;
		  }
		});
	}
	

	if($('input[name="forget_email"]') != null){
		$('input[name="forget_email"]').on('keyup', function () {
			var typed_email = this.value;

			var email = new RegExp('^[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}$');
			
			
			if (email.test(typed_email)) {
				$('input[name="forget_email"]').css({
				'color': 'green',
				'background': '#d1f5d1',
				});
				validate_email = true;
				if(validate_email === true){
				$('button.registerbtn').removeAttr('disabled');
				}			
			} else {
			$('button.registerbtn').attr('disabled', 'true');
			$('input[name="forget_email"]').css({
			'color': 'red',
			'background': '#feeae5',
			});
			validate_email = false;
			}
		});	
	}
	
	function enable_validation(){
		console.log("validate_psw " +validate_psw+ " validate_email " + validate_email);
		
		if($('#psw-repeat, #psw').length != 0 ){
			if($('input[name="email"]').length != 0){				
				console.log("Register mode!");
				if(validate_email == true && validate_psw == true){
					$('button.registerbtn').removeAttr('disabled');
				}							
			}
			else{
				console.log("Change psw mode!");
				if(validate_psw == true){
					$('button.registerbtn').removeAttr('disabled');
				}
			}
			if($('input[name="forget_email"]').length != 0){
				if(validate_email == true){
					$('button.registerbtn').removeAttr('disabled');
				}
			}
		}
	}
	
	$('input[class^="init"]').on('keyup', function () {
		enable_validation();
	});
	
});	