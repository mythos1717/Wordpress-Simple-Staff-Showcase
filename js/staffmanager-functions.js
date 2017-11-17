  
  //show each staff detail on click and hide the ones that were shown previously.
  function getStaffDetails(the_id){ 
    // alert('Request is working');
	 jQuery('.staff-member-detail').removeClass('active'); 	
  		jQuery("#"+the_id).addClass('active');   		
    }