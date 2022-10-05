<?php
/**
 * Template Name: Photos
 *
 * @package lemonfreshday
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
		<img src="<?php bloginfo('template_url'); ?>/lib/imgs/hdr_<?php the_title(); ?>.jpg" alt="<?php the_title(); ?>" />
		<!--<img src="<?php bloginfo('template_url'); ?>/lib/imgs/hdr_<?php echo( basename(get_permalink()) ); ?>.jpg" alt="<?php the_title(); ?>" />-->
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php the_content(); ?>
				
					<div>
						<?php require("flickr_gallery.php"); ?>
					</div>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php edit_post_link( __( 'Edit', 'lemonfreshday' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
