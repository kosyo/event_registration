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
jQuery.validator.setDefaults({
                debug: true,
                success: "valid"
            });
        jQuery('#registration_form').validate();
		jQuery(this).find('#registration_form').submit(function(){

            var form = jQuery(this);
        var isvalid = form.valid();
        if(!isvalid)
        {
            e.preventDefault();
        }
                var ajaxReq = {
					type: 'POST',
					url: ajaxurl,
					dataType: 'json',
					data: {
						action: 'sendContact',
					},
					success: function(data){
						if (data.success){
                            jQuery("#content").html(data.msg);
						} else
                        {

                            jQuery("#error_box").html(data.msg);
                            $('html,body').animate({
                                scrollTop: $("#error_box").offset().top
                            }, 'slow');
                        }
					},
					error: function(ts){
 						alert('error');		
					}
				};

                var allInputs = form.find( ":input" );
                for(x = 0; x < allInputs.length; x++){
                    if(allInputs[x].type != 'submit')
                    {
                        console.log(allInputs[x].type + ' ' + allInputs[x].checked);
                        if((allInputs[x].type != 'radio' && allInputs[x].type != 'checkbox') || allInputs[x].checked)
                        {
                            ajaxReq['data'][allInputs[x].name] = allInputs[x].value;
                        }
                    }
                }
	
				jQuery.ajax(ajaxReq);

			return false;
		});


});
