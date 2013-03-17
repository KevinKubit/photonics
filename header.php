<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<?php if (is_search()) { ?><meta name="robots" content="noindex, nofollow"> <?php } ?>
<title><?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; }
		      elseif (is_search()) {
		         echo 'Search Results - '; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; }
		      elseif (is_404()) {
		         echo 'Not Found - '; }
		      if (is_home()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      else {
		          bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/html5shiv.js"></script>
<![endif]-->
<?php wp_head(); ?>	
</head>
<body <?php body_class(); ?>>

<div id="wrap">

<section id="top"></section>

<div id="inner-wrap">

<div id="page">
   <header>
       <div class="header-wrap">
	  <nav class="menu">
			<ul>

			<li class="home"><a href="#" alt="Home">Home</a></li>

			<li class="search"><a href="#" alt="Search">Search</a></li>

			</ul>
	  </nav>
		<div class="logo">
			 Photonics Research Laboratory
		</div>
		<p class="sublogo"> 
      			Associate Professor Thomas E. Murphy
       		</p>
       </div>
   </header>
    
   <div class="content-wrap">
