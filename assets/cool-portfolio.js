( function ( $ ) {
	$( document ).ready( function () {
		$( "body" ).append( "<div class='coolportfolio-temp-out'><div id='coolportfolio-temp'></div> <a id='coolpclose'>X</a></div>" );
		
		$('#coolpclose').click(function(){
			$( ".coolportfolio-temp-out" ).hide();
			$( "#coolportfolio-temp" ).html( '' );
		});
		
		$('.cool-portfolio').click(function(){
			var href = $(this).attr("href");
			$( "#coolportfolio-temp" ).html( '<div class="loader">Loading...</div>' );
			$( ".coolportfolio-temp-out" ).show();
			$( "#coolportfolio-temp" ).html( '<img src="'+href+'" />' );
			return false;
		});
		
		$('.cool-portfolio-ajax').click(function(){
			var href = $(this).attr("href");
			$( "#coolportfolio-temp" ).html( '<div class="loader">Loading...</div>' );
			$.ajax({
			  url: href,
			  data: {
				fancybox1: 1
			  },
			  success: function( result ) {
				$( ".coolportfolio-temp-out" ).show();
				$( "#coolportfolio-temp" ).html( result );
			  }
			});
			return false;
		});
	});
} )( jQuery );