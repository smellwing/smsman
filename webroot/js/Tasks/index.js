//IIFE - Immediately Invoked Function Expression
(function(iniciar) {
    // The global jQuery object is passed as a parameter
  	iniciar(window.jQuery, window, document);

  }(function($, window, document) {
	  // The $ is now locally scoped 
      // Listen for the jQuery ready event on the document
	  
	$(function(){		
		  
		$('.cargando').each(function() {
		    
		    $index_id = this.id;
		    $task_id = this.id.substring(2);
		    getCarga($task_id)
		    .done(function($data) {
				console.log($data)
				$("#"+$index_id).html($data+"%");
			})
			.fail(function() {
					console.log( "error" );
			});
		    window.setInterval(function(){
		    	getCarga($task_id)
			    .done(function($data) {
					console.log($data)
					$("#"+$index_id).html($data+"%");
				})
				.fail(function() {
						console.log( "error" );
				});	  
		    	}, 5000);
		    
		    
		    
		});

	});
	
	var getCarga = function($task_id){			
		return $.post( "../checkCargando/", {task_id: $task_id})			
	}
 }));