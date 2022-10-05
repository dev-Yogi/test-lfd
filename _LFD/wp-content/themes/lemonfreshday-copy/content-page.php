<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package lemonfreshday
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if( is_front_page() ) : ?>

	<?php else : ?>
	<header class="entry-header">
		<img src="<?php bloginfo('template_url'); ?>/lib/imgs/hdr_<?php the_title(); ?>.jpg" alt="<?php the_title(); ?>" />
		<!--<img src="<?php bloginfo('template_url'); ?>/lib/imgs/hdr_<?php echo( basename(get_permalink()) ); ?>.jpg" alt="<?php the_title(); ?>" />-->
	</header><!-- .entry-header -->
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'lemonfreshday' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'lemonfreshday' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
