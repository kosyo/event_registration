jQuery(document).ready(function(){

var allRadios = jQuery(this).find('#registration_form').find('input[type=radio]');
        var booRadio;
        var x = 0;
        for(x = 0; x < allRadios.length; x++){

            allRadios[x].onclick = function() {
                if(booRadio == this){
                    this.checked = false;
                    booRadio = null;
                }else{
                    booRadio = this;
                }
            };
        }
		jQuery(this).find('#registration_form').submit(function(){
			var form = jQuery(this);
			var fields = form.find('input[]');
			for(x = 0; x < fields.length; x++){
				console.log(fields[i].name);
}

				emailField = form.find('input[name=email]'),
				phoneField = form.find('input[name=phone]'),
				email = emailField.val();
				phone = phoneField.val();
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					dataType: 'json',
					data: {
						action: 'sendContact',
						email: email,
						phone: phone,
						first_name: form.find('input[name=first_name]').val(),
						club: form.find('input[name=club]').val()
					},
					success: function(data){
						if (data.success){
jQuery("#content").html("succesful");
							
						}
					},
					error: function(ts){
 						alert('error');		
					}
				});

			return false;
		});


});
