jQuery(document).ready(function($){
	var postsArchive = $('#posts-archive');
	var canonical    = $('link[rel="canonical"]').attr('href');
	var postTemplate = _.template( $('#archive-post__template').html() );
	var postYears    = $('#posts-archive-years');
	$('.archive-years__select').on('change', function(event){
		var year = $(this).val();
		getPostArchive( year ).then(function(){
			$('#archive-year-'+ year ).addClass('archive-year--active');
		});
	});
	$('#secondary').on('click', 'a', function(event){
		event.preventDefault();
		var year = parseInt( $( event.currentTarget ).data('year'), 10 );
		$('.archive-years__select').val( year ).trigger('change');
	});
	var getPostArchive = function( year ){
		postYears.find('.archive-year--active').removeClass('archive-year--active');
		// dude, chill
		if ( postsArchive.hasClass('posts-archive--loading') ) {
			return;
		}
		postsArchive.addClass('posts-archive--loading');
		return $.ajax({
			type: 'GET',
			url: '/wp-json/yukei/v1/archives/'+ year,
			dataType: 'json'
		}).then(function(data){
			var newPosts = '';
			_.each(data, function(post){
				newPosts += postTemplate( post );
			});
			postsArchive.html( newPosts ).removeClass('posts-archive--loading');
			history.pushState( {}, '', canonical + year +'/' );
		});
	};
});