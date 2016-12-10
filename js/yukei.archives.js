jQuery(document).ready(function($){
	$('#secondary').on('click', 'a', function(event){
		event.preventDefault();
		var year = parseInt( $( event.currentTarget ).data('year'), 10 );
		var request = $.ajax({
			type: 'GET',
			url: '/wp-json/yukei/v1/archives/'+ year,
			dataType: 'json'
		});
		request.then(function(data){
			console.log( data );
		});
	});
});