//IIFE - Immediately Invoked Function Expression
(function(iniciar) {
    // The global jQuery object is passed as a parameter
  	iniciar(window.jQuery, window, document);

  }(function($, window, document) {
	  // The $ is now locally scoped 
      // Listen for the jQuery ready event on the document
	  
	$(function(){		
		  
		 $('#datetime-start').change(function(){
			 var minutes = '';
			 var d = new Date();
			 var m = parseInt(d.getMinutes());
			 var val = parseInt($('#datetime-start').val());
			 
			 d.setMinutes(m+val);			 
			 if (d.getMinutes()<10) minutes = '0'+ d.getMinutes() + '';
			 else minutes =  d.getMinutes() + '';
			 var start_time = d.getHours()+':'+ minutes
			 $('#start-time').html('Esta tarea iniciará a las '+start_time)
			 
		 })		 

		 $('#datetime-end').change(function(){
			 var minutes = '';
			 var d = new Date();
			 var m = parseInt(d.getMinutes());
			 var valstart = parseInt($('#datetime-start').val());
			 var valend = parseInt($('#datetime-end').val());
			 
			 d.setMinutes(m+valstart+valend);			 
			 if (d.getMinutes()<10) minutes = '0'+ d.getMinutes() + '';
			 else minutes =  d.getMinutes() + '';
			 var end_time = d.getHours()+':'+minutes;
			 $('#end-time').html('Esta tarea acabará a las '+end_time)		 
			 
			 
			 var carga_maxima_hora = parseInt($('#carga-maxima-hora').val());
			 var carga_value = carga_maxima_hora*(valend/60) 
			 $('#carga-value').html('En este horario se enviarán '+carga_value+' mensajes.')
			 
		 })		 

	});
	
	
 }));