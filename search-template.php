<?php
/* Template Name: Search */
?>

<?php get_header(); ?>

<?php get_sidebar(); ?>
<section id="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<h1><?php esc_html(the_title()); ?></h1>
		<div class="entry">
			<?php the_content(); ?>
		</div>
        
        <?php get_advanced_search_form(); ?>
	</div>
	<?php endwhile; endif; ?>
</section>
<?php get_footer(); ?>
