<?php
/*
 * @package WordPress
 * @subpackage Lemon_Fresh_Day
*/
get_header();
?>
<html>
<head>

<?php do_action('wp_head'); ?>

<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

</head>
<body>
<div id="main_wrapper">
<div id="wrapper">

<div id="header" class="header">
	<div id="join_us">
	<img src="<?php bloginfo( 'template_url' ); ?>/images/join_us_text.jpg" border="0"><img src="<?php bloginfo( 'template_url' ); ?>/images/join_us_facebook.jpg" border="0"><img src="<?php bloginfo( 'template_url' ); ?>/images/join_us_twitter.jpg" border="0"><img src="<?php bloginfo( 'template_url' ); ?>/images/join_us_myspace.jpg" border="0"><img src="<?php bloginfo( 'template_url' ); ?>/images/join_us_youtube.jpg" border="0">
	</div>
</div>

<div id="menu">
	<?php wp_nav_menu( array('items_wrap' => '%3$s', 'menu_id' => 'menu') ); ?>
</div>

<div id="content">
<?php while ( have_posts() ) : the_post(); ?>

	<?php the_content(); ?>
<?php endwhile; // end of the loop. ?>
<div align="center" style="height: 600px;">
<br /><br /><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/lemonfreshday" width="500" height="556" border_color="#ffff00" show_faces="true" stream="true" header="false"></fb:like-box>
</div>
</div>

<div id="home_sidebar">
<?php dynamic_sidebar("Home Page - Sidebar"); ?>
</div>

<div id="footer">
	<img src="<?php bloginfo( 'template_url' ); ?>/images/logo_footer.jpg">
	<br />
	<span id="footer_text_yellow">Some bands may have you dancing by 11pm... LFD will be f&#ing you by midnight!!!</span>
</div>

</div><!-- End #wrapper div -->
</div><!-- End #main_wrapper div -->

<?php wp_footer(); ?>

</body>
</html>
<?php
get_footer();
?>