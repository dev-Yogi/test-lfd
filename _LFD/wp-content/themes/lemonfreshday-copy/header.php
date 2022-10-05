<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package lemonfreshday
 */

  $bg = array('header_01.jpg', 'header_02.jpg', 'header_03.jpg' ); // array of filenames

  $i = rand(0, count($bg)-1); // generate random number size of the array
  $selectedBg = "$bg[$i]"; // set variable equal to which random filename was chosen
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=1024">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
<!--[if IE]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<style>
.site-branding { background: url(http://lemonfreshday.com/wp-content/themes/lemonfreshday/lib/imgs/<?php echo $selectedBg; ?>) no-repeat; }
</style>

</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<nav id="top-navigation" class="social-navigation" role="navigation">
				<ul>
					<li><img src="<?php bloginfo('template_url'); ?>/lib/imgs/join_us_text.jpg"></li>
					<?php dynamic_sidebar('Top Header'); ?>
				</ul>
			</nav><!-- #site-navigation -->

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #site-navigation -->
		</div><!-- .site-branding -->
	</header><!-- #masthead -->

	<div id="content" class="site-content clearfix">
