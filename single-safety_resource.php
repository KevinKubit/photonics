<?php get_header(); ?>

<?php get_sidebar(); ?>
<section id="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
        
        <h1><?php the_title(); ?></h1>
        
        <?php
		$sop = get_post_meta(get_the_ID(), 'sop', true);
		if (!empty($sop)) { ?>
		<div class="sop meta"><span>Safety Operating Procedures:</span> <a href="<?php echo esc_url(wp_get_attachment_url($sop)); ?>">[Link]</a></div>
        <?php } ?>

        <?php
		$chp = get_post_meta(get_the_ID(), 'chp', true);
		if (!empty($chp)) { ?>
		<div class="chp meta"><span>Chemical Hygiene Plans:</span> <a href="<?php echo esc_url(wp_get_attachment_url($chp)); ?>">[Link]</a></div>
        <?php } ?>

        <?php
		$msds = get_post_meta(get_the_ID(), 'msds', true);
		if (!empty($msds)) { ?>
		<div class="msds meta"><span>Material Safety Data Sheets:</span> <a href="<?php echo esc_url(wp_get_attachment_url($msds)); ?>">[Link]</a></div>
        <?php } ?>

	
        <?php
		$content = get_the_content();
		if (!empty($content)) { ?>
        <div class="entry"><?php the_content(); ?></div>
        <?php } ?>
    </div>
	<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>