<?php

class Yukei_2016_Theme{
	public function init(){
		$this->register_hooks();
	}
	public function register_hooks(){
		add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts'], 5 );
		add_action('widgets_init', [ $this, 'register_footer_sidebar'] );
		add_action('twentysixteen_credits', [ $this, 'display_footer_sidebar'], 99 );
		add_action('loop_start', [ $this, 'loop_start'], 10, 1);
		add_action('after_setup_theme', [ $this, 'after_setup_theme'], 999999 );
		add_action('pre_get_posts', [ $this, 'filter_archive_page_query'] );
		add_filter('template_include', [ $this, 'filter_archive_page_template'], 999 );
		add_filter('body_class', [ $this, 'filter_archive_page_body_class'], 999, 2 );
		add_action('rest_api_init', [ $this, 'register_custom_api_endpoints']);
		// add_action('pre_get_posts', [ $this, 'filter_front_page_query'] );
	}
	public function after_setup_theme(){
		add_editor_style( get_template_directory_uri() .'/css/editor-style.css' );
	}
	public function register_custom_api_endpoints(){
		register_rest_route('yukei/v1/', '/archives/(?P<year>\d+)', [
			'methods' => 'GET',
			'callback' => [ $this, 'get_yearly_archives' ],
			'args' => [
				'year' => [
					'validate_callback' => function( $input ){ return is_numeric( $input ); },
					'sanitize_callback' => 'absint',
					'type'              => 'integer'
				]
			]
		]);
	}
	public function get_yearly_archives( $req ){
		$year = $req->get_param('year');
		$posts = new WP_Query([
			'date_query' => [
				'year' => $year
			],
			'posts_per_page' => 999,
			'post_type'      => 'post',
			'post_status'    => 'publish'
		]);
		$response = [];
		if ( ! $posts->have_posts() ) {
			return $response;
		}
		foreach ( $posts->posts as $post ) {
			$response[] = [
				'title' => $post->post_title,
				'date'  => get_the_time( 'F jS', $post ),
				'url'   => get_permalink( $post )
			];
		}
		return $response;
	}
	public function filter_archive_page_body_class( $classes, $custom = '' ){
		$needle = array_search('no-sidebar', $classes);
		if ( $needle ) {
			unset( $classes[$needle] );
		}
		return $classes;
	}
	public function filter_archive_page_template( $template ){
		global $wp;
		if ( stripos( $wp->request, 'archive' ) !== 0 )
			return $template;
		$archive_template =  locate_template('page-archives.php', false, false);
		return $archive_template;
	}
	public function filter_archive_page_query( $q ){
		if ( ! $q->is_main_query() || ! $q->is_page('archive') ) {
			return $q;
		}
		$q->set('post_type', 'post');
		$q->set('posts_per_page', -1);
		$q->set('post_status', 'publish');
		$q->set('date_query', [
			'year' => $q->get('page') ?: date_i18n('Y')
		]);
		$q->set('p', null);
		$q->set('pagename', null);
	}
	public function loop_start( $q ){
		if ( ! $q->is_main_query() || ! $q->is_front_page() )
			return $q;
		get_template_part('template-parts/biography', 'front-page');
	}
	public function display_footer_sidebar(){
		if ( is_front_page() ) {
			dynamic_sidebar('sidebar-4');
		}
	}
	public function register_footer_sidebar(){
		register_sidebar( array(
			'name'          => __( 'Footer', 'twentysixteen' ),
			'id'            => 'sidebar-4',
			'description'   => __( 'Appears at the footer of the front page.', 'twentysixteen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

	public function enqueue_scripts(){
		wp_enqueue_style( 'twentysixteen-style', get_template_directory_uri() .'/style.css'  );
		wp_enqueue_style( 'twentysixteen-custom', get_stylesheet_uri(), ['twentysixteen-style'] );
		if ( is_page('archive') ) {
			wp_enqueue_script('yukei.archives', get_stylesheet_directory_uri() .'/js/yukei.archives.js', ['jquery', 'underscore']);
		}
	}
	public function filter_front_page_query( $q ){
		if ( ! $q->is_front_page() ) {
			return $q;
		}
		$q->set('tax_query', [
			[
				'taxonomy' => 'post_tag',
				'terms'    => ['ephemera', 'efimera', 'ephemera-en'],
				'field'    => 'slug',
				'operator' => 'NOT IN'
			]
		]);
	}
}

( new Yukei_2016_Theme )->init();

if ( ! function_exists('twentysixteen_fonts_url') ) {
	function twentysixteen_fonts_url(){
		return '//fonts.googleapis.com/css?family=Karla:400,400i,700|Libre+Franklin:400,800';
	}
}

if ( ! function_exists('twentysixteen_the_custom_logo') ) {
	function twentysixteen_the_custom_logo(){
		echo '<a href="'. site_url('/') .'" class="custom-logo-link" rel="home" itemprop="url">';
		echo '<img src="'. get_stylesheet_directory_uri() .'/img/yukei.svg" alt="yukei.net" itemprop="logo" width="130" height="60">';
		echo '</a>';
	}
}

function yukei_16_archive_years(){
	global $wpdb;
	$years_with_posts = $wpdb->get_results("
		SELECT YEAR(post_date) as year,
		COUNT( ID ) as q
		FROM $wpdb->posts
		WHERE post_type = 'post'
		AND post_status = 'publish'
		GROUP BY YEAR( post_date )
		ORDER BY post_date DESC
	");
	return $years_with_posts;
}

add_action('wp_footer', function(){
echo<<<EOL
<script type="text/javascript">
var _mfq = _mfq || [];
  (function() {
    var mf = document.createElement("script");
    mf.type = "text/javascript"; mf.async = true;
    mf.src = "//cdn.mouseflow.com/projects/2b8d85fa-7f42-498e-ac7e-9165577743cc.js";
    document.getElementsByTagName("head")[0].appendChild(mf);
  })();
</script>
EOL;
});
