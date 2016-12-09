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
		// add_action('pre_get_posts', [ $this, 'filter_front_page_query'] );
	}
	public function after_setup_theme(){
		add_editor_style( get_template_directory_uri() .'/css/editor-style.css' );
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