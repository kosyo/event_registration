jQuery(document).ready(function(){

jQuery("#payed").click(function(e)
{                       
                e.preventDefault();
console.log(jQuery('#payment_form').find("input[name=payment_id]").val());
                var ajaxReq = {
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data: {     
                        action: 'pay',
                        payment_id: jQuery('#payment_form').find("input[name=payment_id]").val(),
                    },
                    success: function(data){
                        if (data.success){
                            jQuery("#error_box").html(data.msg);
                        } else
                        {
                
                            jQuery("#error_box").html(data.msg);
                            jQuery('html,body').animate({
                                scrollTop: jQuery("#error_box").offset().top
                            }, 'slow');
                        }
                    },
                    error: function(ts){
                        alert('error');
                    }
                };
                
                jQuery.ajax(ajaxReq);
                    
                
});                 

jQuery("#without_fee").click(function(e)
{
                e.preventDefault();
console.log(jQuery('#payment_form').find("input[name=payment_id]").val());
                var ajaxReq = {
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data: {
                        action: 'pay',
                        without_fee: 1,
                        payment_id: jQuery('#payment_form').find("input[name=payment_id]").val(),
                    },
                    success: function(data){
                        if (data.success){
                            jQuery("#error_box").html(data.msg);
                        } else
                        {

                            jQuery("#error_box").html(data.msg);
                            jQuery('html,body').animate({
                                scrollTop: jQuery("#error_box").offset().top
                            }, 'slow');
                        }
                    },
                    error: function(ts){
                        alert('error');
                    }
                };

                jQuery.ajax(ajaxReq);


});

jQuery("#delete_expired_payments").click(function(e)
{
                e.preventDefault();
                confirm('Are you sure you want to archive expired unpay payments?');
                var ajaxReq = {
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data: {
                        action: 'delete_expired_payments',
                        organization_id: jQuery(this).attr('data-organization_id')
                    },
                    success: function(data){
                        if (data.success){
                            jQuery("#error_box").html(data.msg);
                        } else
                        {

                            jQuery("#error_box").html(data.msg);
                            jQuery('html,body').animate({
                                scrollTop: jQuery("#error_box").offset().top
                            }, 'slow');
                        }
                    },
                    error: function(ts){
                        alert('error');
                    }
                };

                jQuery.ajax(ajaxReq);


});


jQuery(".unjoin_event").click(function()
{
                var ajaxReq = {
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data: {
                        action: 'unjoin_event',
                        id: jQuery(this).attr("data-id"),
                    },
                    success: function(data){
                        if (data.success){
                            location.reload();
                        } else
                        {

                            jQuery("#error_box").html(data.msg);
                            jQuery('html,body').animate({
                                scrollTop: jQuery("#error_box").offset().top
                            }, 'slow');
                        }
                    },
                    error: function(ts){
                        alert('error');
                    }
                };
                
                jQuery.ajax(ajaxReq);


});

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
                            jQuery('html,body').animate({
                                scrollTop: jQuery("#error_box").offset().top
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
