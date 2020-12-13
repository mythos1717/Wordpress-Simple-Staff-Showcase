  
  //show each staff detail on click and hide the ones that were shown previously.
  function getStaffDetails(the_id){ 
	 jQuery('.staff-member-detail').removeClass('active'); 	
  		jQuery("#"+the_id).addClass('active');   		
		var infotitle = jQuery('span.info-title').first().css('width');
			console.log(infotitle);
			jQuery('span.info-title').each(function(){
				jQuery(this).css('width',infotitle);
				
			});
    }
