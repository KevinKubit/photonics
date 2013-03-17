<?php get_header(); ?>

<?php get_sidebar(); ?>
<section id="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
        
        <h1><?php esc_html(the_title()); ?></h1>
        
        <?php
		$model = esc_html(get_post_meta(get_the_ID(), 'model', true));
		if (!empty($model)) { ?>
		<div class="model meta"><span>Model:</span> <?php echo $model; ?></div>
        <?php } ?>
		
		<?php
		$location = esc_html(get_post_meta(get_the_ID(), 'location', true));
		if (!empty($location)) { ?>
		<div class="location meta"><span>Location:</span> <?php echo $location; ?></div>
        <?php } ?>
        
        <?php
		$serial_number = esc_html(get_post_meta(get_the_ID(), 'serial_number', true));
		if (!empty($serial_number)) { ?>
		<div class="serial_number meta"><span>Serial Number:</span> <?php echo $serial_number; ?></div>
        <?php } ?>
        
        <?php
		$inventory_number = esc_html(get_post_meta(get_the_ID(), 'inventory_number', true));
		if (!empty($inventory_number)) { ?>
		<div class="inventory-number meta"><span>Inventory Number:</span> <?php echo $inventory_number; ?></div>
        <?php } ?>
	
        <?php
        $line = array();
        $manual = get_post_meta(get_the_ID(), 'manual', true);
        $operating_procedure = get_post_meta(get_the_ID(), 'operating_procedure', true);
        if (!empty($manual))
        	$line[] = '<a href="' . esc_url(wp_get_attachment_url($manual)) . '">Manual</a>';
        if (!empty($operating_procedure))
            $line[] = '<a href="' . esc_url(wp_get_attachment_url($operating_procedure)) . '">Operating Procedure</a>';
	
        ?>
	
        <?php if (!empty($line)) { ?>
        <div class="files meta"><span>Files:</span> <?php echo implode(', ', $line); ?></div>
        <?php } ?>
        
        <div class="update"><a href="<?php echo esc_url(get_edit_post_link(get_the_ID())); ?>" class="button">Update Details for This Item</a></div>
	
        <?php
		$content = get_the_content();
		if (!empty($content)) { ?>
        <div class="entry"><?php the_content(); ?></div>
        <?php } ?>
    </div>
	<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>