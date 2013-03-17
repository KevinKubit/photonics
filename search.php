<?php get_header(); ?>

<?php get_sidebar(); ?>

<section id="content">
	<h1>Search Results</h1>
    
    <!--<form class="ajax-search hide">
    <input type="hidden" name="action" value="ajax_submit" />
    	<div class="basic">
        	<div class="advanced">+ advanced</div>
        	<input type="text" class="search" value="<?php echo esc_attr($_GET['s']); ?>" name="s" /> <input type="submit" value="Search" class="button" />
        </div>
    </form>-->
    
	<?php if (have_posts()) { ?>
    <ul>
    <?php while (have_posts()) { the_post(); ?>
    	<li>
        
        <?php if (get_post_type() == 'publication') { ?>
        	<a href="<?php esc_url(the_permalink()); ?>">(<?php echo esc_html(get_post_meta(get_the_ID(), 'identifier', true)); ?>) <?php esc_html(the_title()); ?></a>
        <?php } else { ?>
        	<a href="<?php esc_url(the_permalink()); ?>"><?php esc_html(the_title()); ?></a>
        <?php } ?>
        
        </li>
	<?php } ?>
    </ul>
    
    <div class="navigation">
    	<div class="prev-posts"><?php previous_posts_link('&lt; Last') ?></div>
        <div class="next-posts"><?php next_posts_link('Next &gt;') ?></div>
    </div>
    <?php } else { ?>
	
	<p>Nothing found.</p>
    
	<?php } ?>
</section>
<?php get_footer(); ?>
