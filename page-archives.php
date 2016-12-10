<?php
/**
 * Template Name: Archives
 */
global $wp_query;
$with_posts  = yukei_16_archive_years();
$active_year = empty( $wp_query->get('page') ) ? date_i18n('Y') : $wp_query->get('page');
get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<select name="year" id="archive-years-select" class="archive-years__select">
	<?php foreach ( $with_posts as $year ) : ?>
		<option value="<?php echo $year->year ?>"<?php echo $year->year == $active_year ? ' selected' : ''; ?>><?php echo "{$year->year} ({$year->q})"; ?></option>
	<?php endforeach; ?>
	</select>
	<?php if ( have_posts() ) : ?>
	<ul id="posts-archive" class="posts-archive">
	<?php while ( have_posts() ) : the_post(); ?>
		<li class="clear archive-post">
			<a href="<?php the_permalink() ?>">
				<span class="archive-post__title"><?php the_title() ?></span>
				<time class="archive-post__date" datetime="<?php the_time('c') ?>"><?php the_time('F jS'); ?></time>
			</a>
		</li>
	<?php endwhile; ?>
	</ul>
	<?php endif; ?>
	<script type="text/x-template" id="archive-post__template">
		<li class="clear archive-post">
	   		<a href="<%- url %>">
	   			<span class="archive-post__title"><%- title %></span>
	   			<time class="archive-post__date"><%- date %></time>
	   		</a>
	   	</li>
	</script>
	</main><!-- .site-main -->
</div><!-- .content-area -->
<aside id="secondary" class="sidebar widget-area archive-years__container" role="complementary">
	<ul class="archive-years" id="posts-archive-years">
	<?php foreach ( $with_posts as $year ) : ?>
		<li class="archive-year clear<?php echo $active_year == $year->year ? ' archive-year--active' : '' ?>" id="archive-year-<?php echo $year->year; ?>">
			<a href="<?php echo site_url("/archive/{$year->year}/"); ?>" data-year="<?php echo $year->year ?>">
				<span class="archive-year__name"><?php echo $year->year ?></span>
				<span class="archive-year__count"><?php echo $year->q ?></span>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>
</aside><!-- .sidebar .widget-area -->
<?php get_footer(); ?>