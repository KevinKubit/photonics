<?php get_header(); ?>

<?php get_sidebar(); ?>


<section id="content">
	<section class="home-image-box">
		<div class="caption-pad">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("front-page-description") ) : ?>
			<?php endif; ?>
		</div>
        <img src="http://placehold.it/452x317" alt="Photonics" width="452" height="317">
    </section>
        <section class="home-contact home-module">
            <h2 class="pad_right">Contact</h2>
            <p><strong class="pad_right">Thomas E. Murphy </strong></p>
            	<div id="divleft" class="no-margins">
            		<p class="no-margins">Office (ERF 1201J):</p>
            		<p>Office (KEB 2130):</p>
            		<p>Fax (IREAP):</p>
            		<p>Assistant (ERF 1201H):</p>
            		<p>Lab (KEB 2135):</p>
            		<p>Lab (ERF 0205A):</p>
           			<p>E-mail:</p>
        		</div>
           		<div id="divright" class="no-margins"> 
                	<p>301-405-0030</p>
                    <p>301-405-3602</p>
                    <p>301-314-9437</p>
                    <p>301-405-4951</p>
                    <p>301-405-0698</p>
                    <p>301-405-7470</p>
                    <p><a href="mailto:tem@umd.edu">tem@umd.edu</a></p>
                </div>
             <a href="#" class="pad_right">[more]</a>
            
    	</section>
    
        <section class="home-news home-module">
            <h2 class="pad_right">News / Announcements</h2>
	<?php
            $args = array( 'posts_per_page' => -1 );
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <div>
                    <h3><a href="<?php esc_url(the_permalink()); ?>"><?php esc_html(the_title()); ?></a></h3>
                    <?php the_content(); ?>
                </div>
            <?php endwhile; ?>
    	</section>
    
</section>
<?php get_footer(); ?>
