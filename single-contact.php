<?php get_header(); ?>

<?php get_sidebar(); ?>
<section id="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
        
        <?php if(has_post_thumbnail()) { ?>
        <div class="photo">
        <?php the_post_thumbnail(); ?>
        </div>
        <?php } ?>
        
        <h1><?php esc_html(the_title()); ?>, <?php echo esc_html(get_post_meta(get_the_ID(), 'department_role', true)); ?></h1>
        
        <?php
		$degrees = esc_html(get_post_meta(get_the_ID(), 'degrees', true));
		if (!empty($degrees)) { ?>
		<div class="degrees meta"><span>Degrees:</span> <?php echo $degrees; ?></div>
        <?php } ?>
		
		<?php
        if ( is_user_logged_in() ) {
            $address = esc_html(get_post_meta(get_the_ID(), 'address', true));
            if (!empty($address)) { ?>
		<div class="address meta"><span>Address (Home):</span> <?php echo $address; ?></div>
        <?php
            }
        }
        ?>
        
        <?php
		$office = esc_html(get_post_meta(get_the_ID(), 'office', true));
		if (!empty($office)) { ?>
		<div class="office meta"><span>Office:</span> <?php echo $office; ?></div>
        <?php } ?>
        
        <?php
        if ( is_user_logged_in() ) {
            $phone = esc_html(get_post_meta(get_the_ID(), 'phone', true));
            if (!empty($phone)) { ?>
		<div class="phone meta"><span>Phone (Mobile):</span> <?php echo $phone; ?></div>
        <?php
            }
        }
        ?>

		<?php
		$phone_office = esc_html(get_post_meta(get_the_ID(), 'phone_office', true));
		if (!empty($phone_office)) { ?>
		<div class="phone-office meta"><span>Phone (Office):</span> <?php echo $phone_office; ?></div>
        <?php } ?>

		<?php
        if ( is_user_logged_in() ) {
            $phone_home = esc_html(get_post_meta(get_the_ID(), 'phone_home', true));
            if (!empty($phone_home)) { ?>
		<div class="phone-home meta"><span>Phone (Home):</span> <?php echo $phone_home; ?></div>
        <?php
            }
        }
        ?>
        
        <?php
		$fax = esc_html(get_post_meta(get_the_ID(), 'fax', true));
		if (!empty($fax)) { ?>
		<div class="fax meta"><span>Fax:</span> <?php echo $fax; ?></div>
        <?php } ?>

		<?php
		$url = get_post_meta(get_the_ID(), 'url', true);
		if (!empty($url)) { ?>
		<div class="url meta"><span>URL:</span> <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $url ); ?></a></div>
        <?php } ?>
        
        <?php
		$email = esc_html(get_post_meta(get_the_ID(), 'email', true));
		if (!empty($email)) { ?>
		<div class="email meta"><span>Email:</span> <?php echo str_replace('.', ' [dot] ', str_replace('@', ' [at] ', $email)); ?></div>
        <?php } ?>
        
        <?php
		$content = get_the_content();
		if (!empty($content)) { ?>
        <div class="entry"><?php the_content(); ?></div>
        <?php } ?>

		<?php
        if ( is_user_logged_in() ) {
            $private_info = get_post_meta(get_the_ID(), 'private_info', true);
            if (!empty($content)) { ?>
        <div class="private-info">
			<div class="title">Private Information</div>
            <?php echo apply_filters( 'the_content', $private_info ); ?>
		</div>
        <?php
            }
        }
        ?>
    </div>
	<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>