<?php get_header(); ?>

<?php get_sidebar(); ?>
<section id="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

		<h1><?php esc_html(the_title()); ?></h1>
        
        <?php
		$publication_date = esc_html(get_post_meta(get_the_ID(), 'publication_date', true));
		if (!empty($publication_date)) {
		?>
        <div class="publication-date meta"><span>Publication Date:</span> <?php echo date('F j, Y', $publication_date); ?></div>
       	<?php } ?>
        
        <?php
		$authors = esc_html(get_post_meta(get_the_ID(), 'authors', true));
		if (!empty($authors)) {
		?>
        <div class="authors meta"><span>Authors:</span> <?php echo $authors; ?></div>
       	<?php } ?>
        
		<?php
		$manuscript = get_post_meta(get_the_ID(), 'manuscript', true);
		if (!empty($manuscript)) {
		?>
        <div class="download meta"><span>Download:</span> <a href="<?php esc_url(wp_get_attachment_url($manuscript)); ?>">Manuscript</a></div>
       	<?php } ?>
       
        <?php
		$doi = get_post_meta(get_the_ID(), 'doi', true);
		if (!empty($doi)) {
		?>
        <div class="doi meta"><span>DOI:</span> <a href="http://dx.doi.org/<?php echo esc_url($doi); ?>"><?php echo esc_html($doi); ?></a></div>
        <?php } ?>
        
        <?php
		$citation = get_post_meta(get_the_ID(), 'full_citation', true);
		if (!empty($citation)) {
		?>
        <div class="citation meta"><span>Citation:</span> <?php echo apply_filters( 'the_content', $citation ); ?></div>
		<?php } ?>
        
        <?php
		$bibtex = get_post_meta(get_the_ID(), 'bibtex', true);
		$ris = get_post_meta(get_the_ID(), 'ris', true);
		if (!empty($bibtex) || !empty($ris)) {
		?>
        <div class="export meta">
        <span>Export:</span> 
        <?php if (!empty($bibtex)) { ?><a href="<?php echo esc_url(wp_get_attachment_url($bibtex)); ?>">BiBTeX (BIB)</a><?php } ?> 
        <?php if (!empty($ris)) { ?><a href="<?php echo esc_url(wp_get_attachment_url($ris)); ?>">EndNote (RIS)</a><?php } ?>
        </div>
        <?php } ?>
        
		<?php
		$abstract = esc_html(get_post_meta(get_the_ID(), 'abstract', true));
		if (!empty($abstract)) {
		?>
        <div class="abstract">
        	<div><span>Abstract</span></div>
        	<?php echo $abstract; ?>
        </div>
        <?php } ?>
        
        <?php
		$supp_files = get_post_meta(get_the_ID(), 'supplemental_files', true);
		if (!empty($supp_files)) {
		?>
        <div class="supplemental_files_title">Supplemental Files:</div>
        <ul class="supplemental_files">
        <?php foreach ($supp_files as $file) { ?>
        	<li>
            	<span><?php echo esc_html($file['name']); ?>:</span> <?php echo esc_html($file['description']); ?>
            	<div><a href="<?php echo esc_url(wp_get_attachment_url($file['aid'])); ?>"><?php echo esc_html(basename(wp_get_attachment_url($file['aid']))); ?></a></div>
            </li>
        <?php } ?>
        </ul>
        <?php } ?>
        
		<?php the_content(); ?>

	</div>
	<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>