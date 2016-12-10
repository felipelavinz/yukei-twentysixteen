<?php
/**
 * Template Name: Archives
 */
get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<?php if ( have_posts() ) : ?>
	<ul>
	<?php while ( have_posts() ) : the_post(); ?>
		<li>
			<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
			<span><?php the_time('F jS'); ?></span>
		</li>
	<?php endwhile; ?>
	</ul>
	<?php endif; ?>
	</main><!-- .site-main -->
</div><!-- .content-area -->
<aside id="secondary" class="sidebar widget-area" role="complementary">
	<ul>
	<?php foreach ( yukei_16_archive_years() as $year ) : ?>
		<li>
			<a href="<?php echo site_url("/{$year->year}/"); ?>" data-year="<?php echo $year->year ?>">
				<span><?php echo $year->year ?></span>
				<span><?php echo $year->q ?></span>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>
</aside><!-- .sidebar .widget-area -->
<?php get_footer(); ?>
