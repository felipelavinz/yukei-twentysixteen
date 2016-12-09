<?php
	$about_page = get_page_by_path('about');
	if ( $about_page ) :
?>
<div class="site-intro">
	<div class="entry-content">
		<?php echo apply_filters( 'the_content', $about_page->post_content ); ?>
	</div>
</div>
<?php endif; ?>